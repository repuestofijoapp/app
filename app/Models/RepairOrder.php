<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\RepairOrderStatus;

class RepairOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_plate',
        'status',
        'total_price',
        'commission',
        'delivery_cost',
    ];

    protected $casts = [
        'status' => RepairOrderStatus::class,
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_plate', 'plate');
    }

    public function repairItems(): HasMany
    {
        return $this->hasMany(RepairItem::class);
    }
}