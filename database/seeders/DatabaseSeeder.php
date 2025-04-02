<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the individual seeders
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,            // Added RoomSeeder
            BookingSeeder::class,         // Added BookingSeeder
            PhotoGallerySeeder::class,    // Added PhotoGallerySeeder
            AmenitySeeder::class,         // Added AmenitySeeder
            HomepageHeaderSeeder::class,
            PatientSeeder::class,
            PatientHistorySeeder::class,
            ServiceSeeder::class,
            BuisnessHourSeeder::class,
        ]);
    }
}

