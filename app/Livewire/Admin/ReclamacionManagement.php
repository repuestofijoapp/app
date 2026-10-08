<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReclamacionManagement extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $estadoFilter = '';
    public string $tipoFilter   = '';
    public int    $perPage      = 25;

    public function paginationView(): string
    {
        return 'vendor.pagination.custom-repuestofijo';
    }

    public static function formatTipo(?string $tipo): string
    {
        return match ($tipo) {
            'producto_defectuoso' => 'Producto defectuoso o con falla',
            'producto_incorrecto' => 'Producto incorrecto entregado',
            'pedido_no_entregado' => 'Pedido no entregado',
            'entrega_tardía'      => 'Demora en la entrega',
            'cobro_incorrecto'    => 'Cobro incorrecto',
            'devolucion_rechazada'=> 'Devolución rechazada o no procesada',
            'mala_atencion'       => 'Mala atención al cliente',
            'falta_informacion'   => 'Falta de información sobre el pedido',
            'problema_plataforma' => 'Problema técnico en la plataforma',
            'otro'                => 'Otro motivo',
            default               => ucfirst(str_replace('_', ' ', $tipo ?? 'No especificado')),
        };
    }

    public static function formatSolucion(?string $solucion): string
    {
        return match ($solucion) {
            'reemplazo'          => 'Reemplazo del producto',
            'devolucion_dinero'  => 'Devolución del dinero',
            'entrega_pendiente'  => 'Entrega del pedido pendiente',
            'descuento'          => 'Descuento o compensación',
            'disculpa_formal'    => 'Disculpa formal',
            'mejora_servicio'    => 'Mejora del servicio (queja)',
            'otra'               => 'Otra solución',
            default              => ucfirst(str_replace('_', ' ', $solucion ?? 'No especificada')),
        };
    }

    public static function isQueja(?string $tipo): bool
    {
        return in_array($tipo, ['mala_atencion', 'falta_informacion', 'problema_plataforma', 'otro', 'queja']);
    }

    // Modal
    public bool   $showModal    = false;
    public ?array $selected     = null;

    // Form dentro del modal
    public string $respuesta    = '';
    public string $nuevoEstado  = '';

    // Stats
    public int $totalPendientes  = 0;
    public int $totalEnRevision  = 0;
    public int $totalRespondidas = 0;
    public int $totalCerradas    = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $this->totalPendientes  = DB::table('reclamaciones')->where('estado', 'pendiente')->count();
        $this->totalEnRevision  = DB::table('reclamaciones')->where('estado', 'en_revision')->count();
        $this->totalRespondidas = DB::table('reclamaciones')->where('estado', 'respondida')->count();
        $this->totalCerradas    = DB::table('reclamaciones')->where('estado', 'cerrada')->count();
    }

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingEstadoFilter(): void { $this->resetPage(); }
    public function updatingTipoFilter(): void   { $this->resetPage(); }

    public function openModal(int $id): void
    {
        $row = DB::table('reclamaciones')->where('id', $id)->first();
        if (!$row) return;

        $this->selected    = (array) $row;
        $this->respuesta   = $this->selected['respuesta'] ?? '';
        $this->nuevoEstado = $this->selected['estado'];
        $this->showModal   = true;
    }

    public function closeModal(): void
    {
        $this->showModal   = false;
        $this->selected    = null;
        $this->respuesta   = '';
        $this->nuevoEstado = '';
    }

    public function guardar(): void
    {
        $this->validate([
            'nuevoEstado' => 'required|in:pendiente,en_revision,respondida,cerrada',
            'respuesta'   => 'nullable|string|max:2000',
        ]);

        $data = [
            'estado'     => $this->nuevoEstado,
            'respuesta'  => $this->respuesta ?: null,
            'updated_at' => now(),
        ];

        // Marcar fecha de respuesta la primera vez
        if ($this->nuevoEstado === 'respondida' && empty($this->selected['respondida_at'])) {
            $data['respondida_at'] = now();
        }

        DB::table('reclamaciones')->where('id', $this->selected['id'])->update($data);

        // Notificar al reclamante por email si se respondió y hay texto
        if ($this->nuevoEstado === 'respondida' && !empty($this->respuesta)) {
            $this->notificarReclamante();
        }

        $this->refreshStats();
        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Reclamación actualizada correctamente.']);
    }

    private function notificarReclamante(): void
    {
        try {
            $rec      = $this->selected;
            $respuesta = $this->respuesta;

            $fromEmail = config('mail.from.address', 'incidencias@repuestofijo.com');
            $fromName  = config('mail.from.name', 'Repuesto Fijo');

            Mail::send([], [], function ($m) use ($rec, $respuesta, $fromEmail, $fromName) {
                $m->to($rec['email'], $rec['nombre'])
                  ->from($fromEmail, $fromName)
                  ->replyTo($fromEmail, $fromName)
                  ->subject("Respuesta a tu reclamación [{$rec['code']}] – Repuesto Fijo")
                  ->html(
                      "<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;color:#333;line-height:1.6;'>"
                    . "<div style='border-bottom:3px solid #ff3b5c;padding-bottom:12px;margin-bottom:20px;'>"
                    . "<h2 style='color:#132530;margin:0;'>Repuesto Fijo</h2>"
                    . "<p style='color:#666;font-size:13px;margin:4px 0 0;'>Atención de Reclamaciones</p>"
                    . "</div>"
                    . "<p>Estimado(a) <strong>" . e($rec['nombre']) . "</strong>,</p>"
                    . "<p>Hemos atendido y emitido resolución formal respecto a tu caso registrado con el código de seguimiento <strong>{$rec['code']}</strong>:</p>"
                    . "<div style='border-left:4px solid #00d68f;padding:16px 20px;background:#f8fafc;border-radius:0 8px 8px 0;margin:20px 0;'>"
                    . "<div style='font-size:12px;text-transform:uppercase;color:#64748b;font-weight:bold;margin-bottom:8px;'>Resolución / Respuesta oficial:</div>"
                    . "<div style='color:#1e293b;'>" . nl2br(e($respuesta)) . "</div>"
                    . "</div>"
                    . "<p>Si tienes alguna consulta adicional respecto a esta resolución, puedes responder directamente a este correo o escribirnos a "
                    . "<a href='mailto:{$fromEmail}' style='color:#ff3b5c;text-decoration:none;font-weight:600;'>{$fromEmail}</a>.</p>"
                    . "<hr style='border:none;border-top:1px solid #eee;margin:24px 0;'>"
                    . "<p style='font-size:12px;color:#999;margin:0;'>Atentamente,<br><strong>Equipo de Atención al Cliente — Repuesto Fijo</strong></p>"
                    . "</div>"
                  );
            });
        } catch (\Throwable $e) {
            Log::error('ReclamacionManagement@notificarReclamante: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->refreshStats();

        $rows = DB::table('reclamaciones')
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('nombre',     'like', "%{$this->search}%")
                          ->orWhere('email',    'like', "%{$this->search}%")
                          ->orWhere('code',     'like', "%{$this->search}%")
                          ->orWhere('num_pedido', 'like', "%{$this->search}%");
                });
            })
            ->when($this->estadoFilter, fn($q) => $q->where('estado', $this->estadoFilter))
            ->when($this->tipoFilter,   fn($q) => $q->where('tipo_reclamacion', $this->tipoFilter))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.reclamacion-management', [
            'rows'             => $rows,
            'totalPendientes'  => $this->totalPendientes,
            'totalEnRevision'  => $this->totalEnRevision,
            'totalRespondidas' => $this->totalRespondidas,
            'totalCerradas'    => $this->totalCerradas,
        ]);
    }
}
