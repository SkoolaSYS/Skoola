<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        // create 1000 random students linked to random schools
        $randomSchools = School::inRandomOrder()->take(1000)->get();
        $randomParents = User::role('parent')->inRandomOrder()->get();

        foreach ($randomSchools as $randomSchool) {
            $stateId = $randomSchool->state_id;
            $districtId = $randomSchool->district_id;
            $schoolId = $randomSchool->id;

            $randomParent = $randomParents->random();

            $dob = $faker->dateTimeBetween('-17 years', '-7 years'); // age 7–17
            $age = now()->year - $dob->format('Y');

            $student = Student::create([
                'name'          => $faker->name(),
                'ic'            => $faker->numerify('############'),
                'state_id'      => $stateId,
                'district_id'   => $districtId,
                'school_id'     => $schoolId,
                'age'           => $age,
                'birth_cert_no' => $faker->numerify('BC########'),
                'dob'           => $dob->format('Y-m-d'),
                'grade'         => $faker->randomElement([
                    'Darjah 1','Darjah 2','Darjah 3','Darjah 4','Darjah 5','Darjah 6',
                    'Tingkatan 1','Tingkatan 2','Tingkatan 3','Tingkatan 4','Tingkatan 5'
                ]),
                'class_name'    => $faker->randomElement([
                    '1A','1B','2A','2B','3A','3B','4A','4B','5A','5B'
                ]),
                'gender'        => $faker->randomElement(['Male','Female']),
                'race'          => $faker->randomElement(['Malay','Chinese','Indian','Others']),
                'religion'      => $faker->randomElement(['Islam','Christianity','Buddhism','Hinduism','Others']),
                'nationality'   => 'Malaysian',
                'orphan'        => $faker->boolean(10) ? 'Yes' : 'No', // 10% orphan
                'oku'           => $faker->boolean() ? 'Yes' : 'No',   // Yes/No only
                'address'       => $faker->address(),
            ]);

            // Attach student to parent (pivot only)
            $randomParent->students()->attach($student->id);
        }
    }
}
