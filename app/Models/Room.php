<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'floor_id', 'capacity', 'price'];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function registrations()
    {
        return $this->hasMany(RoomRegistration::class);
    }
}
