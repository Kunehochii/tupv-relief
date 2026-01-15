<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drive_id',
        'reference_number',
        'items',
        'quantity',
        'details',
        'contact_number',
        'notes',
        'status',
        'verified_at',
        'verified_by',
        'expired_at',
        'distributed_at',
        'families_helped',
        'relief_packages',
        'items_distributed',
        'admin_feedback',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'verified_at' => 'datetime',
            'expired_at' => 'datetime',
            'distributed_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_EXPIRED = 'expired';
    const STATUS_DISTRIBUTED = 'distributed';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pledge) {
            if (empty($pledge->reference_number)) {
                $pledge->reference_number = self::generateReferenceNumber();
            }
        });
    }

    public static function generateReferenceNumber(): string
    {
        do {
            $reference = 'REL-' . strtoupper(Str::random(8));
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drive()
    {
        return $this->belongsTo(Drive::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isDistributed(): bool
    {
        return $this->status === self::STATUS_DISTRIBUTED;
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }
}
