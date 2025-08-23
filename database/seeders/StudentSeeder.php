<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomSchools = School::inRandomOrder()->take(1000)->get(); //set 1000 students
        $randomParents = $randomParents = User::role('parent')->inRandomOrder()->get();

        foreach ($randomSchools as $randomSchool) {
            $stateId = $randomSchool->state_id;
            $districtId = $randomSchool->district_id;
            $schoolId = $randomSchool->id;

            $randomParent = $randomParents->random(); // Get a random parent from the collection
            $parentUserId = $randomParent->id;

            $studentName = fake()->name(); 

            Student::create([
                'name' => $studentName,
                'ic' => '110101120000',
                'state_id' => $stateId,
                'district_id' => $districtId,
                'school_id' => $schoolId,
                'parent_id' => $parentUserId,
            ]);
        }
    }
}
