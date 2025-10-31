<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress_percentage'])
                    ->withTimestamps();
    }

    // Avatar methods
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Generate avatar with initials if no avatar uploaded
        return $this->generateAvatarUrl();
    }

    public function generateAvatarUrl()
    {
        $name = $this->name;
        $initials = '';
        
        // Get initials from name
        $words = explode(' ', $name);
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        // Limit to 2 characters
        $initials = substr($initials, 0, 2);
        
        // Generate a color based on name hash
        $colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c',
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
            '#fa709a', '#fee140', '#a8edea', '#fed6e3',
            '#d299c2', '#fef9d7', '#667eea', '#764ba2'
        ];
        
        $colorIndex = crc32($name) % count($colors);
        $backgroundColor = $colors[$colorIndex];
        
        // Create SVG avatar
        $svg = '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="100" fill="' . $backgroundColor . '"/>
            <text x="50" y="50" font-family="Arial, sans-serif" font-size="36" font-weight="bold" 
                  text-anchor="middle" dominant-baseline="central" fill="white">' . $initials . '</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function getAvatarPathAttribute()
    {
        return $this->avatar ? storage_path('app/public/' . $this->avatar) : null;
    }

    // Student-specific methods
    public function scopeStudents($query)
    {
        return $query->role('student');
    }

    public function scopeEnrolled($query)
    {
        return $query->whereHas('enrollments');
    }

    public function scopeNotEnrolled($query)
    {
        return $query->whereDoesntHave('enrollments');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    public function getEnrollmentStatsAttribute()
    {
        return [
            'total_enrollments' => $this->enrollments->count(),
            'completed_courses' => $this->enrollments->where('completed_at', '!=', null)->count(),
            'in_progress_courses' => $this->enrollments->where('completed_at', null)->where('progress_percentage', '>', 0)->count(),
            'not_started_courses' => $this->enrollments->where('progress_percentage', 0)->count(),
        ];
    }

    public function getLearningStatsAttribute()
    {
        return [
            'total_lessons_completed' => $this->lessonProgress->where('completed_at', '!=', null)->count(),
            'total_quiz_attempts' => $this->quizAttempts->count(),
            'average_quiz_score' => $this->quizAttempts->avg('score') ?? 0,
            'total_study_time' => $this->lessonProgress->sum('duration_minutes') ?? 0,
        ];
    }

    // Instructor-specific relationships
    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    // Student-specific relationships
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}
