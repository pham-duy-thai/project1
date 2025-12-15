<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'room_id',
        'student_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'images',
        'requested_at',
        'approved_at',
        'started_at',
        'completed_at',
        'assigned_to',
        'admin_notes',
        'completion_notes',
        'cost',
    ];

    protected $casts = [
        'images' => 'array',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the student who made the request
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user assigned to handle this request
     */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'plumbing' => 'Hệ thống nước',
            'electrical' => 'Hệ thống điện',
            'furniture' => 'Đồ nội thất',
            'air_conditioning' => 'Điều hòa',
            'door_lock' => 'Cửa và khóa',
            'window' => 'Cửa sổ',
            'other' => 'Khác',
            default => 'Không xác định',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Thấp',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            'urgent' => 'Khẩn cấp',
            default => 'Không xác định',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'in_progress' => 'Đang xử lý',
            'processed' => 'Đã xử lý',
            'completed' => 'Hoàn thành',
            'rejected' => 'Từ chối',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định',
        };
    }
}
