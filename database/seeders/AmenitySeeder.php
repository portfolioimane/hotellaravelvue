<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;
use App\Models\Room;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'Free WiFi', 'icon' => 'wifi.png', 'description' => 'High-speed internet available throughout the hotel.'],
            ['name' => 'Swimming Pool', 'icon' => 'pool.png', 'description' => 'Outdoor swimming pool with lounge area.'],
            ['name' => 'Parking', 'icon' => 'parking.png', 'description' => 'Free parking for hotel guests.'],
            ['name' => 'Air Conditioning', 'icon' => 'ac.png', 'description' => 'Rooms equipped with air conditioning.'],
            ['name' => 'Breakfast Included', 'icon' => 'breakfast.png', 'description' => 'Complimentary breakfast every morning.']
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        // Attach amenities to rooms
        $rooms = Room::all();
        foreach ($rooms as $room) {
            $room->amenities()->attach([1, 2, 3]); // Attaching first 3 amenities to each room
        }
    }
}
