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
        $randomSchools = School::inRandomOrder()->take(1000)->get(); // set 1000 students
        $randomParents = User::role('parent')->inRandomOrder()->get();

        foreach ($randomSchools as $randomSchool) {
            $stateId = $randomSchool->state_id;
            $districtId = $randomSchool->district_id;
            $schoolId = $randomSchool->id;

            $randomParent = $randomParents->random(); // Get a random parent from the collection

            $student = Student::create([
                'name'        => fake()->name(),
                'ic'          => fake()->numerify('############'),
                'state_id'    => $stateId,
                'district_id' => $districtId,
                'school_id'   => $schoolId,
            ]);

            // Attach to pivot (this is what your dashboard expects)
            $randomParent->students()->attach($student->id);

            // If you want to keep parent_id column in students table:
            $student->update(['parent_id' => $randomParent->id]);
        }
    }
}
