<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'price_per_month',
        'price_per_semester',
        'price_per_year',
        'description',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'price_per_month' => 'decimal:2',
        'price_per_semester' => 'decimal:2',
        'price_per_year' => 'decimal:2',
    ];

    /**
     * Get all rooms of this type
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_per_month, 0, ',', '.') . ' ₫/tháng';
    }
}
