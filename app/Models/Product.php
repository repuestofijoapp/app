<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'category_id',
        'brand',
        'fuel_type',
        'vehicle_make',
        'supplier_code',
        'oem_code',
        'additional_oem_codes',
        'oversize',
        'name',
        'compatible_engines',
        'compatible_vehicles',
        'compatible_model_ids',
        'compatible_engine_ids',
        'specs',
        'notes',
        'price',
        'is_active',
        'created_by',
        'updated_by',
        'image_path',
    ];

    protected $casts = [
        'additional_oem_codes' => 'array',
        'compatible_engines' => 'array',
        'compatible_vehicles' => 'array',
        'compatible_model_ids' => 'array',
        'compatible_engine_ids' => 'array',
        'specs' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function oversizes()
    {
        return $this->hasMany(ProductOversize::class, 'product_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Search by supplier code OR oem_code (used by the public search)
    public static function searchByCode(string $term)
    {
        $term = strtoupper(trim($term));
        return self::where(function ($q) use ($term) {
            $q->where('supplier_code', 'LIKE', '%' . $term . '%')
                ->orWhere('oem_code', 'LIKE', '%' . $term . '%')
                ->orWhere('name', 'LIKE', '%' . $term . '%')
                ->orWhereJsonContains('additional_oem_codes', $term);
        })->with(['provider', 'category'])->active()->get();
    }

    // Search by vehicle compatibility (model name or engine code)
    public static function searchByVehicle(string $model, ?string $engineCode = null, ?string $brand = null)
    {
        return self::where(function ($q) use ($model, $engineCode, $brand) {
            $q->where('compatible_vehicles', 'LIKE', '%' . $model . '%');
            if ($brand) {
                $q->where('compatible_vehicles', 'LIKE', '%' . strtoupper($brand) . '%');
            }
            if ($engineCode) {
                $q->where(function ($q2) use ($engineCode) {
                    $q2->where('compatible_engines', 'LIKE', '%' . strtoupper($engineCode) . '%')
                        ->orWhereJsonContains('compatible_engines', strtoupper($engineCode));
                });
            }
        })->with(['provider', 'category'])->active()->get();
    }

    // Get the category IDs that have compatible products for a given vehicle
    public static function getCompatibleCategoryIds(string $model, ?string $engineCode = null, ?string $brand = null): array
    {
        return self::where(function ($q) use ($model, $engineCode) {
            // Match by engine code (exact) OR by model name in compatible_vehicles
            if ($engineCode) {
                $q->where('compatible_engines', 'LIKE', '%"' . strtoupper($engineCode) . '"%');
            }
            if ($model) {
                $q->orWhere('compatible_vehicles', 'LIKE', '%' . strtoupper($model) . '%');
            }
        })->active()->pluck('category_id')->unique()->filter()->values()->toArray();
    }

    // Helper: get engines list as string
    public function getEnginesLabel(): string
    {
        $engines = $this->compatible_engines ?? [];
        return implode(' / ', $engines);
    }

    // Helper: get oversize label
    public function getOversizeLabel(): string
    {
        return $this->oversize === 'STD' || empty($this->oversize) ? 'STD' : '+' . $this->oversize;
    }

    // Unified Image URL Accessor
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return 'https://via.placeholder.com/300?text=No+Foto';
    }
}
