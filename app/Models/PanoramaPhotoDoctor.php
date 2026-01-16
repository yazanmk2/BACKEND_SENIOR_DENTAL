<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanoramaPhotoDoctor extends Model
{
    use HasFactory;

    protected $table = 'panorama_photos_doctors';

    protected $fillable = [
        'd_id',
        'photo',
        'customer_name',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'd_id');
    }

    public function teeth()
    {
        return $this->hasMany(TeethDoctor::class, 'p_id');
    }
}
