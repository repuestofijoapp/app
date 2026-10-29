<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Incidencia extends Model
{
    use SoftDeletes;

    protected $table = 'incidencias';

    protected $fillable = [
        'codigo',
        'pedido_id',
        'customer_id',
        'tipo',
        'submotivo',
        'descripcion',
        'foto_path',
        'telefono_verificado',
        'status',
        'historial_estados',
        'resolucion',
        'nota_resolucion_cliente',
        'atendida_por',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at'       => 'datetime',
        'historial_estados' => 'array',
    ];

    // ── Boot: Generación secuencial de Código INC-000123 ─────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($incidencia) {
            if (empty($incidencia->codigo)) {
                $maxId = static::withTrashed()->max('id') ?? 0;
                $incidencia->codigo = 'INC-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
            }

            if (empty($incidencia->historial_estados)) {
                $incidencia->historial_estados = [
                    [
                        'status'     => $incidencia->status ?? 'abierta',
                        'timestamp'  => now()->toIso8601String(),
                        'changed_by' => auth()->id() ?? $incidencia->customer_id,
                        'role'       => 'cliente',
                        'nota'       => 'Incidencia radicada por el cliente',
                    ]
                ];
            }
        });
    }

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

    // ── Helpers & URL Firmada ────────────────────────────────────

    /**
     * Genera una URL firmada y temporal para visualización segura de la evidencia en el panel admin.
     */
    public function getFotoSignedUrlAttribute(): ?string
    {
        if (empty($this->foto_path)) {
            return null;
        }

        // Si es una ruta privada en storage/app/incidencias
        if (Storage::disk('local')->exists($this->foto_path)) {
            return URL::temporarySignedRoute(
                'admin.incidencias.evidencia',
                now()->addMinutes(60),
                ['id' => $this->id]
            );
        }

        // Si se guardó en disco público como fallback
        if (Storage::disk('public')->exists($this->foto_path)) {
            return Storage::disk('public')->url($this->foto_path);
        }

        return null;
    }

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'producto_incorrecto' => '📦 Producto o cantidad incorrectos',
            'daniado_falla'       => '💔 Llegó dañado o con falla',
            'no_llego'            => '🚫 No llegó mi pedido',
            'otro'                => '💬 Otro problema',
            default               => ucfirst(str_replace('_', ' ', $this->tipo)),
        };
    }

    public function getSubmotivoLabelAttribute(): string
    {
        return match ($this->submotivo) {
            'no_era_producto'     => 'No era el producto solicitado',
            'menos_cantidad'      => 'Me llegó menos cantidad',
            'no_compatible'       => 'No es compatible con mi vehículo',
            'golpeado_abierto'    => 'Llegó golpeado o abierto',
            'falla_fabrica'       => 'Falla de fábrica o defecto',
            'no_llego_nada'       => 'No llegó nada al domicilio',
            'caja_ajena'          => 'Llegó una caja que no es mía',
            default               => $this->submotivo ? ucfirst(str_replace('_', ' ', $this->submotivo)) : 'General',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'abierta'        => 'Abierta',
            'en_revision'    => 'En revisión',
            'reenvio_camino' => 'Reenvío en camino',
            'reembolsado'    => 'Reembolsado',
            'resuelta'       => 'Cerrado / Resuelto',
            'rechazada'      => 'Rechazado',
            'cerrada'        => 'Cerrada',
            default          => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'abierta'        => '#ef4444', // Rojo
            'en_revision'    => '#f59e0b', // Ámbar
            'reenvio_camino' => '#3b82f6', // Azul
            'reembolsado'    => '#8b5cf6', // Morado
            'resuelta'       => '#10b981', // Verde esmeralda
            'rechazada'      => '#64748b', // Slate
            'cerrada'        => '#475569',
            default          => '#94a3b8',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'abierta'        => 'badge-danger',
            'en_revision'    => 'badge-warning',
            'reenvio_camino' => 'badge-info',
            'reembolsado'    => 'badge-purple',
            'resuelta'       => 'badge-success',
            'rechazada'      => 'badge-secondary',
            'cerrada'        => 'badge-dark',
            default          => 'badge-secondary',
        };
    }

    /**
     * SLA Timer y Métricas
     */
    public function getSlaAttribute(): array
    {
        $isClosed = in_array($this->status, ['resuelta', 'rechazada', 'cerrada']);
        $end = ($isClosed && $this->resolved_at) ? $this->resolved_at : now();
        $start = $this->created_at ?? now();

        $diffMinutes = max(0, $start->diffInMinutes($end));
        $hours = intdiv($diffMinutes, 60);
        $mins = $diffMinutes % 60;

        $timeString = $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";

        if ($isClosed) {
            return [
                'active'  => false,
                'minutes' => $diffMinutes,
                'label'   => "Resuelto en {$timeString}",
                'color'   => '#10b981',
                'bg'      => 'rgba(16,185,129,0.15)',
                'level'   => 'closed',
            ];
        }

        if ($diffMinutes < 60) {
            return [
                'active'  => true,
                'minutes' => $diffMinutes,
                'label'   => "SLA: {$timeString} (< 1h)",
                'color'   => '#10b981',
                'bg'      => 'rgba(16,185,129,0.15)',
                'level'   => 'ok',
            ];
        }

        if ($diffMinutes <= 120) {
            return [
                'active'  => true,
                'minutes' => $diffMinutes,
                'label'   => "SLA: {$timeString} (1-2h)",
                'color'   => '#f59e0b',
                'bg'      => 'rgba(245,158,11,0.15)',
                'level'   => 'warning',
            ];
        }

        return [
            'active'  => true,
            'minutes' => $diffMinutes,
            'label'   => "SLA: {$timeString} (> 2h)",
            'color'   => '#ef4444',
            'bg'      => 'rgba(239,68,68,0.2)',
            'level'   => 'danger',
        ];
    }
}
