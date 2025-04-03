<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Service;
use App\Models\PaymentSetting;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\BuisnessHour;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;
use App\Jobs\SendBookingReminder;  // Make sure to import the SendBookingReminder job
use App\Services\PayPalService; // Import PayPalService
use App\Models\Room;

class BookingController extends Controller
{
    private $paypal;

    public function __construct(PayPalService $paypalService)
    {
        // Get the complete PayPal configuration from the service
        $paypalConfig = $paypalService->getPayPalConfig();
            $mode = $paypalConfig['mode'];
    if (!empty($paypalConfig[$mode]['client_id']) && !empty($paypalConfig[$mode]['client_secret'])) {
        // Initialize PayPal client with API credentials
        $this->paypal = new PayPalClient;

        // Set API credentials by directly passing the entire config array
        $this->paypal->setApiCredentials($paypalConfig);

        // Set the access token
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }else {
        // Handle the case where the PayPal credentials are not available for the selected mode
        \Log::warning("PayPal credentials are missing or incomplete for mode: $mode.");
        $this->paypal = null; // Set to null or handle appropriately
    }
    }

    /**
     * Get all bookings for the authenticated user
     */
    public function myBookings(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $bookings = Booking::where('user_id', Auth::id())
            ->with('room')
            ->paginate($perPage);

        return response()->json([
            'bookings' => $bookings,
        ], 200);
    }

    /**
     * Get details of a specific booking
     */
    public function show($id)
    {
        $booking = Booking::with('service')->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking, 200);
    }

    /**
     * Create a new booking
     */

public function create(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'room_id' => 'required|exists:rooms,id', // Assuming you now have room_id
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in', // Ensure check_out is after check_in
        'adults' => 'required|integer|min:1',  // Validate adults
        'children' => 'required|integer|min:0', // Validate children
        'payment_method' => 'required|string',
        'total' => 'required|numeric',
    ]);

    // Convert the check-in and check-out dates to the correct format using Carbon
    $checkInDate = Carbon::createFromFormat('d/m/Y', $validated['check_in'])->format('Y-m-d');
    $checkOutDate = Carbon::createFromFormat('d/m/Y', $validated['check_out'])->format('Y-m-d');

    // Retrieve the selected room (if you are adding the `room_id` field)
    $room = Room::find($validated['room_id']);
    if (!$room) {
        return response()->json(['message' => 'Room not found'], 404);
    }

    // Check if the room is available for the selected check-in and check-out dates
