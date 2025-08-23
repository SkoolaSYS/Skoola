<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StateCityPostcodeSeeder::class,
            SchoolSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            AttendanceSeeder::class,
            StudentAttendanceSeeder::class,
        ]);
    }
}
