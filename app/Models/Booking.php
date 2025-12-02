<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'c_id',
        'd_id',
        'date',
        'time',
        'note',
        'status',
    ];

    // Relationship to doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'd_id');
    }

    // Relationship to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'c_id');
    }
}
