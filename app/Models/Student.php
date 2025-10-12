<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'student_code', 'room_number', 'phone'];
public function room()
    {
        return $this->belongsTo(Room::class, 'room_number', 'name');
    }
}

 