$existingBooking = Booking::where('room_id', $room->id)
    ->where('status', 'completed') // Ensure the booking status is 'completed'
    ->where(function ($query) use ($checkInDate, $checkOutDate) {
        $query->whereBetween('check_in', [$checkInDate, $checkOutDate])
              ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
              ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                  $query->where('check_in', '<=', $checkInDate)
                        ->where('check_out', '>=', $checkOutDate);
              });
    })
    ->exists();


    if ($existingBooking) {
        return response()->json(['message' => 'The selected room is already booked for these dates'], 400);
    }

    // Set booking status and paid_amount based on payment_method
    $status = 'pending'; // Default status
    $paidAmount = 0;

    if (in_array($validated['payment_method'], ['stripe', 'paypal'])) {
        $status = 'completed'; // Set status to confirmed for Stripe/PayPal
        $paidAmount = 50; // Set the booking fee as the paid amount
    }

    // Get the authenticated user ID
    $userId = Auth::id(); // This is from Laravel Sanctum authentication

    // Proceed to create the booking
    $booking = Booking::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'room_id' => $validated['room_id'],
        'check_in' => $checkInDate,  // Use the formatted check-in date
        'check_out' => $checkOutDate, // Use the formatted check-out date
        'adults' => $validated['adults'], // Store the number of adults
        'children' => $validated['children'], // Store the number of children
        'payment_method' => $validated['payment_method'],
        'total' => $validated['total'],
        'status' => $status,
        'paid_amount' => $paidAmount,
        'user_id' => $userId,
    ]);

    // Optionally schedule a reminder for payment confirmation (uncomment if needed)
    /*
    if (in_array($validated['payment_method'], ['stripe', 'paypal'])) {
        $bookingDate = Carbon::parse($validated['check_in']);
        $reminderTime = $bookingDate->subDay();  // Subtract one day from the check-in date

        if ($bookingDate->isToday()) {
            // Send reminder immediately if today
            SendBookingReminder::dispatch($booking)->delay(now());
        } else {
            // Schedule reminder one day before
            SendBookingReminder::dispatch($booking)->delay($reminderTime);
        }

        Log::info('Booking reminder scheduled for booking ID: ' . $booking->id);
    }
    */

    // Return the booking creation response
    return response()->json([
        'message' => 'Booking initiated successfully!',
        'booking' => $booking,
    ], 201);
}



    /**
     * Create Stripe payment for booking
     */
    public function createStripePayment(Request $request)
    {
        try {
            $totalAmount = $request->input('total');
            $amountInCents = $totalAmount * 100;

            $stripeSettings = PaymentSetting::where('provider', 'stripe')->where('enabled', true)->first();
            if (!$stripeSettings || !$stripeSettings->secret_key) {
                return response()->json(['error' => 'Stripe is not configured'], 500);
            }

            Stripe::setApiKey($stripeSettings->secret_key);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            Log::info('Stripe payment intent created', ['payment_intent_id' => $paymentIntent->id]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret], 200);
        } catch (\Exception $e) {
            Log::error('Error creating Stripe payment intent', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create payment intent'], 500);
        }
    }

    /**
     * Confirm PayPal payment for booking
     */
    public function confirmPaypalPayment(Request $request)
    {
        Log::info('Confirm PayPal Payment API called', ['request' => $request->all()]);

        $paypalOrderId = $request->paypalOrderId;

        if (!$paypalOrderId) {
            Log::error('PayPal Order ID is missing in the request.');
            return response()->json(['error' => 'PayPal Order ID is required'], 400);
        }

        try {
            $orderDetails = $this->paypal->showOrderDetails($paypalOrderId);
            Log::info('Order details fetched from PayPal.', ['orderDetails' => $orderDetails]);

            if (isset($orderDetails['status']) && $orderDetails['status'] === 'COMPLETED') {
                Log::info('PayPal order completed successfully.', ['paypalOrderId' => $paypalOrderId]);
                return response()->json(['status' => 'COMPLETED']);
            }

            Log::warning('PayPal order not completed.', ['orderDetails' => $orderDetails]);
            return response()->json(['status' => 'NOT_COMPLETED'], 400);
        } catch (\Exception $e) {
            Log::error('Error while confirming PayPal payment.', [
                'paypalOrderId' => $paypalOrderId,
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to confirm PayPal payment'], 500);
        }
    }






public function searchAvailableRooms(Request $request)
{
    // Validate the request parameters
    $request->validate([
        'check_in' => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'adults' => 'required|integer|min:1',
        'children' => 'required|integer|min:0',
    ]);

    // Log the incoming request data
    Log::info('Search Request Data:', [
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'adults' => $request->adults,
        'children' => $request->children,
    ]);

    // Parse the check-in and check-out dates
    $checkIn = Carbon::parse($request->check_in);
    $checkOut = Carbon::parse($request->check_out);
    $adults = $request->adults;
    $children = $request->children;

    // Log parsed check-in and check-out dates
    Log::info('Parsed Dates:', [
        'check_in' => $checkIn,
        'check_out' => $checkOut,
    ]);

    // Get all rooms that can accommodate the number of guests
    $availableRooms = Room::where('max_adults', '>=', $adults)
        ->where('max_children', '>=', $children)
        ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
            $query->where(function ($q) use ($checkIn, $checkOut) {
                // Log the date range being checked in the query
                Log::info('Checking room availability for date range:', [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]);

                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function ($q) use ($checkIn, $checkOut) {
                      $q->where('check_in', '<=', $checkIn)
                        ->where('check_out', '>=', $checkOut);
                  });
            })
            // Ensure only completed bookings are excluded
            ->where('status', 'completed');
        })
        ->get();

    // Log the number of available rooms found
    Log::info('Available Rooms Found:', ['available_rooms' => $availableRooms->count()]);

    // Return the available rooms as a JSON response
    return response()->json($availableRooms);
}


public function getUnavailableDates($roomId)
{
    // Retrieve only completed bookings for the specified room
    $bookings = Booking::where('room_id', $roomId)
        ->where('status', 'completed') // Only consider completed bookings
        ->get(['check_in', 'check_out']);

    // Collect all booked dates
    $unavailableDates = [];

    foreach ($bookings as $booking) {
        $startDate = \Carbon\Carbon::parse($booking->check_in);
        $endDate = \Carbon\Carbon::parse($booking->check_out);

        while ($startDate <= $endDate) {
            $unavailableDates[] = $startDate->toDateString(); // Format as 'YYYY-MM-DD'
            $startDate->addDay();
        }
    }

    return response()->json($unavailableDates);
}







}
