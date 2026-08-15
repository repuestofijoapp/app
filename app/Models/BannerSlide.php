<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BannerSlide extends Model
{
    protected $fillable = [
        'image_path',
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    public static function getActive()
    {
        return self::where('active', true)->orderBy('sort_order')->get();
    }
}
