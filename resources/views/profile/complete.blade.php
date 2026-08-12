@extends('layouts.app')

@section('title', 'Completar Perfil - RepuestoFijo')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-person-check"></i>
                        Completar Información de Perfil
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Para acceder a todas las funcionalidades, necesitamos completar tu información comercial.
                    </div>

                    <form method="POST" action="#">
                        @csrf

                        <div class="mb-3">
                            <label for="ruc_dni" class="form-label">DNI/RUC *</label>
                            <input type="text" class="form-control" id="ruc_dni" name="ruc_dni" required>
                        </div>

                        @if(auth()->user()->isMechanic())
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nombre del Negocio *</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="ciiu_code" class="form-label">Código CIIU</label>
                            <input type="text" class="form-control" id="ciiu_code" name="ciiu_code">
                            <div class="form-text">Código de actividad económica (opcional)</div>
                        </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                Guardar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection