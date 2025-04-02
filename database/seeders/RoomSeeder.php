<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::insert([
            [
                'room_name' => 'Deluxe Room',
                'room_type' => 'Deluxe',
                'main_photo' => 'deluxe.jpg',
                'max_adults' => 2,
                'max_children' => 2,
                'price' => 150.00,
                'description' => 'A luxurious deluxe room with modern amenities.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_name' => 'Suite Room',
                'room_type' => 'Suite',
                'main_photo' => 'suite.jpg',
                'max_adults' => 3,
                'max_children' => 2,
                'price' => 250.00,
                'description' => 'A spacious suite with premium services.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_name' => 'Standard Room',
                'room_type' => 'Standard',
                'main_photo' => 'standard.jpg',
                'max_adults' => 2,
                'max_children' => 1,
                'price' => 100.00,
                'description' => 'An affordable room with basic amenities.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
