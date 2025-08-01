<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'period_type',
        'period_start',
        'period_end',
        'total_donations',
        'total_expenses',
        'administrative_costs',
        'breakdown',
        'report_file_path',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_donations' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'administrative_costs' => 'decimal:2',
        'breakdown' => 'json',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByPeriodType($query, $type)
    {
        return $query->where('period_type', $type);
    }

    // Methods
    public function getNetAmountAttribute(): float
    {
        return $this->total_donations - $this->total_expenses;
    }

    public function getEfficiencyRateAttribute(): float
    {
        if ($this->total_donations == 0) {
            return 0;
        }

        $directImpact = $this->total_donations - $this->administrative_costs;
        return ($directImpact / $this->total_donations) * 100;
    }

    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
