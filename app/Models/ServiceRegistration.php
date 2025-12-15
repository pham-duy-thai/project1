<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRegistration extends Model
{
    protected $fillable = [
        'service_id',
        'student_id',
        'start_date',
        'end_date',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'active' => 'Đang sử dụng',
            'cancelled' => 'Đã hủy',
            'expired' => 'Hết hạn',
            default => 'Không xác định',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'active' => 'success',
            'cancelled' => 'secondary',
            'expired' => 'danger',
            default => 'secondary',
        };
    }
}
