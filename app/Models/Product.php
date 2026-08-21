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
        'fuel_types',
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
        'fuel_types' => 'array',
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

    /**
     * Returns the list of fuel types as a clean array.
     * Falls back to the legacy fuel_type string column for backward compatibility.
     */
    public function getFuelTypesListAttribute(): array
    {
        if (!empty($this->fuel_types) && is_array($this->fuel_types)) {
            return array_filter($this->fuel_types);
        }
        // Legacy fallback
        if (!empty($this->fuel_type)) {
            return [$this->fuel_type];
        }
        return [];
    }

    /**
     * Fuel type config: returns colors and icons per type.
     */
    public static function fuelConfig(): array
    {
        return [
            'GASOLINA' => ['icon' => '🔴', 'label' => 'GASOLINA', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA', 'admin_bg' => 'rgba(220,38,38,0.15)', 'admin_text' => '#f87171', 'admin_border' => 'rgba(220,38,38,0.3)'],
            'DIESEL'   => ['icon' => '🟡', 'label' => 'DIESEL',   'bg' => '#FEF3C7', 'text' => '#D97706', 'border' => '#FDE68A', 'admin_bg' => 'rgba(217,119,6,0.15)',  'admin_text' => '#fbbf24', 'admin_border' => 'rgba(217,119,6,0.3)'],
            'GAS'      => ['icon' => '💚', 'label' => 'GAS',      'bg' => '#D1FAE5', 'text' => '#059669', 'border' => '#6EE7B7', 'admin_bg' => 'rgba(5,150,105,0.15)',   'admin_text' => '#34d399', 'admin_border' => 'rgba(5,150,105,0.3)'],
            'HIBRIDO'  => ['icon' => '🔵', 'label' => 'HÍBRIDO',  'bg' => '#DBEAFE', 'text' => '#2563EB', 'border' => '#BFDBFE', 'admin_bg' => 'rgba(37,99,235,0.15)',   'admin_text' => '#60a5fa', 'admin_border' => 'rgba(37,99,235,0.3)'],
        ];
    }
}
