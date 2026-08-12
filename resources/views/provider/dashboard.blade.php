@extends('layouts.app')

@section('title', 'Panel de Proveedor - RepuestoFijo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-shop"></i>
                        Panel de Proveedor
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        Bienvenido a tu panel de proveedor. Aquí podrás gestionar tus productos y responder a cotizaciones.
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="bi bi-box-seam fs-1 text-primary mb-3"></i>
                                    <h5>Mis Productos</h5>
                                    <p>Gestiona tu catálogo de productos</p>
                                    <button class="btn btn-outline-primary" disabled>Próximamente</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="bi bi-chat-dots fs-1 text-success mb-3"></i>
                                    <h5>Cotizaciones</h5>
                                    <p>Responde a solicitudes de cotización</p>
                                    <button class="btn btn-outline-success" disabled>Próximamente</button>
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