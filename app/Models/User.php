<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'phone',
        'organization_name',
        'certificate_path',
        'external_donation_url',
        'verification_status',
        'rejection_reason',
        'verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_DONOR = 'donor';
    const ROLE_NGO = 'ngo';

    // Verification status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDonor(): bool
    {
        return $this->role === self::ROLE_DONOR;
    }

    public function isNgo(): bool
    {
        return $this->role === self::ROLE_NGO;
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::STATUS_VERIFIED;
    }

    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function linkClicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    public function createdDrives()
    {
        return $this->hasMany(Drive::class, 'created_by');
    }

    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->organization_name ?? $this->name;
    }
}
