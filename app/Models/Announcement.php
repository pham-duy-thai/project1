<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'created_by',
        'published_at',
        'expires_at',
        'is_published',
        'send_email',
        'send_notification',
        'target_audience',
        'target_id',
        'attachments',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_published' => 'boolean',
        'send_email' => 'boolean',
        'send_notification' => 'boolean',
        'attachments' => 'array',
    ];

    /**
     * Get the user who created this announcement
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if announcement is active
     */
    public function isActive(): bool
    {
        if (!$this->is_published) {
            return false;
        }

        if ($this->published_at && $this->published_at > now()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'general' => 'Thông báo chung',
            'maintenance' => 'Bảo trì',
            'payment' => 'Thanh toán',
            'event' => 'Sự kiện',
            'emergency' => 'Khẩn cấp',
            'rule' => 'Nội quy',
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
            'normal' => 'Bình thường',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            'urgent' => 'Khẩn cấp',
            default => 'Không xác định',
        };
    }

    /**
     * Get target audience label
     */
    public function getTargetAudienceLabelAttribute(): string
    {
        return match($this->target_audience) {
            'all' => 'Tất cả',
            'students' => 'Sinh viên',
            'staff' => 'Nhân viên',
            'building' => 'Theo tòa nhà',
            'room' => 'Theo phòng',
            default => 'Tất cả',
        };
    }

    /**
     * Get the building relationship (when target_audience is 'building')
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'target_id');
    }

    /**
     * Get the room relationship (when target_audience is 'room')
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'target_id');
    }
}
