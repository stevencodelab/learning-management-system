<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'question_text',
        'type',
        'points',
        'partial_points',
        'order',
        'explanation',
        'difficulty',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'points' => 'integer',
        'partial_points' => 'decimal:2',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Additional scopes
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    // Helper methods
    public function getAnswersForAttempt()
    {
        $answers = $this->answers;
        
        // Add this to Quiz model's shuffle_answers check
        // This will be handled in the controller
        
        return $answers;
    }

    public function calculateScore($userAnswer)
    {
        // This depends on question type
        // Will be implemented in the attempt controller
        return 0;
    }
}
