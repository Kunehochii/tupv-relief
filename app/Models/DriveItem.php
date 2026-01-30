<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriveItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'drive_id',
        'item_name',
        'quantity_needed',
        'quantity_pledged',
        'quantity_distributed',
        'unit',
        'pack_type',
        'is_custom',
    ];

    protected function casts(): array
    {
        return [
            'quantity_needed' => 'decimal:2',
            'quantity_pledged' => 'decimal:2',
            'quantity_distributed' => 'decimal:2',
            'is_custom' => 'boolean',
        ];
    }

    public function drive()
    {
        return $this->belongsTo(Drive::class);
    }

    public function pledgeItems()
    {
        return $this->hasMany(PledgeItem::class);
    }

    /**
     * Get remaining quantity needed
     */
    public function getRemainingNeededAttribute(): float
    {
        return max(0, $this->quantity_needed - $this->quantity_pledged);
    }

    /**
     * Get pledged percentage for this item
     */
    public function getPledgedPercentageAttribute(): float
    {
        if ($this->quantity_needed <= 0) return 0;
        return min(100, round(($this->quantity_pledged / $this->quantity_needed) * 100, 2));
    }

    /**
     * Get distributed percentage for this item
     */
    public function getDistributedPercentageAttribute(): float
    {
        if ($this->quantity_needed <= 0) return 0;
        return min(100, round(($this->quantity_distributed / $this->quantity_needed) * 100, 2));
    }

    /**
     * Check if item is fully pledged
     */
    public function isFullyPledged(): bool
    {
        return $this->quantity_pledged >= $this->quantity_needed;
    }

    /**
     * Check if item is fully distributed
     */
    public function isFullyDistributed(): bool
    {
        return $this->quantity_distributed >= $this->quantity_pledged;
    }
}
