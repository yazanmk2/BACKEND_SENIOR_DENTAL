<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

protected $fillable = [
    'u_id',
    'cv',
    'specialization',
    'previous_works',
    'open_time',
    'close_time',
    'average_rate',
 ];


    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'd_id');
    }

    public function displayCases()
    {
        return $this->hasMany(DisplayCase::class, 'd_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(DoctorRateFeedback::class, 'd_id');
    }
}
