<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NgoDriveSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drive_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drive()
    {
        return $this->belongsTo(Drive::class);
    }

    /**
     * Toggle support status
     */
    public function toggle(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}
