<?php

namespace Database\Seeders;

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
        // Define permissions
        $permissions = [
            'view-admin-page',
            'view-country-page',
            'view-state-page',
            'view-ppd-page',
            'view-school-page',
            'view-student-attendance-page',
            'view-parent-page',
            'view-teacher-page',
            'view-school-admin-page',
        ];

        // Create permissions if not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles with their permissions
        $roles = [
            'admin' => [
                'view-admin-page',
                'view-country-page',
                'view-state-page',
                'view-ppd-page',
                'view-school-page',
                'view-student-attendance-page',
            ],
            'country' => [
                'view-country-page',
                'view-state-page',
                'view-ppd-page',
                'view-school-page',
                'view-student-attendance-page',
            ],
            'state' => [
                'view-state-page',
                'view-ppd-page',
                'view-school-page',
                'view-student-attendance-page',
            ],
            'ppd' => [
                'view-ppd-page',
                'view-school-page',
                'view-student-attendance-page',
            ],
            'school' => [
                'view-school-page',
                'view-student-attendance-page',
            ],
            'school_admin' => [
                'view-school-admin-page',
                'view-school-page',
                'view-student-attendance-page',
            ],
            'teacher' => [
                'view-teacher-page',
                'view-student-attendance-page',
            ],
            'parent' => [
                'view-parent-page',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
