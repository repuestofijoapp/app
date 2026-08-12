<div>
    <div class="category-grid-container">
        @if($vehicleId)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-grid-3x3-gap"></i>
                        Seleccionar Categoría de Reparación
                    </h5>
                    @if($selectedCategory)
                        <button class="btn btn-outline-secondary btn-sm" wire:click="clearSelection">
                            <i class="bi bi-arrow-left"></i>
                            Cambiar Categoría
                        </button>
                    @endif
                </div>

                <!-- Buscador de categorías -->
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar categoría..."
                            wire:model.live.debounce.300ms="searchTerm"
                        >
                    </div>
                </div>

                @if(!$selectedCategory)
                    <!-- Parrilla de categorías principales -->
                    <div class="row g-3">
                        @foreach($this->parentCategories as $category)
                            <div class="col-md-6 col-lg-4">
                                <div
                                    class="category-card card h-100 border-0 shadow-sm"
                                    style="cursor: pointer; transition: all 0.3s ease;"
                                    wire:click="selectCategory({{ $category->id }})"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)';"
                                >
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="{{ $category->icon }} fs-1 text-primary"></i>
                                        </div>
                                        <h6 class="card-title fw-bold mb-2">{{ $category->name }}</h6>
                                        <small class="text-muted">
                                            {{ $category->children->count() }} subcategorías
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($this->parentCategories->isEmpty() && !empty($searchTerm))
                        <div class="text-center py-5">
                            <i class="bi bi-search fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">No se encontraron categorías</h6>
                            <p class="text-muted small">Intenta con otros términos de búsqueda</p>
                        </div>
                    @endif

                @else
                    <!-- Vista de subcategorías -->
                    <div class="selected-category-header bg-light p-3 rounded mb-4">
                        <div class="d-flex align-items-center">
                            <i class="{{ $selectedCategory->icon }} fs-3 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $selectedCategory->name }}</h6>
                                <small class="text-muted">Selecciona una subcategoría específica</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($selectedCategory->children as $subcategory)
                            <div class="col-md-6 col-lg-4">
                                <div
                                    class="subcategory-card card h-100 border"
                                    style="cursor: pointer; transition: all 0.2s ease;"
                                    onmouseover="this.style.borderColor='#0d6efd';"
                                    onmouseout="this.style.borderColor='#dee2e6';"
                                >
                                    <div class="card-body text-center p-3">
                                        <div class="mb-2">
                                            <i class="{{ $subcategory->icon }} fs-4 text-secondary"></i>
                                        </div>
                                        <h6 class="card-title small fw-semibold mb-1">{{ $subcategory->name }}</h6>
                                        <div class="d-grid">
                                            <button class="btn btn-outline-primary btn-sm">
                                                Seleccionar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Información del vehículo seleccionado -->
            @php
                $vehicle = \App\Models\Vehicle::find($vehicleId);
            @endphp
            @if($vehicle)
                <div class="vehicle-info bg-info bg-opacity-10 border border-info p-3 rounded">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-car-front fs-4 text-info me-3"></i>
                        <div>
                            <small class="text-muted">Vehículo seleccionado</small>
                            <div class="fw-semibold">
                                {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                            </div>
                            <small class="text-muted">Placa: {{ $vehicle->plate }}</small>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Estado inicial: esperando selección de vehículo -->
            <div class="text-center py-5">
                <i class="bi bi-car-front fs-1 text-muted mb-3"></i>
                <h6 class="text-muted">Selecciona un vehículo primero</h6>
                <p class="text-muted small">Busca por placa o código OEM para comenzar</p>
            </div>
        @endif
    </div>

    <style>
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    </style>
</div>