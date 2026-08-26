<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // CLEAR old data first
        Attendance::truncate();

        // Full year 2026
        $startDate = Carbon::create(2026, 1, 1);
        $endDate   = Carbon::create(2026, 12, 31);

        $status = ['attend', 'absent'];
        $students = Student::all();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            foreach ($students as $student) {
                Attendance::create([
                    'check_in'   => '08:00',
                    'check_out'  => '12:00',
                    'date'       => $date->format('Y-m-d'),
                    'status'     => $status[array_rand($status)],
                    'student_id' => $student->id,
                ]);
            }
        }
    }
}

