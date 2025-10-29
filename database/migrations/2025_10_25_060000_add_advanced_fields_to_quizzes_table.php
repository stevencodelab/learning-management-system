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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('allow_multiple_attempts')->default(true);
            $table->integer('max_attempts')->nullable();
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_answers')->default(false);
            $table->boolean('show_correct_answers')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->integer('questions_per_page')->default(10);
            $table->boolean('allow_navigation')->default(true);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_mark_value', 5, 2)->default(0.25);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('random_question_count')->nullable();
            $table->boolean('require_all_questions')->default(false);
            $table->text('instructions')->nullable();
            $table->text('pass_message')->nullable();
            $table->text('fail_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'allow_multiple_attempts',
                'max_attempts',
                'shuffle_questions',
                'shuffle_answers',
                'show_correct_answers',
                'show_results_immediately',
                'questions_per_page',
                'allow_navigation',
                'negative_marking',
                'negative_mark_value',
                'status',
                'start_date',
                'end_date',
                'random_question_count',
                'require_all_questions',
                'instructions',
                'pass_message',
                'fail_message'
            ]);
        });
    }
};

