<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleAcknowledgment extends Model
{
    protected $fillable = [
        'rule_id',
        'student_id',
        'acknowledged_at',
        'ip_address',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    /**
     * Get the rule
     */
    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
