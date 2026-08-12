@extends('layouts.app')

@section('content')
    <style>
        body, html {
            overflow: hidden !important;
            height: 100%;
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
            opacity: 1 !important;
        }

        /* For different browsers */
        input::-webkit-input-placeholder { color: rgba(255, 255, 255, 0.8) !important; }
        input::-moz-placeholder { color: rgba(255, 255, 255, 0.8) !important; }
        input:-ms-input-placeholder { color: rgba(255, 255, 255, 0.8) !important; }

        .login-logo {
            height: 60px;
            filter: drop-shadow(0 0 15px rgba(190,60,59,0.4));
            transition: all 0.3s ease;
        }

        .login-container {
            width: 25%;
            min-width: 400px;
        }

        @media (max-width: 768px) {
            .login-container {
                width: 90%;
                min-width: 300px;
                /* Add a slight top offset if needed, but flex-center is usually best */
                margin-top: -5vh; 
            }
            .login-logo {
                height: 35px;
            }
            .mb-5 {
                margin-bottom: 1.25rem !important;
            }
            .card-body {
                padding: 1.25rem !important;
            }
            .mt-5 {
                margin-top: 1.25rem !important;
            }
            .mt-4 {
                margin-top: 0.75rem !important;
            }
            .mt-3 {
                margin-top: 0.75rem !important;
            }
        }
    </style>

    <div class="d-flex flex-column justify-content-center align-items-center vh-100 m-0 overflow-hidden" style="background: transparent;">
        <div class="login-container">
            <!-- App Logo -->
            <div class="text-center mb-5">
                <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo Logo" class="login-logo">
            </div>

            <div class="card card-glass overflow-hidden shadow-lg border-opacity-10">
                <!-- Card Header -->
                <div class="p-3 p-md-4 border-bottom border-light border-opacity-10 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-1">
                        <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: var(--accent-red);">
                            <i class="fas fa-lock text-white extra-small" style="font-size: 0.65rem;"></i>
                        </div>
                        <h2 class="h5 mb-0 fw-bold text-white">Iniciar Sesión</h2>
                    </div>
                    <p class="extra-small text-white mb-0">Ingresa tus credenciales para continuar</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger rounded-4 p-2 text-center mb-3">
                            <ul class="mb-0 list-unstyled extra-small fw-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3 mb-md-4">
                            <label for="email" class="form-label extra-small text-white text-uppercase fw-bold ls-1">Correo Electrónico</label>
                            <input type="email" class="form-control bg-dark bg-opacity-25 border-light border-opacity-10 text-white rounded-3 py-2 py-md-3" id="email" name="email" required autofocus placeholder="nombre@ejemplo.com" style="border-color: rgba(255,255,255,0.1); background-color: rgba(0,0,0,0.2);">
                        </div>

                        <div class="mb-3 mb-md-4">
                            <label for="password" class="form-label extra-small text-white text-uppercase fw-bold ls-1">Contraseña</label>
                            <div class="position-relative">
                                <input type="password" class="form-control bg-dark bg-opacity-25 border-light border-opacity-10 text-white rounded-3 py-2 py-md-3 pe-5" id="password" name="password" required placeholder="********" style="border-color: rgba(255,255,255,0.1); background-color: rgba(0,0,0,0.2);">
                                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y text-white opacity-50 px-3 border-0 shadow-none hover-opacity-100" onclick="const p = document.getElementById('password'); const i = this.querySelector('i'); if (p.type === 'password') { p.type = 'text'; i.classList.replace('fa-eye', 'fa-eye-slash'); } else { p.type = 'password'; i.classList.replace('fa-eye-slash', 'fa-eye'); }">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mt-4 mt-md-5">
                            <button type="submit" class="btn btn-primary fw-bold py-2 py-md-3 rounded-3" style="background: linear-gradient(135deg, var(--accent-red), #8E2B2B); border: none; font-family: 'Syne', sans-serif;">
                                INGRESAR <i class="fas fa-arrow-right ms-2 small"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3 mt-md-4 text-center">
                <p class="extra-small text-white opacity-75" style="font-size: 0.7rem;">RepuestoFijo &copy; 2026 · Powered by ZettaThink</p>
            </div>
        </div>
    </div>
@endsection
