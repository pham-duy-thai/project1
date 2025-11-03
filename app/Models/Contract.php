<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'sign_date', 'expire_date', 'total_price'];

    public function registration()
    {
        return $this->belongsTo(RoomRegistration::class);
    }
}
