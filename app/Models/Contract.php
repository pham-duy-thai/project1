<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_registration_id',
        'start_date',
        'end_date',
        'total_amount',
        'status'
    ];

    public function registration()
    {
        return $this->belongsTo(RoomRegistration::class, 'room_registration_id');
    }
}
