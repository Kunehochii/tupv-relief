<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrivePhoto extends Model
{
    protected $fillable = [
        'drive_id',
        'path',
        'sort_order',
    ];

    public function drive(): BelongsTo
    {
        return $this->belongsTo(Drive::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
