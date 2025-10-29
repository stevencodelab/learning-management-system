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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->integer('attempt_number')->default(1)->after('quiz_id');
            $table->integer('total_points')->default(0)->after('total_questions');
            $table->integer('correct_answers')->default(0)->after('score');
            $table->integer('incorrect_answers')->default(0)->after('correct_answers');
            $table->integer('unanswered_questions')->default(0)->after('incorrect_answers');
            $table->json('time_spent')->nullable()->after('completed_at');
            $table->json('answers_review')->nullable()->after('time_spent');
            $table->boolean('submitted')->default(false)->after('time_spent');
            $table->boolean('is_passed')->default(false)->after('submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn([
                'attempt_number',
                'total_points',
                'correct_answers',
                'incorrect_answers',
                'unanswered_questions',
                'time_spent',
                'answers_review',
                'submitted',
                'is_passed'
            ]);
        });
    }
};

