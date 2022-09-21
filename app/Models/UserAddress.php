<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_address';
    protected $guarded = [];
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    public function area() {
        return $this->belongsTo(Area::class,'area_id');
    }
}
