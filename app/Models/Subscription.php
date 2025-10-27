<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_type',
        'price',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scope for active subscriptions
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '>=', now());
    }

    // Scope for expired subscriptions
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere('end_date', '<', now());
    }
}
