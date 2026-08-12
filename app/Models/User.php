<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'phone',
        'phone_verified_at',
        'saved_addresses',
        'ruc_dni',
        'role',
        'business_name',
        'ciiu_code',
        'profile_photo_path',
        'is_active',
        'last_session_id',
        'onboarding_completed_at',
        'receipt_type',
        'blocked_at',
        'blocked_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'saved_addresses' => 'array',
        'password' => 'hashed',
        'role' => UserRole::class,
        'onboarding_completed_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    // Relationships
    public function repairOrders()
    {
        return $this->hasMany(RepairOrder::class);
    }

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    // Helper methods for roles
    public function isMechanic(): bool
    {
        return $this->role === UserRole::Mechanic;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isWorkshop(): bool
    {
        return $this->role === UserRole::Workshop;
    }

    public function isStore(): bool
    {
        return $this->role === UserRole::Store;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    public function isTransporte(): bool
    {
        return $this->role === UserRole::Transporte;
    }

    public function canAccessDashboard(): bool
    {
        return in_array($this->role, [UserRole::Admin, UserRole::Manager]);
    }

    // ── WhatsApp phone helpers ────────────────────────────────────────────

    /** ¿Tiene número de WhatsApp verificado? */
    public function hasVerifiedPhone(): bool
    {
        return !empty($this->phone) && $this->phone_verified_at !== null;
    }

    /** Guardar y marcar teléfono como verificado */
    public function saveVerifiedPhone(string $phone): void
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        // Asegurar prefijo de país Peru (51) si no lo tiene
        if (strlen($clean) === 9) {
            $clean = '51' . $clean;
        }
        $this->update([
            'phone' => $clean,
            'phone_verified_at' => now(),
        ]);
    }

    // ── Saved Addresses (max 5, ordered by most recently used) ───────────

    /** Devuelve las addresses guardadas, el más reciente primero */
    public function getSavedAddresses(): array
    {
        return $this->saved_addresses ?? [];
    }

    /**
     * Guarda una nueva dirección al inicio de la lista.
     * Elimina duplicados (misma dirección+distrito/agencia) y limita a 5.
     *
     * @param array $address {type, label, address?, district?, agency?, city?,
     *                        recipient_name?, recipient_dni?, recipient_phone?,
     *                        recipient_address?}
     */
    public function saveAddress(array $address): void
    {
        $address['used_at'] = now()->toIso8601String();

        // Auto-generar label si no viene
        if (empty($address['label'])) {
            if ($address['type'] === 'lima') {
                $address['label'] = trim(($address['district'] ?? '') . ' — ' . ($address['address'] ?? ''));
            } else {
                $address['label'] = trim(($address['city'] ?? '') . ' vía ' . ($address['agency'] ?? ''));
            }
        }

        $existing = $this->getSavedAddresses();

        // Eliminar entradas duplicadas (misma dirección+distrito para Lima, o ciudad+agencia para provincua)
        $existing = array_filter($existing, function ($a) use ($address) {
            if ($a['type'] !== $address['type'])
                return true;
            if ($address['type'] === 'lima') {
                return !($a['address'] === $address['address'] && $a['district'] === $address['district']);
            }
            return !($a['agency'] === $address['agency'] && $a['city'] === $address['city']);
        });

        // Insertar al inicio (más reciente primero)
        array_unshift($existing, $address);

        // Limitar a 5
        $existing = array_slice(array_values($existing), 0, 5);

        $this->update(['saved_addresses' => $existing]);
    }

    /**
     * Get the user who blocked this user.
     */
    public function blockedBy()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            UserRole::Admin => 'Administrador',
            UserRole::Manager => 'Gestor',
            UserRole::Mechanic => 'Mecánico Particular',
            UserRole::Workshop => 'Taller Automotriz',
            UserRole::Store => 'Tienda de Repuestos',
            UserRole::Transporte => 'Transporte',
            default => 'Cliente',
        };
    }

    public function getDocumentLabel(): string
    {
        if (empty($this->ruc_dni)) return 'Documento';
        return strlen($this->ruc_dni) === 11 ? 'RUC' : 'DNI';
    }
}
