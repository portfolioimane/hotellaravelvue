<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'name', 'email', 'phone', 'check_in', 'check_out', 'adults', 'children', 'payment_method', 'total', 'status', 'paid_amount'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
