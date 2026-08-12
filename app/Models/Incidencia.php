<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pedido_id',
        'customer_id',
        'tipo',
        'descripcion',
        'status',
        'resolucion',
        'atendida_por',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function atendidaPor()
    {
        return $this->belongsTo(User::class, 'atendida_por');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'no_llego' => '📦 No llegó el pedido',
            'producto_incorrecto' => '🔄 Producto incorrecto',
            'producto_defectuoso' => '💔 Producto defectuoso',
            'cobro_incorrecto' => '💳 Cobro incorrecto',
            'otro' => '❓ Otro problema',
            default => ucfirst($this->tipo),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'abierta' => 'Abierta',
            'en_revision' => 'En revisión',
            'resuelta' => 'Resuelta',
            'cerrada' => 'Cerrada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'abierta' => '#ff3b5c',
            'en_revision' => '#fbbf24',
            'resuelta' => '#00d68f',
            'cerrada' => '#6B7A99',
            default => '#6B7A99',
        };
    }
}
