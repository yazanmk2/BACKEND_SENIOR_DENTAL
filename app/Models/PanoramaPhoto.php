<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanoramaPhoto extends Model
{
    use HasFactory;

    protected $table = 'panorama_photos';

    protected $fillable = [
        'c_id',
        'photo',
    ];
}
