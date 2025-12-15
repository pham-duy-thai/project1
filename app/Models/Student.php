<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_code',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'citizen_id',
        'permanent_address',
        'contact_address',
        'parent_name',
        'parent_phone',
        'faculty',
        'major',
        'class',
        'academic_year',
        'avatar',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all contracts
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get active contract
     */
    public function activeContract()
    {
        return $this->hasOne(Contract::class)->where('status', 'active');
    }

    /**
     * Get all invoices
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get maintenance requests
     */
    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    /**
     * Get violations
     */
    public function violations(): HasMany
    {
        return $this->hasMany(Violation::class);
    }

    /**
     * Get current room
     */
    public function getCurrentRoomAttribute()
    {
        return $this->activeContract?->room;
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        // Return default if no avatar
        if (!$this->avatar) {
            return asset('assets/images/user/user-1.jpg');
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // Handle paths starting with 'storage/' - already public path
        if (str_starts_with($this->avatar, 'storage/')) {
            $storagePath = str_replace('storage/', '', $this->avatar);
            if (Storage::disk('public')->exists($storagePath)) {
                return asset($this->avatar);
            }
            \Log::warning('Student avatar not found', ['student_id' => $this->id, 'path' => $this->avatar]);
            return asset('assets/images/user/user-1.jpg');
        }

        // Handle paths starting with 'uploads/' - public directory
        if (str_starts_with($this->avatar, 'uploads/')) {
            if (file_exists(public_path($this->avatar))) {
                return asset($this->avatar);
            }
            \Log::warning('Student avatar not found in public', ['student_id' => $this->id, 'path' => $this->avatar]);
            return asset('assets/images/user/user-1.jpg');
        }

        // Default: assume it's in storage/app/public (like 'avatars/xxx.jpg')
        if (Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        
        \Log::warning('Student avatar not found in storage', ['student_id' => $this->id, 'path' => $this->avatar]);
        return asset('assets/images/user/user-1.jpg');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Đang hoạt động',
            'inactive' => 'Không hoạt động',
            'graduated' => 'Đã tốt nghiệp',
            'suspended' => 'Đã bị đình chỉ',
            default => 'Không xác định',
        };
    }
}
