<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dormitory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'description',
        'total_buildings',
        'total_rooms',
        'total_capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all buildings in this dormitory
     */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Get all rooms through buildings
     */
    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Building::class);
    }

    /**
     * Get active buildings
     */
    public function activeBuildings(): HasMany
    {
        return $this->hasMany(Building::class)->where('is_active', true);
    }

    /**
     * Update statistics
     */
    public function updateStatistics(): void
    {
        $this->total_buildings = $this->buildings()->count();
        $this->total_rooms = $this->buildings()->withCount('rooms')->get()->sum('rooms_count');
        $this->total_capacity = $this->buildings()
            ->with('rooms')
            ->get()
            ->flatMap->rooms
            ->sum('capacity');
        $this->save();
    }
}
