<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2023, 8, 1);
        $endDate = Carbon::create(2023, 8, 31);

        $studentCount = 1000;
        $status = ['attend', 'absent'];

        $students = Student::inRandomOrder()->get();

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            foreach ($students as $student) {
                Attendance::create([
                    'check_in' => '08:00',
                    'check_out' => '12:00',
                    'date' => $date->format('Y-m-d'),
                    'status' => $status[rand(0, 1)], // attend / absent,
                    'student_id' => $student->id,
                ]);
            }
        }
        
    }
}
