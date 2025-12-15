<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'dormitory_id',
        'name',
        'code',
        'floors',
        'total_rooms',
        'gender',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the dormitory that owns this building
     */
    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(Dormitory::class);
    }

    /**
     * Get all rooms in this building
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get active rooms
     */
    public function activeRooms(): HasMany
    {
        return $this->hasMany(Room::class)->where('is_active', true);
    }

    /**
     * Get available rooms
     */
    public function availableRooms(): HasMany
    {
        return $this->hasMany(Room::class)
            ->where('is_active', true)
            ->where('status', 'available')
            ->whereColumn('current_occupancy', '<', 'capacity');
    }

    /**
     * Update statistics
     */
    public function updateStatistics(): void
    {
        $this->total_rooms = $this->rooms()->count();
        $this->save();
        
        // Update dormitory statistics
        $this->dormitory->updateStatistics();
    }
}
