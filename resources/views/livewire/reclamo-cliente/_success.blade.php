{{-- Pantalla de éxito tras crear el reclamo (Bootstrap 5 Dark Mode) --}}
<div class="p-4 py-5 text-center d-flex flex-column align-items-center">
    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
        style="width: 64px; height: 64px; background: rgba(34,197,94,0.15); color: #22c55e;">
        <i class="fas fa-check fs-2"></i>
    </div>

    <h4 class="fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Reclamo registrado</h4>
    <p class="text-white text-opacity-60 small mb-4">Te contactamos hoy al número verificado</p>

    <div class="w-100 p-3 rounded-4 text-start mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
        <div class="fw-bold text-warning" style="font-size: 1.05rem;">
            Reclamo #{{ $incidenciaCodigo }}
        </div>
        @if ($pedidoData)
            <div class="text-white text-opacity-50 small mt-1">
                Pedido #{{ $pedidoData['id'] }}
            </div>
        @endif
    </div>

    <p class="text-white text-opacity-40 small mb-4" style="font-size: 0.78rem; line-height: 1.4;">
        Nuestro equipo de soporte revisará la información y se comunicará contigo hoy mismo.
    </p>

    <button
        type="button"
        wire:click="cancel"
        class="btn btn-danger fw-bold rounded-pill w-100 py-2 shadow-none"
        style="font-size: 0.95rem; background: #dc2626;"
    >
        Cerrar ventana
    </button>
</div>
