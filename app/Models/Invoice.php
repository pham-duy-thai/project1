<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'contract_id',
        'student_id',
        'room_id',
        'invoice_type',
        'title',
        'description',
        'billing_period_start',
        'billing_period_end',
        'issue_date',
        'due_date',
        'amount',
        'electricity_cost',
        'water_cost',
        'service_cost',
        'other_cost',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'electricity_cost' => 'decimal:2',
        'water_cost' => 'decimal:2',
        'service_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    /**
     * Get the contract
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

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
     * Get all payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'partial' => 'Thanh toán một phần',
            'overdue' => 'Quá hạn',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Get invoice type label
     */
    public function getInvoiceTypeLabelAttribute(): string
    {
        return match($this->invoice_type) {
            'rent' => 'Tiền thuê phòng',
            'electricity' => 'Tiền điện',
            'water' => 'Tiền nước',
            'service' => 'Phí dịch vụ',
            'other' => 'Chi phí khác',
            default => 'Không xác định',
        };
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }

    /**
     * Get month from billing period
     */
    public function getMonthAttribute(): int
    {
        return $this->billing_period_start?->month ?? now()->month;
    }

    /**
     * Get year from billing period
     */
    public function getYearAttribute(): int
    {
        return $this->billing_period_start?->year ?? now()->year;
    }

    /**
     * Get room price (for compatibility with views)
     */
    public function getRoomPriceAttribute(): float
    {
        return $this->amount;
    }
}
