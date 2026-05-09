<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gameAccount()
    {
        return $this->belongsTo(GameAccount::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
