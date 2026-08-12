<div class="vehicle-search-container">
    <div class="mb-3">
        <label for="vehicle-search" class="form-label fw-bold">Buscar Vehículo</label>
        <div class="input-group">
            <input
                type="text"
                id="vehicle-search"
                class="form-control form-control-lg"
                placeholder="Ingresa placa (ABC-123) o código OEM..."
                wire:model.live.debounce.300ms="search"
                wire:loading.attr="disabled"
            >
            <button
                class="btn btn-outline-secondary"
                type="button"
                wire:click="clearSearch"
                @if(empty($search)) disabled @endif
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Search Type Indicator -->
        @if($search && $searchType !== 'auto')
            <div class="mt-1">
                <small class="text-muted">
                    Detectado como:
                    @if($searchType === 'plate')
                        <span class="badge bg-primary">Placa</span>
                    @elseif($searchType === 'oem')
                        <span class="badge bg-success">Código OEM</span>
                    @endif
                </small>
            </div>
        @endif

        <!-- Loading Indicator -->
        <div wire:loading wire:target="search" class="mt-2">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <small class="text-muted ms-2">Buscando vehículo...</small>
        </div>
    </div>

    <!-- Search Results -->
    @if(!empty($searchResults))
        <div class="search-results mt-3">
            <h6 class="fw-bold">Resultados de búsqueda:</h6>
            <div class="list-group">
                @foreach($searchResults as $result)
                    <button
                        type="button"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        wire:click="selectVehicle({{ $result->id }})"
                    >
                        <div>
                            <div class="fw-bold">{{ $result->plate }}</div>
                            <small class="text-muted">
                                {{ $result->brand }} {{ $result->model }} ({{ $result->year }})
                            </small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                @endforeach
            </div>
        </div>
    @elseif(strlen($search) >= 3 && empty($searchResults) && !$isSearching)
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i>
            No se encontraron resultados para "{{ $search }}"
            @if($searchType === 'plate')
                <br><small>¿Es una placa nueva? Se consultará la API externa.</small>
            @endif
        </div>
    @endif

    <!-- Selected Vehicle Display -->
    @if($vehicle)
        <div class="selected-vehicle mt-4 p-3 border rounded bg-light">
            <h6 class="fw-bold text-success mb-2">
                <i class="bi bi-check-circle-fill"></i>
                Vehículo Seleccionado
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Placa:</strong> {{ $vehicle->plate }}</p>
                    <p class="mb-1"><strong>VIN:</strong> {{ $vehicle->vin ?: 'No disponible' }}</p>
                    <p class="mb-1"><strong>Motor:</strong> {{ $vehicle->engine_code ?: 'No disponible' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Marca:</strong> {{ $vehicle->brand }}</p>
                    <p class="mb-1"><strong>Modelo:</strong> {{ $vehicle->model }}</p>
                    <p class="mb-1"><strong>Año:</strong> {{ $vehicle->year }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Validation Errors -->
    @error('search')
        <div class="alert alert-danger mt-2">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $message }}
        </div>
    @enderror
</div>