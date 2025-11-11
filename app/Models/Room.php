<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['building_id', 'floor_number', 'room_number', 'capacity', 'price'];


    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function building()
    {
        // để dễ lấy tòa từ phòng
        return $this->hasOneThrough(Building::class, Floor::class, 'id', 'id', 'floor_id', 'building_id');
    }
}
