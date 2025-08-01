<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'publication_id',
        'amount',
        'currency',
        'frequency',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'status',
        'is_anonymous',
        'anonymous_donor_info',
        'tax_receipt_requested',
        'tax_receipt_path',
        'message',
        'processed_at',
        'next_payment_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'anonymous_donor_info' => 'json',
        'tax_receipt_requested' => 'boolean',
        'processed_at' => 'datetime',
        'next_payment_at' => 'datetime',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePlatform($query)
    {
        return $query->where('type', 'platform');
    }

    public function scopeBlindSupport($query)
    {
        return $query->where('type', 'blind_support');
    }

    public function scopeRecurring($query)
    {
        return $query->where('frequency', 'monthly');
    }

    // Methods
    public function isRecurring(): bool
    {
        return $this->frequency === 'monthly';
    }

    public function getDonorNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return $this->anonymous_donor_info['name'] ?? 'Donateur anonyme';
        }

        return $this->user ? $this->user->pseudo : 'Donateur anonyme';
    }
}
