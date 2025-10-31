<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'slug',
        'description',
        'passing_score',
        'time_limit_minutes',
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
        'fail_message',
    ];

    protected $casts = [
        'allow_multiple_attempts' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'show_correct_answers' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_navigation' => 'boolean',
        'negative_marking' => 'boolean',
        'require_all_questions' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'negative_mark_value' => 'decimal:2',
        'passing_score' => 'integer',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($quiz) {
            if (empty($quiz->slug)) {
                $quiz->slug = Str::slug($quiz->title);
            }
        });
        
        static::updating(function ($quiz) {
            // Update slug if title changed
            if ($quiz->isDirty('title') && empty($quiz->slug)) {
                $quiz->slug = Str::slug($quiz->title);
            }
        });
    }

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getCourseAttribute()
    {
        return $this->lesson->module->course ?? null;
    }
    
    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at');
    }

    // Additional scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('status', 'published')
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
    }

    // Helper methods
    public function canUserAttempt($userId)
    {
        if (!$this->allow_multiple_attempts) {
            return !$this->attempts()->where('user_id', $userId)->exists();
        }

        if ($this->max_attempts) {
            $attemptCount = $this->attempts()->where('user_id', $userId)->count();
            return $attemptCount < $this->max_attempts;
        }

        return true;
    }

    public function getUserAttemptNumber($userId)
    {
        return $this->attempts()->where('user_id', $userId)->count() + 1;
    }

    public function isAvailable()
    {
        if ($this->status !== 'published') {
            return false;
        }

        $now = now();
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }

        if ($this->end_date && $now > $this->end_date) {
            return false;
        }

        return true;
    }

    public function getQuestionsForAttempt()
    {
        $questions = $this->questions;

        // Random question selection
        if ($this->random_question_count && $this->random_question_count < $questions->count()) {
            $questions = $questions->random($this->random_question_count);
        }

        // Shuffle questions if enabled
        if ($this->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        return $questions;
    }

    public function getTotalPointsAttribute()
    {
        return $this->questions->sum('points');
    }

    public function getUserBestScore($userId)
    {
        $bestAttempt = $this->attempts()
            ->where('user_id', $userId)
            ->where('completed_at', '!=', null)
            ->orderBy('score', 'desc')
            ->first();

        return $bestAttempt ? $bestAttempt->score : 0;
    }

    public function getAverageScoreAttribute()
    {
        return $this->attempts()->whereNotNull('completed_at')->avg('score') ?? 0;
    }

    public function getCompletionRateAttribute()
    {
        $attempts = $this->attempts()->count();
        $completed = $this->attempts()->whereNotNull('completed_at')->count();
        
        return $attempts > 0 ? ($completed / $attempts) * 100 : 0;
    }

    public function getPassRateAttribute()
    {
        $completed = $this->attempts()->whereNotNull('completed_at')->get();
        $passed = $completed->filter(function ($attempt) {
            return ($attempt->score / $this->total_points) * 100 >= $this->passing_score;
        })->count();
        
        return $completed->count() > 0 ? ($passed / $completed->count()) * 100 : 0;
    }
    
    // Helper method to get URL with slug
    public function getUrlAttribute()
    {
        return route('quizzes.show', ['quiz' => $this->id, 'slug' => $this->slug]);
    }
}
