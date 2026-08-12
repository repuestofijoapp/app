<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getBool($key, $default = false)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? (bool) $setting->value : $default;
    }

    public static function setBool($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value ? '1' : '0']);
    }
}
