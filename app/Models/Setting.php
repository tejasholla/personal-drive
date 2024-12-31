<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $hidden = [
        'uuid',
    ];

    public static function updateSetting($key, $value): bool
    {
        $result = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        return $result->wasRecentlyCreated || $result->wasChanged();
    }

    public static function getUUID(): string
    {
        $storageUuid = Setting::getSettingByKeyName('uuid');
        return $storageUuid ?: '';
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
