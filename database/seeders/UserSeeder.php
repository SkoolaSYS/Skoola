<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\State;
use App\Models\District;
use App\Models\School;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) { //create 100 user parents
            $parent = User::create([
                'name' => fake()->name(),
                'phone_num' => '0123456789',
                'email' => fake()->email(),
                'password' => bcrypt('password')
            ]);
            $parent->assignRole('parent');
        }

        $admin = User::create([
            'name' => 'Admin',
            'phone_num' => '0123456789',
            'email' => 'admin@email.com',
            'password' => bcrypt('password')
        ]);
        $admin->assignRole('admin');

        $country = User::create([
            'name' => 'Country User',
            'phone_num' => '0123456789',
            'email' => 'country@email.com',
            'password' => bcrypt('password')
        ]);
        $country->assignRole('country');

        $states = State::inRandomOrder()->take(5)->pluck('id', 'name'); //Testing states User
        foreach ($states as $stateName => $stateId) {
            $state = User::create([
                'name' => "{$stateName} User",
                'phone_num' => '03000000000',
                'email' => "state{$stateId}@example.com",
                'password' => bcrypt('password'),
            ]);

            $state->assignRole('state'); // Assign role
            $state->setMeta('user_state_id', $stateId); // Update the user_state_id meta field
        }

        $ppd = District::inRandomOrder()->take(10)->pluck('id', 'ppd'); //Testing ppd User
        foreach ($ppd as $ppdName => $ppdId) {
            $district = User::create([
                'name' => "{$ppdName} User",
                'phone_num' => '03000000000',
                'email' => "PPD{$ppdId}@example.com",
                'password' => bcrypt('password'),
            ]);

            $district->assignRole('ppd'); // Assign role
            $district->setMeta('user_district_id', $ppdId); // Update the user_district_id meta field
        }

        $schools = School::inRandomOrder()->take(10)->pluck('id', 'name'); //Testing schools User
        foreach ($schools as $schoolName => $schoolId) {
            $school = User::create([
                'name' => "{$schoolName} User",
                'phone_num' => '03000000000',
                'email' => "school{$schoolId}@example.com",
                'password' => bcrypt('password'),
            ]);

            $school->assignRole('school'); // Assign role
            $school->setMeta('user_school_id', $schoolId); // Update the user_district_id meta field
        }
    }
}
