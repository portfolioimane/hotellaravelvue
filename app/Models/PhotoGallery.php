<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'photo_url'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
