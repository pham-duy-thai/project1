<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'icon',
        'is_common',
    ];

    protected $casts = [
        'is_common' => 'boolean',
    ];

    /**
     * Get all rooms that have this amenity
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenities')
            ->withPivot('quantity', 'condition', 'notes')
            ->withTimestamps();
    }
}
