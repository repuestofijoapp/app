<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\RepairItemStatus;

class RepairItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_order_id',
        'oem_product_id',
        'provider_id',
        'price',
        'status',
        'green_api_message_id',
        'retry_count',
        'last_retry_at',
        'confirmed_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => RepairItemStatus::class,
        'last_retry_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function oemProduct(): BelongsTo
    {
        return $this->belongsTo(OemProduct::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}