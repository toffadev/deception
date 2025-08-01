<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reactable_type',
        'reactable_id',
        'type',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    // Constants pour les types de réactions
    public const TYPES = [
        'heart' => '❤️',
        'cry' => '😢',
        'pray' => '🙏',
        'thank_you' => 'Merci pour ton partage',
        'understand' => 'Je comprends',
        'courage' => 'Courage à toi',
    ];

    public function getEmojiAttribute(): string
    {
        return self::TYPES[$this->type] ?? '';
    }
}
