@extends('layouts.app')

@section('title', 'Dashboard - RepuestoFijo')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-house-door"></i>
                            Dashboard Principal
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Bienvenido al sistema RepuestoFijo. Tu rol actual:
                            <strong>{{ auth()->user()->role->value }}</strong>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-tools fs-1 text-primary mb-3"></i>
                                        <h5>Panel Mecánico</h5>
                                        <p>Accede a tu panel de reparaciones</p>
                                        <a href="{{ route('mechanic.dashboard') }}" class="btn btn-primary">Ir al Panel</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-truck fs-1 text-info mb-3"></i>
                                        <h5>Panel Transporte</h5>
                                        <p>Gestiona tus entregas y rutas</p>
                                        <a href="{{ route('home') }}" class="btn btn-info">Ir al Panel</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-shield-check fs-1 text-warning mb-3"></i>
                                        <h5>Panel Admin</h5>
                                        <p>Administración del sistema</p>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Ir al Panel</a>
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