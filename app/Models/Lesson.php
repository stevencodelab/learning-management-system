<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'type',
        'content_url',
        'content_text',
        'duration_minutes',
        'order',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Scope for free lessons
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    // Scope for type
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
