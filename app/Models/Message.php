<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'account_id',
        'account_title',
        'message',
        'image_path',
        'is_read',
        'is_auto_message',
        'offer_price',
        'is_price_offer',
        'offer_valid_until',
    ];

    protected $casts = [
        'is_auto_message' => 'boolean',
        'is_read' => 'boolean',
        'is_price_offer' => 'boolean',
        'offer_valid_until' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function account()
    {
        return $this->belongsTo(GameAccount::class, 'account_id');
    }

    public static function unreadCountFor($userId)
    {
        return self::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
