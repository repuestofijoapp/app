<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Retrieve the list of disabled vehicle makes (all uppercase).
     */
    public static function getDisabledMakes(): array
    {
        return Cache::remember('disabled_vehicle_makes', 3600, function () {
            $setting = self::where('key', 'disabled_vehicle_makes')->first();
            if (!$setting || empty($setting->value)) {
                return [];
            }
            $decoded = json_decode($setting->value, true);
            return is_array($decoded) ? array_values(array_unique(array_filter(array_map('strtoupper', array_map('trim', $decoded))))) : [];
        });
    }

    /**
     * Set the list of disabled vehicle makes and clear public search caches.
     */
    public static function setDisabledMakes(array $makes): void
    {
        $normalized = array_values(array_unique(array_filter(array_map('strtoupper', array_map('trim', $makes)))));
        self::updateOrCreate(
            ['key' => 'disabled_vehicle_makes'],
            ['value' => json_encode($normalized)]
        );

        Cache::forget('disabled_vehicle_makes');
        Cache::forget('brands_with_products');
        Cache::forget('all_makes_names');
    }

    /**
     * Check if a specific make is disabled.
     */
    public static function isMakeDisabled(?string $make): bool
    {
        if (empty($make)) {
            return false;
        }
        $disabled = self::getDisabledMakes();
        return in_array(strtoupper(trim($make)), $disabled, true);
    }
}

