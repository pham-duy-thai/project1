<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'room_type_id',
        'room_number',
        'floor',
        'capacity',
        'current_occupancy',
        'area',
        'status',
        'description',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'area' => 'decimal:2',
    ];

    /**
     * Get the building that owns this room
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the room type
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get all contracts for this room
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get active contracts
     */
    public function activeContracts(): HasMany
    {
        return $this->hasMany(Contract::class)->where('status', 'active');
    }

    /**
     * Get all invoices for this room
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get maintenance requests
     */
    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    /**
     * Get amenities
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities')
            ->withPivot('quantity', 'condition', 'notes')
            ->withTimestamps();
    }

    /**
     * Check if room is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' 
            && $this->is_active 
            && $this->current_occupancy < $this->capacity;
    }

    /**
     * Get available beds
     */
    public function getAvailableBedsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'Còn trống',
            'occupied' => 'Đã đầy',
            'maintenance' => 'Đang sửa chữa',
            'reserved' => 'Đã đặt',
            default => 'Không xác định',
        };
    }
}
