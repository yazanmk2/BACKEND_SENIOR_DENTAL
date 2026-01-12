<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teeth extends Model
{
    use HasFactory;

    protected $table = 'teeth';

  protected $fillable = [
    'p_id',
    'name',
    'photo_panorama_generated',
    'photo_icon',
    'descripe',
    'number',
    'confidence', // ✅ ADD THIS
];

}
