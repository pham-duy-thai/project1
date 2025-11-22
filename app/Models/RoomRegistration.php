<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'room_id',
        'registration_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Quan hệ với sinh viên
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Quan hệ với phòng
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Quan hệ với hợp đồng (1 đăng ký → 1 hợp đồng)
     * Dùng khi bạn tạo bảng contracts có registration_id
     */
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
