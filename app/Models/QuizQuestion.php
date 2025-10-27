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
        'type',
        'points',
        'order',
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
}
