<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view-admin-page']);
        Permission::create(['name' => 'view-country-page']);
        Permission::create(['name' => 'view-state-page']);
        Permission::create(['name' => 'view-ppd-page']);
        Permission::create(['name' => 'view-school-page']);
        Permission::create(['name' => 'view-student-attendance-page']);
        Permission::create(['name' => 'view-parent-page']);

        // Admin role
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view-admin-page',
            'view-country-page',
            'view-state-page',
            'view-ppd-page',
            'view-school-page',
            'view-student-attendance-page',
        ]);

        // Country role
        $country = Role::create(['name' => 'country']);
        $country->givePermissionTo([
            'view-country-page',
            'view-state-page',
            'view-ppd-page',
            'view-school-page',
            'view-student-attendance-page',
        ]);

        // State role
        $state = Role::create(['name' => 'state']);
        $state->givePermissionTo([
            'view-state-page',
            'view-ppd-page',
            'view-school-page',
            'view-student-attendance-page',
        ]);

        // PPD role
        $ppd = Role::create(['name' => 'ppd']);
        $ppd->givePermissionTo([
            'view-ppd-page',
            'view-school-page',
            'view-student-attendance-page',
        ]);

        // School role
        $school = Role::create(['name' => 'school']);
        $school->givePermissionTo([
            'view-school-page',
            'view-student-attendance-page',
        ]);

        // Parent role
        $parent = Role::create(['name' => 'parent']);
        $parent->givePermissionTo('view-parent-page');
    }
}
