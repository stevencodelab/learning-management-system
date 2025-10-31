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
        // Add indexes for frequently queried columns
        // Check if index exists before creating to avoid duplicates
        
        // Courses table indexes
        Schema::table('courses', function (Blueprint $table) {
            // Composite index for published courses listing
            if (!$this->indexExists('courses', 'courses_is_published_created_at_index')) {
                $table->index(['is_published', 'created_at'], 'courses_is_published_created_at_index');
            }
            
            // Index for instructor queries
            if (!$this->indexExists('courses', 'courses_instructor_id_index')) {
                $table->index('instructor_id', 'courses_instructor_id_index');
            }
            
            // Composite index for search and filtering
            if (!$this->indexExists('courses', 'courses_level_is_published_index')) {
                $table->index(['level', 'is_published'], 'courses_level_is_published_index');
            }
        });

        // Testimonials table indexes
        Schema::table('testimonials', function (Blueprint $table) {
            // Composite index for approved testimonials listing
            if (!$this->indexExists('testimonials', 'testimonials_is_approved_created_at_index')) {
                $table->index(['is_approved', 'created_at'], 'testimonials_is_approved_created_at_index');
            }
            
            // Composite index for featured testimonials
            if (!$this->indexExists('testimonials', 'testimonials_is_approved_is_featured_index')) {
                $table->index(['is_approved', 'is_featured'], 'testimonials_is_approved_is_featured_index');
            }
            
            // Index for course filtering
            if (!$this->indexExists('testimonials', 'testimonials_course_id_index')) {
                $table->index('course_id', 'testimonials_course_id_index');
            }
        });

        // Enrollments table indexes
        Schema::table('enrollments', function (Blueprint $table) {
            // Composite index for user enrollments
            if (!$this->indexExists('enrollments', 'enrollments_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at'], 'enrollments_user_id_created_at_index');
            }
            
            // Index for course enrollments
            if (!$this->indexExists('enrollments', 'enrollments_course_id_index')) {
                $table->index('course_id', 'enrollments_course_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_is_published_created_at_index');
            $table->dropIndex(['instructor_id']);
            $table->dropIndex('courses_level_is_published_index');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('testimonials_is_approved_created_at_index');
            $table->dropIndex('testimonials_is_approved_is_featured_index');
            $table->dropIndex(['course_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_user_id_created_at_index');
            $table->dropIndex(['course_id']);
        });
    }

    /**
     * Check if index exists using raw query
     */
    private function indexExists($table, $indexName): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};

