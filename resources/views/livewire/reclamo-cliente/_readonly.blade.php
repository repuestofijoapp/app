{{-- Vista de solo lectura de incidencia existente (Bootstrap 5 Dark Mode) --}}
<div class="p-4 d-flex flex-column gap-3" style="max-height: 75vh; overflow-y: auto;">
    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3" style="border-color: rgba(255,255,255,0.08) !important;">
        <div>
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Syne', sans-serif;">
                Reclamo #{{ $incidenciaData['codigo'] }}
            </h5>
            <div class="text-white text-opacity-50 small mt-1">
                {{ $incidenciaData['created_at'] }}
            </div>
        </div>
        <button type="button" wire:click="cancel" class="btn-close btn-close-white shadow-none" style="opacity: 0.6;"></button>
    </div>

    {{-- Estado actual --}}
    <div>
        <span class="badge px-3 py-2 rounded-pill fw-bold"
            style="font-size: 0.78rem; background: rgba(234, 179, 8, 0.15); color: #facc15; border: 1px solid rgba(234, 179, 8, 0.3);">
            {{ $incidenciaData['status_label'] }}
        </span>
    </div>

    {{-- Motivo --}}
    <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
        <div class="text-white text-opacity-40 small mb-1" style="font-size: 0.75rem;">Motivo del reporte</div>
        <div class="text-white fw-bold" style="font-size: 0.95rem;">
            {{ $incidenciaData['tipo_label'] }}
            @if ($incidenciaData['submotivo_label'])
                <span class="text-white text-opacity-60 fw-normal"> › {{ $incidenciaData['submotivo_label'] }}</span>
            @endif
        </div>
        @if ($incidenciaData['descripcion'])
            <div class="text-white text-opacity-70 small mt-2 pt-2 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                {{ $incidenciaData['descripcion'] }}
            </div>
        @endif
    </div>

    {{-- Foto adjunta --}}
    @if ($incidenciaData['foto_url'])
        <div>
            <div class="text-white text-opacity-50 small mb-2" style="font-size: 0.75rem;">Foto adjunta como evidencia</div>
            <img src="{{ $incidenciaData['foto_url'] }}" class="img-fluid rounded-3 w-100" style="max-height: 200px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);" alt="Evidencia">
        </div>
    @endif

    {{-- Historial de estados --}}
    @if (!empty($incidenciaData['historial_estados']))
        <div>
            <div class="text-white text-opacity-50 small mb-2" style="font-size: 0.75rem;">Historial de seguimiento</div>
            <div class="d-flex flex-column gap-2">
                @foreach (array_reverse($incidenciaData['historial_estados']) as $h)
                    <div class="d-flex align-items-start gap-2">
                        <div class="rounded-circle mt-1" style="width: 8px; height: 8px; background: #f97316; flex-shrink: 0;"></div>
                        <div>
                            <div class="text-white small fw-semibold">{{ $h['estado'] ?? '' }}</div>
                            <div class="text-white text-opacity-40" style="font-size: 0.7rem;">
                                {{ $h['fecha'] ?? '' }}{{ isset($h['por']) ? ' · ' . $h['por'] : '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Nota de resolución --}}
    @if ($incidenciaData['nota_resolucion'])
        <div class="p-3 rounded-4" style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25);">
            <div class="text-success small fw-bold mb-1">Nota de resolución del soporte</div>
            <div class="text-white small">{{ $incidenciaData['nota_resolucion'] }}</div>
        </div>
    @endif

    <div class="pt-2">
        <button type="button" wire:click="cancel" class="btn btn-outline-light border-0 rounded-pill w-100 py-2" style="background: rgba(255,255,255,0.06); font-size: 0.88rem;">
            Cerrar
        </button>
    </div>
</div>
