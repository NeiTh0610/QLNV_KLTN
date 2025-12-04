<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Settings
{
    /**
     * Retrieve a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$key}";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($key, $default) {
            $value = DB::table('settings')->where('key', $key)->value('value');

            if (is_null($value)) {
                return $default;
            }

            $decoded = json_decode($value, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        $jsonValue = is_string($value) ? $value : json_encode($value);
        
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $jsonValue, 'updated_at' => now()]
        );

        self::forget($key);
    }

    /**
     * Forget a cached setting value.
     */
    public static function forget(string $key): void
    {
        Cache::forget("settings.{$key}");
    }
}

