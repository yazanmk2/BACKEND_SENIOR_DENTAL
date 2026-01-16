<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanoramaPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'c_id',
        'photo',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'c_id');
    }

    public function teeth()
    {
        return $this->hasMany(Teeth::class, 'p_id');
    }

    public function orthodonticsResult()
    {
        return $this->hasOne(OrthodonticsResult::class, 'p_id');
    }
}
