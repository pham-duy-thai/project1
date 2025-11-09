<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * Các cột có thể gán hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'student_code',
        'name',
        'gender',
        'class',
        'phone',
        'address',
        'date_of_birth',
        'status',
    ];

    /**
     * Kiểu dữ liệu cho các cột (tự động cast)
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Liên kết với bảng users (1 user → 1 student)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
