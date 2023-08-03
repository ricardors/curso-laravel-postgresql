<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $table = "refresh_token";

    protected $fillable = [
        'refresh_token',
        'user_id',
        'active',
        'expires_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'ID', 'user_id')->where('STATUS', 1);
    }
}

