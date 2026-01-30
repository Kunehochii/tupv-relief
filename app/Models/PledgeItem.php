<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PledgeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pledge_id',
        'drive_item_id',
        'item_name',
        'quantity',
        'unit',
        'quantity_distributed',
        'families_helped',
        'distributed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'quantity_distributed' => 'decimal:2',
            'distributed_at' => 'datetime',
        ];
    }

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function driveItem()
    {
        return $this->belongsTo(DriveItem::class);
    }

    /**
     * Get remaining quantity to distribute
     */
    public function getRemainingToDistributeAttribute(): float
    {
        return max(0, $this->quantity - $this->quantity_distributed);
    }

    /**
     * Check if fully distributed
     */
    public function isFullyDistributed(): bool
    {
        return $this->quantity_distributed >= $this->quantity;
    }

    /**
     * Calculate families helped using mother formula
     */
    public function calculateFamiliesHelped(): int
    {
        // Find the corresponding relief pack item for the formula
        $reliefItem = ReliefPackItem::where('item_name', $this->item_name)
            ->where('unit', $this->unit)
            ->first();

        if ($reliefItem) {
            return $reliefItem->calculateFamiliesHelped($this->quantity_distributed);
        }

        // Default to 0 if no matching formula found
        return 0;
    }
}
