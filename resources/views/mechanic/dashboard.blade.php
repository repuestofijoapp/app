@extends('layouts.app')

@section('title', 'Panel de Mecánico - RepuestoFijo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-tools"></i>
                        Panel de Mecánico
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Vehicle Search Component -->
                            <div class="mb-4">
                                <h5 class="mb-3">Buscar Vehículo</h5>
                                @livewire('vehicle-search')
                            </div>

                            <!-- Category Selection -->
                            <div class="mt-4">
                                <h5 class="mb-3">Seleccionar Categoría de Reparación</h5>
                                @livewire('category-grid')
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Quick Stats -->
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-bar-chart"></i>
                                        Estadísticas Rápidas
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Órdenes Activas:</span>
                                        <span class="badge bg-warning">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Órdenes Completadas:</span>
                                        <span class="badge bg-success">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Gastado:</span>
                                        <span class="fw-bold">S/ 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-clock-history"></i>
                                        Actividad Reciente
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mt-2">No hay actividad reciente</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection