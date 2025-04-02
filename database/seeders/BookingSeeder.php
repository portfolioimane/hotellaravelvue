<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $room = Room::first();
        $user = User::first(); // Make sure you have a user in the database

        Booking::create([
            'user_id' => $user->id ?? 1,
            'room_id' => $room->id ?? 1,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '0612345678',
            'check_in' => now()->addDays(1),
            'check_out' => now()->addDays(4),
            'payment_method' => 'credit_card',
            'total' => 450.00,
            'status' => 'pending',
            'paid_amount' => 0.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
