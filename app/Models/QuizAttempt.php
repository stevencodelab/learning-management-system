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
        'score',
        'total_questions',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->score / $this->total_questions) * 100, 2);
    }

    // Scope for completed attempts
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }
}
