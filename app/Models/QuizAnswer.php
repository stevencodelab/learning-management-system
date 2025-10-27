<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'answer_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    // Scope for correct answers
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }
}
