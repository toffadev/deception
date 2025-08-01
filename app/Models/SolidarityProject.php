<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolidarityProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'current_amount',
        'currency',
        'status',
        'start_date',
        'end_date',
        'featured_image_path',
        'beneficiaries_info',
        'impact_description',
        'is_featured',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'beneficiaries_info' => 'json',
        'is_featured' => 'boolean',
    ];

    // Relations
    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'solidarity_project_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Methods
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }

        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function isCompleted(): bool
    {
        return $this->current_amount >= $this->target_amount;
    }

    public function updateCurrentAmount(): void
    {
        $this->current_amount = $this->donations()->where('status', 'completed')->sum('amount');
        $this->save();
    }
}
