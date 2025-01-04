<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    public static string $storagePath = 'storage_path';
    protected $fillable = ['key', 'value'];


    public static function updateSetting($key, $value): bool
    {
        $result = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        return $result->wasRecentlyCreated || $result->wasChanged();
    }

    public static function getSettingByKeyName(string $key): string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : '';
    }

}
