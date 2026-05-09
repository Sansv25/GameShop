<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAccount extends Model
{
    use HasFactory, \App\Traits\HasHashId;

    protected $fillable = [
        'title',
        'description',
        'category',
        'price',
        'image_path',
        'images',
        'accounts',
        'username',
        'password',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'accounts' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
