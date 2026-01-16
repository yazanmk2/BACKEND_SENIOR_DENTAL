<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeethDoctor extends Model
{
    use HasFactory;

    protected $table = 'teeth_doctor';

    protected $fillable = [
        'p_id',
        'name',
        'photo_panorama_generated',
        'descripe',
        'number',
    ];

    public function panoramaDoctor()
    {
        return $this->belongsTo(PanoramaPhotoDoctor::class, 'p_id');
    }
}
