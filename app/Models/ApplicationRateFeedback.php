<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRateFeedback extends Model
{
    use HasFactory;

    protected $table = 'application_rates_feedbacks'; // ✅ Explicitly match table name

    protected $fillable = [
        'u_id',
        'rate',
        'feedback',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
}
