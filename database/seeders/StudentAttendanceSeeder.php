<?php

namespace Database\Seeders;
use App\Models\StudentAttendance;
use App\Models\School;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $startDate = Carbon::create(2023, 8, 1);
        $endDate = Carbon::create(2023, 8, 31);

        $totalStudents = 1000;

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            // Generate random attendance and absent counts for each student and date
            $totalAttend = rand(0, $totalStudents);
            $totalAbsent = $totalStudents - $totalAttend;

            // Insert the generated data into the 'student_attendances' table
            StudentAttendance::create([
                'date' => $date->format('Y-m-d'),
                'total_student' => $totalStudents,
                'total_attend' => $totalAttend,
                'total_absent' => $totalAbsent,
                'school_id' => School::inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
