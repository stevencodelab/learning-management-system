<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run role and permission seeder first
        $this->call([
            RolePermissionSeeder::class,
            SampleDataSeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'level' => 'advanced',
        ]);
        $admin->assignRole('admin');

        // Create instructor user
        $instructor = User::factory()->create([
            'name' => 'Instructor User',
            'email' => 'instructor@lms.com',
            'level' => 'advanced',
        ]);
        $instructor->assignRole('instructor');
        
        // Create student user
        $student = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@lms.com',
            'level' => 'beginner',
        ]);
        $student->assignRole('student');

        // Create additional test users
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('student');
        });
    }
}
