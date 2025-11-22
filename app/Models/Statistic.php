<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'month',
        'year',
        'total_students',
        'revenue',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
