<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'unit',
        'icon',
        'is_active',
        'requires_approval',
        'max_registrations',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_approval' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get registrations for this service
     */
    public function registrations()
    {
        return $this->hasMany(ServiceRegistration::class);
    }

    /**
     * Get active registrations
     */
    public function activeRegistrations()
    {
        return $this->hasMany(ServiceRegistration::class)
            ->where('status', 'active');
    }

    /**
     * Check if student has registered
     */
    public function isRegisteredBy($studentId)
    {
        return $this->registrations()
            ->where('student_id', $studentId)
            ->whereIn('status', ['pending', 'active'])
            ->exists();
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'monthly' => 'Theo tháng',
            'one_time' => 'Một lần',
            'per_use' => 'Theo lần sử dụng',
            default => 'Không xác định',
        };
    }

    /**
     * Check if service is available for registration
     */
    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_registrations !== null) {
            $currentCount = $this->activeRegistrations()->count();
            return $currentCount < $this->max_registrations;
        }

        return true;
    }
}
