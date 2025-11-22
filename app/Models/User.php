<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // ✅ thêm vào đây
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Quan hệ với bảng Role (1 user thuộc 1 role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Quan hệ với bảng Student (1 user có thể là 1 sinh viên)
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Kiểm tra xem user có phải admin không
     */
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }
}
