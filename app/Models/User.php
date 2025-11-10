<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'first_name',
        'father_name',
        'last_name',
        'phone',
        'email',
        'password',
        'address',
        'gender',
        'type',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'u_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'u_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'u_id');
    }

    public function hr()
    {
        return $this->hasOne(HR::class, 'u_id');
    }

    public function applicationFeedbacks()
    {
        return $this->hasMany(ApplicationRateFeedback::class, 'u_id');
    }
}
