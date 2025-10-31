<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'level',
        'price',
        'is_published',
        'duration_hours',
        'instructor_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
        
        static::updating(function ($course) {
            // Update slug if title changed
            if ($course->isDirty('title') && empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // Relationships
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress_percentage'])
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    // Accessor for lessons count
    public function getLessonsCountAttribute()
    {
        return $this->modules->sum(function ($module) {
            return $module->lessons->count();
        });
    }

    // Scope for published courses
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope for level
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }
    
    // Helper method to get URL with slug
    public function getUrlAttribute()
    {
        return route('courses.show', ['course' => $this->id, 'slug' => $this->slug]);
    }
    
    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'level' => $this->level,
            'price' => $this->price,
            'is_published' => $this->is_published,
            'duration_hours' => $this->duration_hours,
        ];
    }
    
    /**
     * Get the value used to index the model.
     */
    public function getScoutKey()
    {
        return $this->id;
    }
    
    /**
     * Get the name of the key used to index the model.
     */
    public function getScoutKeyName()
    {
        return 'id';
    }
}
