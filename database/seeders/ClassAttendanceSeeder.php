<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassAttendance;
use App\Models\Student;
use Carbon\Carbon;

class ClassAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $teacherId = 147;

        $gradesClasses = [
            'Darjah 3' => '3A',
            'Darjah 4' => '4A',
        ];

        foreach ($gradesClasses as $grade => $className) {
            $students = Student::where('grade', $grade)
                                ->where('class_name', $className)
                                ->pluck('id')
                                ->toArray();

            if (empty($students)) {
                $this->command->warn("No students found for $grade $className, skipping...");
                continue;
            }

            foreach ($students as $studentId) {
                for ($dayOffset = 0; $dayOffset < 30; $dayOffset++) {
                    $date = Carbon::now()->subDays($dayOffset);

                    // Random status with probabilities: 70% Present, 20% Absent, 10% Late
                    $rand = rand(1, 100);
                    if ($rand <= 70) {
                        $status = 'Present';
                    } elseif ($rand <= 90) {
                        $status = 'Absent';
                    } else {
                        $status = 'Late';
                    }

                    ClassAttendance::create([
                        'student_id'      => $studentId,
                        'teacher_id'      => $teacherId,
                        'grade'           => $grade,
                        'class_name'      => $className,
                        'subject'         => 'Mathematics',
                        'status'          => $status,
                        'attendance_time' => $date->setTime(rand(7, 10), rand(0, 59)), // morning time
                    ]);
                }
            }
        }
    }
}
