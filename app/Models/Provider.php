<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'contact_email',
        'portal_password',
        'whatsapp_number',
        'phone',
        'address',
        'city',
        'country',
        'ruc',
        'bank_account_number',
        'specialty',
        'leads_count',
        'leads_reset_at',
        'is_active',
        'requires_zbot',
    ];

    protected $hidden = [
        'portal_password',
        'remember_token',
    ];

    protected $casts = [
        'leads_reset_at' => 'date',
        'is_active' => 'boolean',
        'requires_zbot' => 'boolean',
        'portal_password' => 'hashed',
    ];

    // Relationships
    public function repairItems(): HasMany
    {
        return $this->hasMany(RepairItem::class);
    }

    // Helper methods
    public function getConfirmedRepairItemsCount(): int
    {
        return $this->repairItems()->where('status', 'confirmed')->count();
    }

    public function getPendingRepairItemsCount(): int
    {
        return $this->repairItems()->where('status', 'pending')->count();
    }

    public function getAllItemsConfirmed(): bool
    {
        return $this->repairItems()->whereIn('status', ['pending', 'rejected', 'timeout'])->doesntExist();
    }

    public function getConfirmationProgress(): float
    {
        $totalItems = $this->repairItems()->count();
        if ($totalItems === 0) {
            return 0.0;
        }
        $confirmedItems = $this->getConfirmedRepairItemsCount();
        return ($confirmedItems / $totalItems) * 100;
    }
}