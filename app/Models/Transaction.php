<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'subscription_id',
        'transaction_code',
        'amount',
        'payment_method',
        'payment_status',
        'payment_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Scope for successful transactions
    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'success');
    }

    // Scope for pending transactions
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
