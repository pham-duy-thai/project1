<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'current_students',
        'price',
        'status',
    ];

    public function students()
    {
        return $this->hasMany(Student::class,'room_number','name');
    }
}
