<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'room_id', 'start_date', 'end_date', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
