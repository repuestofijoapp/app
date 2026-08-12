<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Configuración del Sistema
            </h1>
            <p class="text-white text-sm opacity-75">Ajustes globales y preferencias de la plataforma.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert"
            style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399;">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card bg-surface border-secondary border-opacity-25 shadow-sm rounded-4 h-100"
                style="background-color: var(--surface);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2"
                        style="font-family: 'Syne', sans-serif;">
                        <i class="fas fa-search text-primary"></i>
                        Módulo de Búsqueda
                    </h5>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                        style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);">
                        <div>
                            <h6 class="text-white fw-bold mb-1">Búsqueda por Placa</h6>
                            <p class="text-white small mb-0">Habilita o deshabilita la búsqueda de modelos de auto por
                                número de placa en la vista pública.</p>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0 ms-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="plateSearchSwitch"
                                wire:click="togglePlateSearch" {{ $enable_plate_search ? 'checked' : '' }}
                                style="cursor: pointer; {{ $enable_plate_search ? 'background-color: var(--accent-red); border-color: var(--accent-red);' : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>