<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OemProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'oem_code',
        'category_id',
        'name',
        'description',
        'image_url',
        'specs',
        'compatible_models',
        'common_brands',
    ];

    protected $casts = [
        'common_brands' => 'array',
        'compatible_models' => 'array',
        'specs' => 'array',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function repairItems(): HasMany
    {
        return $this->hasMany(RepairItem::class);
    }

    // Helper methods
    public function getBrandsList(): array
    {
        return $this->common_brands ?? [];
    }

    public function hasBrand(string $brand): bool
    {
        $brands = $this->getBrandsList();
        return in_array(strtoupper($brand), array_map('strtoupper', $brands));
    }

    public function addBrand(string $brand): void
    {
        $brands = $this->getBrandsList();
        if (!in_array(strtoupper($brand), array_map('strtoupper', $brands))) {
            $brands[] = strtoupper($brand);
            $this->common_brands = $brands;
            $this->save();
        }
    }

    // Search methods
    public static function searchByOemCode(string $oemCode)
    {
        return self::where('oem_code', 'LIKE', '%' . $oemCode . '%')
            ->with('category')
            ->get();
    }

    public static function searchByCategory(int $categoryId)
    {
        return self::where('category_id', $categoryId)
            ->orWhereHas('category', function ($query) use ($categoryId) {
            $query->where('parent_id', $categoryId);
        })
            ->with('category')
            ->get();
    }
}