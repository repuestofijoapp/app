@extends('layouts.app')

@section('title', 'Bienvenido a RepuestoFijo')

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-tools display-1 text-primary"></i>
                        </div>

                        <h1 class="h2 fw-bold text-dark mb-3">Bienvenido a RepuestoFijo</h1>

                        <p class="text-muted mb-4 lead">
                            El marketplace logístico B2B para el mercado automotriz peruano.
                            Centraliza la oferta de importadores especializados.
                        </p>

                        <div class="d-grid gap-3">
                            <a href="{{ route('google.auth') }}"
                                class="btn btn-danger btn-lg d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-google"></i>
                                <span>Iniciar sesión con Google</span>
                            </a>

                            <div class="border-top pt-3">
                                <p class="text-muted small mb-2 text-center">Acceso de prueba (desarrollo)</p>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <a href="{{ route('test.login', 'mechanic') }}"
                                            class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-tools"></i>
                                            Login Mecánico
                                        </a>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ route('test.login', 'transporte') }}"
                                            class="btn btn-outline-info btn-sm w-100">
                                            <i class="bi bi-truck"></i>
                                            Login Transporte
                                        </a>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ route('test.login', 'admin') }}"
                                            class="btn btn-outline-warning btn-sm w-100">
                                            <i class="bi bi-shield-check"></i>
                                            Login Admin
                                        </a>
                                    </div>
                                </div>
                                <p class="text-muted small mt-2 text-center">
                                    <strong>Password:</strong> 123456
                                </p>
                            </div>

                            <p class="text-muted small mb-0">
                                Plataforma especializada para mecánicos y proveedores de repuestos automotrices
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection