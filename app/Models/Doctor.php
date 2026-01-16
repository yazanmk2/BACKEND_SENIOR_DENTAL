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
        'certificate',
        'open_time',
        'close_time',
        'average_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }

    public function panoramaPhotos()
    {
        return $this->hasMany(PanoramaPhotoDoctor::class, 'd_id');
    }
}
