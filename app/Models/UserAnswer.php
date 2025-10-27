<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'quiz_question_id',
        'quiz_answer_id',
        'answer_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Relationships
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class);
    }
}
