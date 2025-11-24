<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRateFeedback extends Model
{
    use HasFactory;

    // 👇 Explicitly link the correct table name
    protected $table = 'doctors_rates_feedbacks';

    protected $fillable = [
        'c_id',
        'd_id',
        'rate',
        'feedback',
    ];
}
