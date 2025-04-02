<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    // Fetch all rooms with amenities and photo gallery
    public function index()
    {
        // Fetch all rooms with their related amenities and photo gallery
        $rooms = Room::with(['amenities', 'photoGallery'])->get();

        // Log the rooms data
        Log::info('Rooms fetched with amenities and photo gallery: ', $rooms->toArray());

        // Return rooms data as JSON
        return response()->json($rooms);
    }

    // Fetch a specific room by ID with amenities and photo gallery
    public function show($id)
    {
        // Fetch the room by ID with its related amenities and photo gallery
        $room = Room::with(['amenities', 'photoGallery'])->findOrFail($id);

        // Log the room data
        Log::info('Room fetched with amenities and photo gallery: ', $room->toArray());

        // Return the room data as JSON
        return response()->json($room);
    }

    // Fetch featured rooms (featured flag in Room model) with amenities and photo gallery
    public function getFeaturedRooms()
    {
        // Fetch the featured rooms with their related amenities and photo gallery
        $featuredRooms = Room::where('featured', true)
            ->with(['amenities', 'photoGallery'])
            ->latest()
            ->take(4)  // Get the latest 4 featured rooms
            ->get();

        // Log the featured rooms data
        Log::info('Featured rooms fetched with amenities and photo gallery: ', $featuredRooms->toArray());

        // Return the featured rooms data as JSON
        return response()->json($featuredRooms, 200);
    }
}
