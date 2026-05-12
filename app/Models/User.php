<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'github_id',
        'google_id',
        'github_username',
        'github_avatar_url',
        'google_avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'creator_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'assignee_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function usesPasswordCredential(): bool
    {
        $password = $this->attributes['password'] ?? null;

        return is_string($password) && $password !== '';
    }
}