<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role',
        'pseudo',
        'email',
        'password',
        'birth_date',
        'status',
        'charter_accepted_at',
        'anonymous_by_default',
        'google_id',
        'avatar',
        'auth_provider',
        'notification_preferences',
        'email_notifications',
        'unsubscribe_token',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'charter_accepted_at' => 'datetime',
        'anonymous_by_default' => 'boolean',
        'notification_preferences' => 'json',
        'email_notifications' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relations
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function moderatedPublications(): HasMany
    {
        return $this->hasMany(Publication::class, 'moderated_by');
    }

    public function moderatedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'moderated_by');
    }

    public function reviewedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    // Accessors & Mutators
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->pseudo;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        // Générer un avatar par défaut basé sur les initiales
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->pseudo) . '&background=ef4444&color=ffffff&size=128';
    }

    public function canLogin(): bool
    {
        return $this->isActive() && !$this->isBanned();
    }

    public function hasAcceptedCharter(): bool
    {
        return !is_null($this->charter_accepted_at);
    }

    public function isGoogleUser(): bool
    {
        return $this->auth_provider === 'google';
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    public function wantsEmailNotifications(): bool
    {
        return $this->email_notifications && $this->hasVerifiedEmail();
    }

    public function generateUnsubscribeToken(): string
    {
        if (!$this->unsubscribe_token) {
            $this->unsubscribe_token = \Str::random(60);
            $this->save();
        }
        return $this->unsubscribe_token;
    }
}
