<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\Admin;
use App\Models\HR;
use App\Models\Booking;
use App\Models\DisplayCase;
use App\Models\ApplicationRateFeedback;
use App\Models\DoctorRateFeedback;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== USERS ==========
        $userCustomer = User::create([
            'first_name' => 'Yazan2',
            'father_name' => 'Ahmad',
            'last_name' => 'Ali',
            'phone' => '0994999999',
            'email' => 'yazan1@example.com',
            'password' => bcrypt('123456'),
            'address' => 'Damascus',
            'gender' => 'male',
            'type' => 'customer',
        ]);

        $userDoctor = User::create([
            'first_name' => 'Dr. Lina2',
            'father_name' => 'Omar',
            'last_name' => 'Hassan',
            'phone' => '0988688888',
            'email' => 'doctor2@example.com',
            'password' => bcrypt('123456'),
            'address' => 'Aleppo',
            'gender' => 'female',
            'type' => 'doctor',
        ]);

        $userAdmin = User::create([
            'first_name' => 'Sara2',
            'father_name' => 'Maher',
            'last_name' => 'Khaled',
            'phone' => '0977577777',
            'email' => 'admin2@example.com',
            'password' => bcrypt('admin123'),
            'address' => 'Homs',
            'gender' => 'female',
            'type' => 'admin',
        ]);

        // ========== ROLE TABLES ==========
        $customer = Customer::create([
            'u_id' => $userCustomer->id,
            'birthdate' => '1995-04-20',
        ]);

        $doctor = Doctor::create([
            'u_id' => $userDoctor->id,
            'cv' => 'Experienced dentist specializing in orthodontics.',
            'specialization' => 'Dentistry',
            'previous_works' => 'Worked at Smile Clinic, Aleppo.',
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ]);

        $admin = Admin::create([
            'u_id' => $userAdmin->id,
        ]);

        // ========== BOOKINGS ==========
        Booking::create([
            'c_id' => $customer->id,
            'd_id' => $doctor->id,
            'time' => '10:30:00',
            'date' => '2025-11-10',
            'note' => 'First-time consultation and check-up.',
            'status' => 'confirmed',
        ]);

        Booking::create([
            'c_id' => $customer->id,
            'd_id' => $doctor->id,
            'time' => '14:00:00',
            'date' => '2025-11-15',
            'note' => 'Teeth whitening session.',
            'status' => 'pending',
        ]);

        // ========== DISPLAY CASES ==========
        DisplayCase::create([
            'd_id' => $doctor->id,
            'photo_before' => 'before_case1.jpg',
            'photo_after' => 'after_case1.jpg',
            'favorite_flag' => true,
        ]);

        DisplayCase::create([
            'd_id' => $doctor->id,
            'photo_before' => 'before_case2.jpg',
            'photo_after' => 'after_case2.jpg',
            'favorite_flag' => false,
        ]);

        // ========== FEEDBACKS ==========
        ApplicationRateFeedback::create([
            'u_id' => $userCustomer->id,
            'rate' => 5,
            'feedback' => 'Very user-friendly app and excellent experience!',
        ]);

        DoctorRateFeedback::create([
            'c_id' => $customer->id,
            'd_id' => $doctor->id,
            'rate' => 4,
            'feedback' => 'Professional and kind doctor. Highly recommended!',
        ]);

        DoctorRateFeedback::create([
            'c_id' => $customer->id,
            'd_id' => $doctor->id,
            'rate' => 5,
            'feedback' => 'Best dental experience I’ve had so far!',
        ]);
    }
}
