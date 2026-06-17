<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isRegular(): bool
    {
        return $this->role === 'regular';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canReviewTickets(): bool
    {
        return in_array($this->role, ['reviewer', 'admin'], true);
    }
}
