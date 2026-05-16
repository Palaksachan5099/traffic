<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'email_verified_at', 'completion', 'admin_notes',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (empty($user->role)) {
                $user->role = 'user';
            }
        });
    }

    public function getConnectionName()
    {
        return env('MONGO_USER_CONNECTION', $this->connection);
    }

    public function dashboardRoute(): string
    {
        $role = strtolower(trim((string) ($this->role ?? 'user')));

        if ($role === 'admin') {
            return 'admin.dashboard';
        }

        if ($role === 'officer') {
            return 'assignments.index';
        }

        return 'dashboard';
    }

    public function isAdmin(): bool
    {
        return ($this->role ?? 'user') === 'admin';
    }

    public function accidents(): HasMany
    {
        return $this->hasMany(Accident::class);
    }

    public function congestionReports(): HasMany
    {
        return $this->hasMany(Congestion::class);
    }
}