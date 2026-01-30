<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefPackItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pack_type',
        'item_name',
        'quantity_per_family',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'quantity_per_family' => 'decimal:2',
        ];
    }

    // Pack type constants
    const PACK_FOOD = 'food';
    const PACK_KITCHEN = 'kitchen';
    const PACK_HYGIENE = 'hygiene';
    const PACK_SLEEPING = 'sleeping';
    const PACK_CLOTHING = 'clothing';

    const PACK_TYPES = [
        self::PACK_FOOD => 'Food Pack',
        self::PACK_KITCHEN => 'Kitchen Pack',
        self::PACK_HYGIENE => 'Hygiene Pack',
        self::PACK_SLEEPING => 'Sleeping Pack',
        self::PACK_CLOTHING => 'Clothing Pack',
    ];

    /**
     * Get items by pack type
     */
    public static function getByPackType(string $packType)
    {
        return static::where('pack_type', $packType)->get();
    }

    /**
     * Get all items grouped by pack type
     */
    public static function getAllGroupedByType()
    {
        return static::all()->groupBy('pack_type');
    }

    /**
     * Calculate quantity needed for given number of families
     */
    public function calculateForFamilies(int $families): float
    {
        return $this->quantity_per_family * $families;
    }

    /**
     * Calculate families helped based on quantity donated
     */
    public function calculateFamiliesHelped(float $quantity): int
    {
        if ($this->quantity_per_family <= 0) {
            return 0;
        }
        return (int) floor($quantity / $this->quantity_per_family);
    }
}
