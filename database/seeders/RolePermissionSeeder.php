<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Course permissions
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',
            
            // Module permissions
            'view modules',
            'create modules',
            'edit modules',
            'delete modules',
            
            // Lesson permissions
            'view lessons',
            'create lessons',
            'edit lessons',
            'delete lessons',
            
            // Quiz permissions
            'view quizzes',
            'create quizzes',
            'edit quizzes',
            'delete quizzes',
            'take quizzes',
            
            // Enrollment permissions
            'view enrollments',
            'create enrollments',
            'manage enrollments',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Dashboard permissions
            'view dashboard',
            'view instructor dashboard',
            'view admin dashboard',
            
            // Subscription permissions
            'view subscriptions',
            'manage subscriptions',
            
            // Transaction permissions
            'view transactions',
            'manage transactions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $instructorRole = Role::create(['name' => 'instructor']);
        $instructorRole->givePermissionTo([
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',
            'view modules',
            'create modules',
            'edit modules',
            'delete modules',
            'view lessons',
            'create lessons',
            'edit lessons',
            'delete lessons',
            'view quizzes',
            'create quizzes',
            'edit quizzes',
            'delete quizzes',
            'view enrollments',
            'view dashboard',
            'view instructor dashboard',
        ]);

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view courses',
            'view modules',
            'view lessons',
            'view quizzes',
            'take quizzes',
            'create enrollments',
            'view enrollments',
            'view dashboard',
        ]);
    }
}
