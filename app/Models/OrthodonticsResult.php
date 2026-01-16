<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrthodonticsResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'p_id',
        'upper',
        'lower',
        'final',
    ];

    public function panorama()
    {
        return $this->belongsTo(PanoramaPhoto::class, 'p_id');
    }
}
