<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'student_id',
        'room_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit_amount',
        'total_amount',
        'payment_cycle',
        'status',
        'notes',
        'signed_at',
        'signed_by',
            'signed_by_student',
            'signed_by_manager',
            'student_signed_at',
            'manager_signed_at',
        'terminated_at',
        'termination_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
            'student_signed_at' => 'datetime',
            'manager_signed_at' => 'datetime',
        'terminated_at' => 'datetime',
        'monthly_rent' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'signed_by_student' => 'boolean',
        'signed_by_manager' => 'boolean',
    ];

    /**
     * Get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who signed the contract
     */
    public function signedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    /**
     * Get all invoices for this contract
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all payments through invoices
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    /**
     * Check if contract is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    /**
     * Check if contract is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date < now();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xác nhận',
            'active' => 'Đang hoạt động',
            'expired' => 'Đã hết hạn',
            'terminated' => 'Đã chấm dứt',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Get payment cycle label
     */
    public function getPaymentCycleLabelAttribute(): string
    {
        return match($this->payment_cycle) {
            'monthly' => 'Hàng tháng',
            'semester' => 'Theo học kỳ',
            'yearly' => 'Hàng năm',
            default => $this->payment_cycle,
        };
    }
}
