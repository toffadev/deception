<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'tag_id',
    ];

    // Relations
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
