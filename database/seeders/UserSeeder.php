<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\State;
use App\Models\District;
use App\Models\School;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // ---------------- Parents ----------------
        for ($i = 0; $i < 100; $i++) { 
            $parent = User::firstOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'phone_num' => '0123456789',
                    'password' => bcrypt('password'),
                ]
            );
            $parent->assignRole('parent');
        }

        // ---------------- Admin ----------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin',
                'phone_num' => '0123456789',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // ---------------- Country ----------------
        $country = User::firstOrCreate(
            ['email' => 'country@email.com'],
            [
                'name' => 'Country User',
                'phone_num' => '0123456789',
                'password' => bcrypt('password'),
            ]
        );
        $country->assignRole('country');

        // ---------------- State ----------------
        $states = State::inRandomOrder()->take(5)->pluck('id', 'name'); 
        foreach ($states as $stateName => $stateId) {
            $state = User::firstOrCreate(
                ['email' => "state{$stateId}@example.com"],
                [
                    'name' => "{$stateName} User",
                    'phone_num' => '03000000000',
                    'password' => bcrypt('password'),
                ]
            );

            $state->assignRole('state'); 
            $state->setMeta('user_state_id', $stateId); 
        }

        // ---------------- PPD ----------------
        $ppd = District::inRandomOrder()->take(10)->pluck('id', 'ppd'); 
        foreach ($ppd as $ppdName => $ppdId) {
            $district = User::firstOrCreate(
                ['email' => "PPD{$ppdId}@example.com"],
                [
                    'name' => "{$ppdName} User",
                    'phone_num' => '03000000000',
                    'password' => bcrypt('password'),
                ]
            );

            $district->assignRole('ppd'); 
            $district->setMeta('user_district_id', $ppdId); 
        }

        // ---------------- School ----------------
        $schools = School::inRandomOrder()->take(10)->pluck('id', 'name'); 
        foreach ($schools as $schoolName => $schoolId) {
            $school = User::firstOrCreate(
                ['email' => "school{$schoolId}@example.com"],
                [
                    'name' => "{$schoolName} User",
                    'phone_num' => '03000000000',
                    'password' => bcrypt('password'),
                ]
            );

            $school->assignRole('school'); 
            $school->setMeta('user_school_id', $schoolId); 
        }

        // ---------------- Teachers ----------------
        $teacherSchools = School::inRandomOrder()->take(20)->pluck('id', 'name');
        $counter = 1;

        foreach ($teacherSchools as $schoolName => $schoolId) {
            $teacherId = 'TCH' . str_pad($counter, 3, '0', STR_PAD_LEFT);

            $teacher = User::firstOrCreate(
                ['teacher_id' => $teacherId],
                [
                    'name' => "{$faker->firstName} Teacher ({$schoolName})",
                    'phone_num' => '0311111111',
                    'email' => "teacher{$teacherId}@example.com",
                    'password' => bcrypt('password'),
                ]
            );

            $teacher->assignRole('teacher'); 
            $teacher->setMeta('user_school_id', $schoolId); 

            $counter++;
        }
    }
}
