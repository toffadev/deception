<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'custom_author_name',
        'title',
        'content',
        'type',
        'is_anonymous',
        'status',
        'views_count',
        'comments_count',
        'reactions_count',
        'donations_amount',
        'auto_tags',
        'moderation_reason',
        'moderated_at',
        'moderated_by',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'reactions_count' => 'integer',
        'donations_amount' => 'decimal:2',
        'auto_tags' => 'json',
        'moderated_at' => 'datetime',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'publication_tags')->withTimestamps();
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonyme';
        }

        if (!empty($this->custom_author_name)) {
            return $this->custom_author_name;
        }

        return $this->user ? $this->user->pseudo : 'Auteur inconnu';
    }
}
