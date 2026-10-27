<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoHito extends Model
{
    protected $table = 'pedido_hitos';

    protected $fillable = [
        'pedido_id',
        'tipo_hito',
        'foto_url',
        'lat',
        'lng',
        'distancia_metros',
        'dentro_de_rango',
        'operador_nombre',
        'operador_user_id',
        'observaciones',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'distancia_metros' => 'decimal:2',
        'dentro_de_rango' => 'boolean',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_user_id');
    }

    public function getHitoLabelAttribute(): string
    {
        return match ($this->tipo_hito) {
            'recojo_proveedor' => 'Recojo en Proveedor',
            'embalaje_hub' => 'Embalaje en Hub Central',
            'entrega_motorizado' => 'Despacho a Motorizado',
            'entrega_cliente' => 'Entrega al Cliente',
            default => ucfirst($this->tipo_hito),
        };
    }

    public function getHitoIconAttribute(): string
    {
        return match ($this->tipo_hito) {
            'recojo_proveedor' => 'fas fa-boxes',
            'embalaje_hub' => 'fas fa-box-open',
            'entrega_motorizado' => 'fas fa-motorcycle',
            'entrega_cliente' => 'fas fa-home',
            default => 'fas fa-check-circle',
        };
    }
}
