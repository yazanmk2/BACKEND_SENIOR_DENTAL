<?php

namespace App\Services\customer;

use Illuminate\Support\Facades\DB;

class DisplayCaseService
{
    public function getFavoriteCases()
    {
        return DB::table('display_cases')
            ->join('doctors', 'display_cases.d_id', '=', 'doctors.id')
            ->join('users', 'doctors.u_id', '=', 'users.id')
            ->select(
                'display_cases.photo_before',
                'display_cases.photo_after',
                'users.first_name',
                'users.last_name',
                'users.id as user_id',
                'doctors.average_rate'
            )
            ->where('display_cases.favorite_flag', 1)
            ->where('doctors.average_rate', '>', 3)
            ->get();
    }
}
