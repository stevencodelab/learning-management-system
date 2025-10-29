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
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->text('explanation')->nullable()->after('order');
            $table->string('difficulty')->default('medium')->after('explanation');
            $table->json('tags')->nullable()->after('difficulty');
            $table->decimal('partial_points', 5, 2)->nullable()->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['explanation', 'difficulty', 'tags', 'partial_points']);
        });
    }
};

