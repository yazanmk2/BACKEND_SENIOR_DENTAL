<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HR extends Model
{
    use HasFactory;

    protected $table = 'hr'; // ✅ Explicitly set correct table name

    protected $fillable = [
        'u_id',
    ];
}
