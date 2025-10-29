<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('course_id');
            $table->index('slug');
        });
        
        // Generate slugs for existing enrollments
        $enrollments = \App\Models\Enrollment::whereNull('slug')->get();
        foreach ($enrollments as $enrollment) {
            $enrollment->slug = \Illuminate\Support\Str::slug($enrollment->course->title . '-' . $enrollment->user->name);
            $enrollment->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
