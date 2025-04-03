<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\PhotoGallery;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RoomsController extends Controller
{
    public function index()
    {
        // Fetch all rooms
        $rooms = Room::with('amenities', 'photoGallery')->get();
        Log::info('Fetched all rooms', ['rooms' => $rooms]);
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        // Validate and create a new room
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'max_adults' => 'required|integer',
            'max_children' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'main_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'photoGallery' => 'nullable|array',
            'photoGallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Store main photo if uploaded
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('images/rooms', 'public');
            $data['main_photo'] = '/storage/' . $path;
            Log::info('Room main photo uploaded', ['path' => $path]);
        }

        // Create the room
        $room = Room::create($data);

        // Associate amenities with the room
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        // Handle room photos
        if ($request->hasFile('photoGallery')) {
            foreach ($request->file('photoGallery') as $photo) {
                $path = $photo->store('images/rooms', 'public');
                PhotoGallery::create([
                    'room_id' => $room->id,
                    'photo_url' => '/storage/' . $path,
                ]);
            }
        }

        Log::info('New room created', ['room' => $room]);
        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate and update a room
        $validator = Validator::make($request->all(), [
            'room_name' => 'sometimes|required|string|max:255',
            'room_type' => 'sometimes|required|string|max:255',
            'max_adults' => 'sometimes|required|integer',
            'max_children' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed on update', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = Room::findOrFail($id);
        $data = $request->all();

        // Store new main photo if uploaded, and delete the old one
        if ($request->hasFile('main_photo')) {
            if ($room->main_photo) {
                Storage::delete('public/' . $room->main_photo);
            }
            $path = $request->file('main_photo')->store('images/rooms', 'public');
            $data['main_photo'] = '/storage/' . $path;
        }

        // Update the room
        $room->update($data);

        // Associate amenities with the room
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        // Handle room photos
        if ($request->hasFile('photoGallery')) {
            foreach ($request->file('photoGallery') as $photo) {
                $path = $photo->store('images/rooms', 'public');
                PhotoGallery::create([
                    'room_id' => $room->id,
                    'photo_url' => '/storage/' . $path,
                ]);
            }
        }

        Log::info('Room updated', ['room' => $room]);
        return response()->json($room);
    }

    public function show($id)
    {
        // Fetch a single room by ID, with related amenities and photos
        $room = Room::with('amenities', 'photoGallery')->findOrFail($id);
        Log::info('Fetched room by ID', ['room' => $room]);
        return response()->json($room);
    }

    public function destroy($id)
    {
        // Delete room, related photos, and amenities
        $room = Room::findOrFail($id);

        // Delete main photo if exists
        if ($room->main_photo) {
            Storage::delete('public/' . $room->main_photo);
        }

        // Delete related photos
        foreach ($room->photoGallery as $photo) {
            Storage::delete('public/' . $photo->photo_url);
            $photo->delete();
        }

        // Detach amenities
        $room->amenities()->detach();

        // Delete the room
        $room->delete();
        Log::info('Room deleted', ['room_id' => $id]);
        return response()->json(null, 204);
    }

    public function toggleFeatured($roomId)
    {
        // Find the room by ID
        $room = Room::findOrFail($roomId);

        // Toggle the featured status
        $room->featured = !$room->featured;

        // Save the room with the updated featured status
        $room->save();

        // Return the updated room as a JSON response
        return response()->json($room);
    }
}
