<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'severity',
        'penalty_amount',
        'order',
        'is_active',
        'requires_acknowledgment',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_acknowledgment' => 'boolean',
        'penalty_amount' => 'decimal:2',
    ];

    /**
     * Get acknowledgments for this rule
     */
    public function acknowledgments()
    {
        return $this->hasMany(RuleAcknowledgment::class);
    }

    /**
     * Check if a student has acknowledged this rule
     */
    public function isAcknowledgedBy($studentId)
    {
        return $this->acknowledgments()
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'safety' => 'An toàn',
            'hygiene' => 'Vệ sinh',
            'discipline' => 'Kỷ luật',
            'general' => 'Chung',
            default => 'Không xác định',
        };
    }

    /**
     * Get severity label
     */
    public function getSeverityLabelAttribute()
    {
        return match($this->severity) {
            'low' => 'Thấp',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            default => 'Không xác định',
        };
    }
}
