<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name', 'room_type', 'main_photo', 'max_adults', 'max_children', 'price', 'description', 'featured'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function photoGallery()
    {
        return $this->hasMany(PhotoGallery::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

      public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
