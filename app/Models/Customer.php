<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['u_id', 'birthdate'];

    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'c_id');
    }

    public function doctorFeedbacks()
    {
        return $this->hasMany(DoctorRateFeedback::class, 'c_id');
    }
}
