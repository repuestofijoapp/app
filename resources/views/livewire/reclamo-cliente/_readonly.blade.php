{{-- Vista de solo lectura de incidencia existente --}}
<div class="px-5 py-5 flex flex-col gap-4 overflow-y-auto max-h-[75dvh]">
    {{-- Cabecera --}}
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-white font-bold text-base">Reclamo {{ $incidenciaData['codigo'] }}</h2>
            <p class="text-gray-500 text-xs mt-0.5">{{ $incidenciaData['created_at'] }}</p>
        </div>
        <button wire:click="cancel" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Estado actual --}}
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $incidenciaData['status_color'] }}-500/20 text-{{ $incidenciaData['status_color'] }}-400 border border-{{ $incidenciaData['status_color'] }}-500/30">
            {{ $incidenciaData['status_label'] }}
        </span>
    </div>

    {{-- Motivo --}}
    <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 space-y-1">
        <p class="text-gray-400 text-xs">Motivo</p>
        <p class="text-white text-sm font-medium">{{ $incidenciaData['tipo_label'] }}
            @if ($incidenciaData['submotivo_label'])
                <span class="text-gray-400 font-normal"> › {{ $incidenciaData['submotivo_label'] }}</span>
            @endif
        </p>
        @if ($incidenciaData['descripcion'])
            <p class="text-gray-400 text-xs mt-1 pt-1 border-t border-white/10">{{ $incidenciaData['descripcion'] }}</p>
        @endif
    </div>

    {{-- Foto adjunta --}}
    @if ($incidenciaData['foto_url'])
        <div>
            <p class="text-gray-400 text-xs mb-2">Foto adjunta</p>
            <img src="{{ $incidenciaData['foto_url'] }}" class="w-full max-h-48 object-cover rounded-xl border border-white/10" alt="Evidencia">
        </div>
    @endif

    {{-- Historial de estados --}}
    @if (!empty($incidenciaData['historial_estados']))
        <div>
            <p class="text-gray-400 text-xs mb-2">Historial</p>
            <div class="space-y-2">
                @foreach (array_reverse($incidenciaData['historial_estados']) as $h)
                    <div class="flex gap-3 items-start">
                        <div class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-white text-xs font-medium">{{ $h['estado'] ?? '' }}</p>
                            <p class="text-gray-500 text-[11px]">{{ $h['fecha'] ?? '' }}{{ isset($h['por']) ? ' · ' . $h['por'] : '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Nota de resolución --}}
    @if ($incidenciaData['nota_resolucion'])
        <div class="bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3">
            <p class="text-green-400 text-xs font-semibold mb-1">Nota del equipo de soporte</p>
            <p class="text-gray-300 text-xs">{{ $incidenciaData['nota_resolucion'] }}</p>
        </div>
    @endif

    <button wire:click="cancel" class="w-full bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-medium py-3 rounded-xl transition-colors mt-2">
        Cerrar
    </button>
</div>

