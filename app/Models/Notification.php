<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'emailed_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
            'emailed_at' => 'datetime',
        ];
    }

    // Notification type constants
    const TYPE_PLEDGE_ACKNOWLEDGED = 'pledge_acknowledged';
    const TYPE_PLEDGE_VERIFIED = 'pledge_verified';
    const TYPE_PLEDGE_EXPIRED = 'pledge_expired';
    const TYPE_PLEDGE_EXPIRY_WARNING = 'pledge_expiry_warning';
    const TYPE_DONATION_DISTRIBUTED = 'donation_distributed';
    const TYPE_ITEM_DISTRIBUTED = 'item_distributed';
    const TYPE_IMPACT_FEEDBACK = 'impact_feedback';
    const TYPE_NEW_DRIVE = 'new_drive';
    const TYPE_DRIVE_ENDING_SOON = 'drive_ending_soon';
    const TYPE_NGO_VERIFIED = 'ngo_verified';
    const TYPE_NGO_REJECTED = 'ngo_rejected';
    const TYPE_NGO_PLEDGE_ADDED = 'ngo_pledge_added';

    // Color mapping for notification types
    public static function getColor(string $type): string
    {
        return match ($type) {
            self::TYPE_PLEDGE_VERIFIED => 'success',
            self::TYPE_PLEDGE_EXPIRED => 'danger',
            self::TYPE_PLEDGE_EXPIRY_WARNING => 'warning',
            self::TYPE_NEW_DRIVE => 'info',
            self::TYPE_DONATION_DISTRIBUTED => 'purple',
            self::TYPE_ITEM_DISTRIBUTED => 'success',
            self::TYPE_NGO_VERIFIED => 'success',
            self::TYPE_NGO_REJECTED => 'danger',
            self::TYPE_NGO_PLEDGE_ADDED => 'info',
            default => 'secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
