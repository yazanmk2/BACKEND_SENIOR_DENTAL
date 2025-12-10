<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisplayCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'd_id',
        'photo_before',
        'photo_after',
        'favorite_flag',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'd_id');
    }

    public function booking()
{
    return $this->belongsTo(Booking::class, 'booking_id');
}
}
