<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'slug',
        'enrolled_at',
        'completed_at',
        'progress_percentage',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($enrollment) {
            if (empty($enrollment->slug)) {
                $enrollment->slug = Str::slug($enrollment->course->title . '-' . $enrollment->user->name);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scope for active enrollments
    public function scopeActive($query)
    {
        return $query->whereNull('completed_at');
    }

    // Scope for completed enrollments
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }
    
    // Helper method to get URL with slug
    public function getUrlAttribute()
    {
        return route('enrollments.show', ['enrollment' => $this->id, 'slug' => $this->slug]);
    }
}
