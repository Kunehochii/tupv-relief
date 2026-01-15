<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drive extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'target_amount',
        'collected_amount',
        'target_type',
        'items_needed',
        'start_date',
        'end_date',
        'status',
        'latitude',
        'longitude',
        'address',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'items_needed' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'collected_amount' => 'decimal:2',
        ];
    }

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';

    // Target type constants
    const TARGET_FINANCIAL = 'financial';
    const TARGET_QUANTITY = 'quantity';

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->end_date > now();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function getTotalPledgedAttribute()
    {
        return $this->pledges()->where('status', Pledge::STATUS_VERIFIED)->sum('quantity');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 2));
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->where('end_date', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
