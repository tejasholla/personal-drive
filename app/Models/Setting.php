<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static string $storagePath = 'storage_path';
    public static string $uuidForStorageFiles = 'uuidForStorageFiles';
    public static string $uuidForThumbnails = 'uuidForThumbnails';
    protected $fillable = ['key', 'value'];

    protected $hidden = [
        'uuidForStorageFiles',
        'uuidForThumbnails',
    ];

    public static function updateSetting($key, $value): bool
    {
        $result = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        return $result->wasRecentlyCreated || $result->wasChanged();
    }

    public static function getUUIDForStorageFiles(): string
    {
        return Setting::getSettingByKeyName(self::$uuidForStorageFiles) ?: '';
    }
    public static function getUUIDForThumbnails(): string
    {
        return Setting::getSettingByKeyName(self::$uuidForThumbnails) ?: '';
    }

    public static function getSettingByKeyName(string $key): string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : '';
    }

    public static function getAllSettings(): array
    {
        return static::pluck('value', 'key')->toArray();
    }
}
