{{-- Pantalla de éxito tras crear el reclamo --}}
<div class="px-5 py-8 flex flex-col items-center text-center gap-4">
    <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center">
        <svg class="w-8 h-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <div>
        <h3 class="text-white font-bold text-lg">Reclamo registrado</h3>
        <p class="text-gray-400 text-sm mt-1">Te contactamos hoy al número verificado</p>
    </div>

    <div class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-left">
        <p class="text-orange-400 font-bold text-sm">{{ $incidenciaCodigo }}</p>
        @if ($pedidoData)
            <p class="text-gray-400 text-xs mt-0.5">Pedido #{{ $pedidoData['id'] }}</p>
        @endif
    </div>

    <p class="text-gray-500 text-xs leading-relaxed">
        Nuestro equipo de soporte revisará tu caso y se comunicará contigo a la brevedad.
    </p>

    <button
        wire:click="cancel"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl text-sm transition-colors mt-2"
    >
        Cerrar ventana
    </button>
</div>

