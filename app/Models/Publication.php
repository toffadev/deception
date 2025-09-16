<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Publication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'custom_author_name',
        'title',
        'slug',
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

        // Vérifier si la relation user est chargée
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->pseudo;
        }

        // Si la relation n'est pas chargée, la charger
        if ($this->user_id) {
            try {
                $user = $this->user;
                return $user ? $user->pseudo : 'Auteur inconnu';
            } catch (\Exception $e) {
                return 'Auteur inconnu';
            }
        }

        return 'Auteur inconnu';
    }

    public function getExcerptAttribute(): string
    {
        $content = $this->content ?? '';
        // Nettoyer les caractères UTF-8 malformés
        $cleanContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        return substr(strip_tags($cleanContent), 0, 200) . '...';
    }

    public function setContentAttribute($value)
    {
        // Nettoyage automatique à chaque sauvegarde
        $cleaned = $this->sanitizeContent($value);
        $this->attributes['content'] = $cleaned;

        // Log pour monitoring si nécessaire
        if ($value !== $cleaned) {
            Log::warning('Content was sanitized', [
                'publication_id' => $this->id ?? 'new',
                'original_length' => strlen($value),
                'cleaned_length' => strlen($cleaned)
            ]);
        }
    }

    private function sanitizeContent($content)
    {
        // Multi-layer cleaning
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);
        $content = filter_var($content, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        return iconv('UTF-8', 'UTF-8//IGNORE', $content);
    }

    public function generateSlug(): string
    {
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $counter = 1;

        // Vérifier si le slug existe déjà
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($publication) {
            if (empty($publication->slug)) {
                $publication->slug = $publication->generateSlug();
            }
        });

        static::updating(function ($publication) {
            if ($publication->isDirty('title') && empty($publication->slug)) {
                $publication->slug = $publication->generateSlug();
            }
        });
    }
}
