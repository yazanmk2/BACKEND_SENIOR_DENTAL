<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teeth extends Model
{
    use HasFactory;

    protected $table = 'teeth';

    protected $fillable = [
        'p_id',        // panorama photo id
        'name',        // tooth name (e.g. molar)
        'number',      // tooth number
    ];

    /**
     * Each tooth belongs to a panorama photo
     */
    public function panorama()
    {
        return $this->belongsTo(PanoramaPhoto::class, 'p_id');
    }

    /**
     * Optional: load customer through panorama photo
     */
    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,         // final model
            PanoramaPhoto::class,    // intermediate model
            'id',                    // panorama_photos.id
            'id',                    // customers.id
            'p_id',                  // teeth.p_id
            'c_id'                   // panorama_photos.c_id
        );
    }
}
