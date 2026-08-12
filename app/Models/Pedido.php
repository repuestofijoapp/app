<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Pedido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'transporte_id',
        'tipo_envio',
        'distrito',
        'direccion',
        'referencia',
        'telefono_contacto',
        'metodo_pago',
        'culqi_charge_id',
        'payment_confirmed_at',
        'clave_secreta',
        'subtotal',
        'costo_envio',
        'total',
        'status',
        'cancellation_reason',
        'billing_type',
        'invoice_url',
        'invoice_xml',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_confirmed_at' => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function transporte()
    {
        return $this->belongsTo(User::class, 'transporte_id');
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function zbotQueries()
    {
        return $this->hasMany(ZbotQuery::class);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** ¿El pago fue confirmado por Culqi? */
    public function isPaid(): bool
    {
        return $this->status === 'pagado' && $this->payment_confirmed_at !== null;
    }

    /** ¿Está esperando respuesta de algún proveedor? */
    public function isPendingProviders(): bool
    {
        return in_array($this->status, ['pendiente', 'por_confirmar']);
    }

    // ── Encryption helpers ───────────────────────────────────────

    public function setClaveSecretaAttribute($value)
    {
        $this->attributes['clave_secreta'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getClaveSecretaDecryptedAttribute()
    {
        return $this->clave_secreta ? Crypt::decryptString($this->clave_secreta) : null;
    }

    // ── Status label helper ──────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendiente' => 'Pendiente',
            'por_confirmar' => 'Por confirmar',
            'pagado' => 'Pagado',
            'en_preparacion' => 'En preparación',
            'en_camino' => 'En camino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendiente' => '#6B7A99',
            'por_confirmar' => '#fbbf24',
            'pagado' => '#3b82f6',
            'en_preparacion' => '#a855f7',
            'en_camino' => '#f97316',
            'entregado' => '#00d68f',
            'cancelado' => '#ff3b5c',
            default => '#6B7A99',
        };
    }
}
