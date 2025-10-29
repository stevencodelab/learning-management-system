<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_number',
        'score',
        'total_points',
        'total_questions',
        'correct_answers',
        'incorrect_answers',
        'unanswered_questions',
        'is_passed',
        'submitted',
        'started_at',
        'completed_at',
        'time_spent',
        'answers_review',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent' => 'array',
        'answers_review' => 'array',
        'submitted' => 'boolean',
        'is_passed' => 'boolean',
        'total_points' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'incorrect_answers' => 'integer',
        'unanswered_questions' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    // Accessor for percentage score
    public function getScorePercentageAttribute()
    {
        if ($this->total_points == 0) {
            return 0;
        }
        return round(($this->score / $this->total_points) * 100, 2);
    }

    // Scope for completed attempts
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function markAsPassed()
    {
        $quiz = $this->quiz;
        $percentage = $this->score_percentage;
        
        if ($percentage >= $quiz->passing_score) {
            $this->is_passed = true;
            return true;
        }
        
        return false;
    }

    public function calculateCompletionPercentage()
    {
        $answered = $this->correct_answers + $this->incorrect_answers;
        return $this->total_questions > 0 ? ($answered / $this->total_questions) * 100 : 0;
    }

    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }
        return 0;
    }

    public function getFormattedDurationAttribute()
    {
        $seconds = $this->duration;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%d:%02d', $minutes, $secs);
    }

    public function isInProgress()
    {
        return !is_null($this->started_at) && is_null($this->completed_at);
    }
}
