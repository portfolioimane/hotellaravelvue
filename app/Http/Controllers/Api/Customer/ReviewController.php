<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    // Fetch the latest three featured reviews with the user and room relationships
    public function latestFeaturedReviews()
    {
        // Fetch only featured reviews, ordered by the most recent, with user and room relationships eager-loaded
        $reviews = Review::with(['user', 'room']) // Eager load user and room relationships
                         ->where('is_featured', true) // Only get featured reviews
                         ->latest() // Order by created_at in descending order (latest)
                         ->take(3) // Limit to the latest three reviews
                         ->get();

        // Return the reviews as JSON
        return response()->json($reviews);
    }

    // Fetch reviews for a specific room
    public function index($roomId)
    {
        // Fetch only approved reviews for the given roomId
        $reviews = Review::with('user', 'room') // Eager load the user and room relationships
                         ->where('room_id', $roomId)
                         ->where('status', 'approved') // Filter reviews by approved status
                         ->get();

        // Return reviews as JSON
        return response()->json($reviews);
    }

    // Submit a review for a specific room
    public function store(Request $request, $roomId)
    {
        $validatedData = $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        Log::info('Review validation passed.', ['validated_data' => $validatedData]);

        // Find the room
        try {
            $room = Service::findOrFail($roomId);
            Log::info('Service found.', ['room_id' => $roomId]);
        } catch (\Exception $e) {
            Log::error('Service not found.', ['room_id' => $roomId, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Service not found.'], 404);
        }

        // Create the new review
        $review = new Review([
            'review' => $request->review,
            'rating' => $request->rating,
            'user_id' => auth()->id(), // Assuming the user is authenticated
        ]);

        Log::info('Review created.', [
            'user_id' => auth()->id(),
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        // Save the review and associate it with the room via the relationship
        try {
            $room->reviews()->save($review);
            Log::info('Review saved and associated with the room.', ['room_id' => $roomId, 'review_id' => $review->id]);
        } catch (\Exception $e) {
            Log::error('Failed to save review.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save review.'], 500);
        }

        // Eager load the user and room relationships
        $review = $review->load('user', 'room');  // Load both 'user' and 'room' relationships

        // Return the review with associated user and room
        return response()->json($review, 201);
    }
}
