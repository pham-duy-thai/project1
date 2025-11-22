<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Quan hệ ngược lại với User (1 role có nhiều user)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
