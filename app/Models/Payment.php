<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'invoice_id',
        'student_id',
        'amount',
        'payment_method',
        'payment_date',
        'transaction_id',
        'notes',
        'status',
        'received_by',
        'receipt_number',
        'payment_details',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'payment_details' => 'array',
    ];

    /**
     * Get the invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who received payment
     */
    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
            'zalopay' => 'ZaloPay',
            default => 'Không xác định',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Đang chờ',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn trả',
            default => 'Không xác định',
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.') . ' ₫';
    }
}
