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
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // ---------- USERS ----------
        $users = collect();

        // Create customers
        for ($i = 0; $i < 30; $i++) {
            $users->push(User::create([
                'first_name' => $faker->firstName,
                'father_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('123456'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'type' => 'customer',
            ]));
        }

        // Create doctors
        for ($i = 0; $i < 10; $i++) {
            $users->push(User::create([
                'first_name' => $faker->firstName,
                'father_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('123456'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'type' => 'doctor',
            ]));
        }

        // Create admins
        for ($i = 0; $i < 3; $i++) {
            $users->push(User::create([
                'first_name' => $faker->firstName,
                'father_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('admin123'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'type' => 'admin',
            ]));
        }

        // Create HR
        for ($i = 0; $i < 2; $i++) {
            $users->push(User::create([
                'first_name' => $faker->firstName,
                'father_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('hr123'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'type' => 'hr',
            ]));
        }

        // ---------- CUSTOMERS ----------
        $customers = collect();
        foreach (User::where('type', 'customer')->get() as $user) {
            $customers->push(Customer::create([
                'u_id' => $user->id,
                'birthdate' => $faker->date(),
                'patient_record' => 'patient_records/patient_record_' . $user->first_name . '_' . $user->last_name . '.pdf',
            ]));
        }

        // ---------- DOCTORS ----------
        $doctors = collect();
        foreach (User::where('type', 'doctor')->get() as $user) {
            $doctors->push(Doctor::create([
                'u_id' => $user->id,
                'cv' => $faker->sentence(8),
                'specialization' => $faker->randomElement(['Orthodontics', 'Implantology', 'Endodontics', 'Surgery']),
                'previous_works' => $faker->sentence(12),
                'open_time' => '09:00:00',
                'close_time' => '17:00:00',
            ]));
        }

        // ---------- ADMINS ----------
        foreach (User::where('type', 'admin')->get() as $user) {
            Admin::create(['u_id' => $user->id]);
        }

        // ---------- HR ----------
        foreach (User::where('type', 'hr')->get() as $user) {
            HR::create(['u_id' => $user->id]);
        }

        // ---------- BOOKINGS ----------
        for ($i = 0; $i < 50; $i++) {
            Booking::create([
                'c_id' => $customers->random()->id,
                'd_id' => $doctors->random()->id,
                'time' => $faker->time(),
                'date' => $faker->date(),
                'note' => $faker->sentence(),
                'status' => $faker->randomElement(['pending', 'confirmed', 'completed']),
            ]);
        }

        // ---------- DISPLAY CASES ----------
        for ($i = 0; $i < 20; $i++) {
            DisplayCase::create([
                'd_id' => $doctors->random()->id,
                'photo_before' => 'before_case_' . $i . '.jpg',
                'photo_after' => 'after_case_' . $i . '.jpg',
                'favorite_flag' => $faker->boolean(),
            ]);
        }

        // ---------- APPLICATION FEEDBACKS ----------
        for ($i = 0; $i < 40; $i++) {
            ApplicationRateFeedback::create([
                'u_id' => $users->random()->id,
                'rate' => $faker->numberBetween(1, 5),
                'feedback' => $faker->sentence(8),
            ]);
        }

        // ---------- DOCTOR FEEDBACKS ----------
        for ($i = 0; $i < 40; $i++) {
            DoctorRateFeedback::create([
                'c_id' => $customers->random()->id,
                'd_id' => $doctors->random()->id,
                'rate' => $faker->numberBetween(1, 5),
                'feedback' => $faker->sentence(8),
            ]);
        }

        echo "✅ Database seeded successfully with large fake data!\n";
    }
}
