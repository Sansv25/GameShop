<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null): ?string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Check if chatbot is globally active.
     */
    public static function isChatbotActive(): bool
    {
        return (bool) self::getValue('chatbot_active', false);
    }

    /**
     * Get the Gemini API key.
     */
    public static function getGeminiApiKey(): ?string
    {
        return self::getValue('gemini_api_key');
    }
}
