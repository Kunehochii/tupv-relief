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
        'cover_photo',
        'target_amount',
        'collected_amount',
        'pledged_amount',
        'distributed_amount',
        'target_type',
        'items_needed',
        'pack_types_needed',
        'families_affected',
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
            'pack_types_needed' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'collected_amount' => 'decimal:2',
            'pledged_amount' => 'decimal:2',
            'distributed_amount' => 'decimal:2',
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

    public function driveItems()
    {
        return $this->hasMany(DriveItem::class);
    }

    public function ngoSupports()
    {
        return $this->hasMany(NgoDriveSupport::class);
    }

    public function supportingNgos()
    {
        return $this->belongsToMany(User::class, 'ngo_drive_supports')
            ->wherePivot('is_active', true)
            ->withTimestamps();
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

    /**
     * Get pledged percentage for 3-color progress bar
     */
    public function getPledgedPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->pledged_amount / $this->target_amount) * 100, 2));
    }

    /**
     * Get distributed percentage for 3-color progress bar
     */
    public function getDistributedPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->distributed_amount / $this->target_amount) * 100, 2));
    }

    /**
     * Get cover photo URL
     */
    public function getCoverPhotoUrlAttribute(): ?string
    {
        if (!$this->cover_photo) return null;
        return asset('storage/' . $this->cover_photo);
    }

    /**
     * Generate drive items from families affected using mother formula
     */
    public function generateItemsFromFamilies(): void
    {
        if (!$this->families_affected || empty($this->pack_types_needed)) {
            return;
        }

        // Get relief pack items for selected pack types
        $reliefItems = ReliefPackItem::whereIn('pack_type', $this->pack_types_needed)->get();

        foreach ($reliefItems as $item) {
            $this->driveItems()->updateOrCreate(
                ['item_name' => $item->item_name],
                [
                    'quantity_needed' => $item->calculateForFamilies($this->families_affected),
                    'unit' => $item->unit,
                    'pack_type' => $item->pack_type,
                    'is_custom' => false,
                ]
            );
        }
    }

    /**
     * Recalculate pledged and distributed amounts from related items
     */
    public function recalculateProgress(): void
    {
        // Sum from drive items
        $this->pledged_amount = $this->driveItems()->sum('quantity_pledged');
        $this->distributed_amount = $this->driveItems()->sum('quantity_distributed');
        $this->save();
    }
}
