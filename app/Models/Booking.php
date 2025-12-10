<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function doctor()
{
    return $this->belongsTo(Doctor::class, 'd_id');
}

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'c_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'u_id');
}

public function displayCases() {
    return $this->hasMany(DisplayCase::class, 'booking_id');
}

}
