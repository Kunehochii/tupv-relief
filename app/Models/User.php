<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $organization_name
 * @property string|null $certificate_path
 * @property string|null $external_donation_url
 * @property string|null $verification_status
 * @property string|null $google_id
 * @property bool $otp_verified
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pledge[] $pledges
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LinkClick[] $linkClicks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Drive[] $createdDrives
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Drive[] $supportedDrives
 * 
 * @method \Illuminate\Database\Eloquent\Relations\HasMany pledges()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany notifications()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany linkClicks()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany createdDrives()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany supportedDrives()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany receivedReceipts()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany submittedReceipts()
 * @method bool isAdmin()
 * @method bool isDonor()
 * @method bool isNgo()
 * @method bool isVerified()
 * @method bool isPending()
 * @method bool isVerifiedNgo()
 */
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
        'bio',
        'contact_numbers',
        'qr_channels',
        'certificate_path',
        'external_donation_url',
        'logo_url',
        'logo_path',
        'verification_status',
        'rejection_reason',
        'verified_at',
        'otp_verified',
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
            'qr_channels' => 'array',
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

    public function isVerifiedNgo(): bool
    {
        return $this->isNgo() && $this->isVerified();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function linkClicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdDrives()
    {
        return $this->hasMany(Drive::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function supportedDrives()
    {
        return $this->belongsToMany(Drive::class, 'ngo_drive_supports')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function driveSupports()
    {
        return $this->hasMany(NgoDriveSupport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function otpVerifications()
    {
        return $this->hasMany(OtpVerification::class);
    }

    /**
     * Donation receipts submitted TO this NGO.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedReceipts()
    {
        return $this->hasMany(DonationReceipt::class, 'ngo_id');
    }

    /**
     * Donation receipts submitted BY this user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submittedReceipts()
    {
        return $this->hasMany(DonationReceipt::class, 'user_id');
    }

    /**
     * Check if user has completed OTP verification.
     */
    public function isOtpVerified(): bool
    {
        return (bool) $this->otp_verified;
    }

    /**
     * Get the logo URL, preferring uploaded path over external URL.
     */
    public function getLogoAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return $this->logo_url;
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
