<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasHashId
{
    public static function bootHasHashId()
    {
        static::creating(function ($model) {
            if (!$model->hash_id) {
                $model->hash_id = static::generateUniqueHashId();
            }
        });
    }

    public static function generateUniqueHashId()
    {
        do {
            $hash = Str::random(10);
        } while (static::where('hash_id', $hash)->exists());

        return $hash;
    }

    public function getRouteKeyName()
    {
        return 'hash_id';
    }
}
