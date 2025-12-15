<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_code',
        'student_id',
        'room_id',
        'title',
        'description',
        'severity',
        'violation_date',
        'fine_amount',
        'fine_status',
        'fine_paid_date',
        'reported_by',
        'evidence',
        'action_taken',
        'status',
        'notes',
    ];

    protected $casts = [
        'violation_date' => 'date',
        'fine_paid_date' => 'date',
        'fine_amount' => 'decimal:2',
        'evidence' => 'array',
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
     * Get the user who reported this violation
     */
    public function reportedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Alias for reportedByUser (used in controller)
     */
    public function reporter(): BelongsTo
    {
        return $this->reportedByUser();
    }

    /**
     * Alias for reportedByUser (used in views)
     */
    public function reportedBy(): BelongsTo
    {
        return $this->reportedByUser();
    }

    /**
     * Get severity label
     */
    public function getSeverityLabelAttribute(): string
    {
        return match($this->severity) {
            'minor' => 'Nhẹ',
            'moderate' => 'Trung bình',
            'serious' => 'Nghiêm trọng',
            'critical' => 'Rất nghiêm trọng',
            default => 'Không xác định',
        };
    }

    /**
     * Get fine status label
     */
    public function getFineStatusLabelAttribute(): string
    {
        return match($this->fine_status) {
            'unpaid' => 'Chưa đóng phạt',
            'paid' => 'Đã đóng phạt',
            'waived' => 'Được miễn',
            default => 'Không xác định',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'resolved' => 'Đã giải quyết',
            'appealed' => 'Đang khiếu nại',
            'dismissed' => 'Đã bỏ qua',
            default => 'Không xác định',
        };
    }

    /**
     * Get formatted fine amount
     */
    public function getFormattedFineAmountAttribute(): string
    {
        return number_format($this->fine_amount, 0, ',', '.') . ' ₫';
    }

    /**
     * Get fine_paid attribute (backward compatibility)
     */
    public function getFinePaidAttribute(): bool
    {
        return $this->fine_status === 'paid';
    }

    /**
     * Get fine_paid_at attribute (backward compatibility)
     */
    public function getFinePaidAtAttribute()
    {
        return $this->fine_paid_date;
    }

    /**
     * Get is_resolved attribute (backward compatibility)
     */
    public function getIsResolvedAttribute(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Get resolved_at attribute (backward compatibility)
     */
    public function getResolvedAtAttribute()
    {
        return $this->status === 'resolved' ? $this->updated_at : null;
    }

    /**
     * Get reported_date attribute (backward compatibility)
     */
    public function getReportedDateAttribute()
    {
        return $this->violation_date;
    }

    /**
     * Get evidence images paths (extract paths from evidence array)
     */
    public function getEvidenceImagesAttribute(): array
    {
        if (!$this->evidence || !is_array($this->evidence)) {
            return [];
        }

        $paths = [];
        foreach ($this->evidence as $item) {
            if (is_array($item) && isset($item['path'])) {
                $paths[] = $item['path'];
            } elseif (is_string($item)) {
                // Handle old format where evidence might be array of strings
                $paths[] = $item;
            }
        }

        return $paths;
    }
}
