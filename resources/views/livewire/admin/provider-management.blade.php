<div class="container-fluid">
    <style>
        .provider-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .provider-card {
                padding: 1rem;
            }

            .h2 {
                font-size: 1.5rem;
            }
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-custom th {
            color: var(--muted);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .table-custom tr td {
            background: var(--surface2);
            padding: 1.25rem 1rem;
            color: #fff;
            transition: all 0.3s;
        }

        .table-custom tr.inactive td {
            opacity: 0.6;
        }

        .table-custom tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table-custom tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        .btn-add {
            background: var(--accent-red);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--accent-red-glow);
        }

        .search-input {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fff;
            max-width: 350px;
            width: 100%;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-red);
        }

        .search-input::placeholder {
            color: #fff;
            opacity: 0.6;
        }

        .per-page-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: #fff;
            outline: none;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content-custom {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            padding: 2.5rem;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 2px var(--accent-red-glow);
        }

        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Acciones */
        .btn-action {
            background: var(--surface3);
            border: 1px solid var(--border);
            padding: 10px;
            border-radius: 10px;
            transition: 0.2s;
            cursor: pointer;
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-action-edit {
            color: #3b82f6;
        }

        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }

        .btn-action-delete {
            color: var(--red);
        }

        .btn-action-delete:hover {
            background: rgba(255, 59, 92, 0.1);
            border-color: var(--red);
        }

        .btn-action-active {
            color: #00d68f;
        }

        .btn-action-inactive {
            color: var(--muted);
        }

        .section-title {
            color: var(--accent-red);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(190, 60, 59, 0.2);
        }

        @media (max-width: 768px) {
            .table-custom thead {
                display: none;
            }

            .table-custom,
            .table-custom tbody,
            .table-custom tr,
            .table-custom td {
                display: block;
                width: 100%;
            }

            .table-custom tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border);
                border-radius: 15px;
                background: var(--surface2);
                padding: 0.5rem 0;
            }

            .table-custom td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: none !important;
                padding: 0.6rem 1rem !important;
                background: transparent !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            }

            .table-custom td:last-child {
                border-bottom: none !important;
            }

            .table-custom td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.7rem;
                color: var(--muted);
                text-transform: uppercase;
                font-family: 'Syne', sans-serif;
            }
        }
        /* Autocomplete Styles */
        .autocomplete-wrapper {
            position: relative;
        }
        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-top: 5px;
            z-index: 3000;
            max-height: 250px;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            backdrop-filter: blur(15px);
        }
        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            color: #fff;
            transition: all 0.2s;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .autocomplete-item:last-child { border-bottom: none; }
        .autocomplete-item:hover {
            background: var(--accent-red);
            padding-left: 20px;
        }
    </style>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="h2 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">
                Gestión de Proveedores</h1>
            <p class="text-white text-sm">Administra la información de los proveedores y sus accesos.</p>
        </div>
        <button wire:click="openModal" class="btn-add w-full md:w-auto">
            <i class="fas fa-plus"></i>
            Nuevo Proveedor
        </button>
    </div>

    <div class="provider-card mt-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 pb-4">
            <input type="text" wire:model.live.debounce.300ms="search" class="search-input"
                placeholder="Buscar por nombre, RUC, ciudad...">

            <div class="text-end">
                <span>Mostrar</span>
                <select wire:model.live="perPage" class="per-page-select">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>registros</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Razón Social</th>
                        <th>WhatsApp / Tel.</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $provider)
                        <tr class="{{ !$provider->is_active ? 'inactive' : '' }}">
                            <td data-label="Razón Social">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $provider->business_name }}</span>
                                    <span class="text-warning" style="font-size: 0.75rem;">RUC:
                                        {{ $provider->ruc ?? 'No registrado' }}</span>
                                </div>
                            </td>
                            <td data-label="WhatsApp / Tel.">
                                <div class="d-flex flex-column">
                                    <span class="text-white"><i class="fab fa-whatsapp text-success mr-1"></i>
                                        {{ $provider->whatsapp_number }}</span>
                                    <small class="text-warning">{{ $provider->phone }}</small>
                                </div>
                            </td>
                            <td data-label="Ubicación">
                                <span class="text-white">{{ $provider->city }}, {{ $provider->country }}</span>
                            </td>
                            <td data-label="Estado">
                                @if($provider->is_active)
                                    <span class="text-green-400 text-xs font-bold"><i class="fas fa-check-circle mr-1"></i>
                                        Activo</span>
                                @else
                                    <span class="text-red-400 text-xs font-bold"><i class="fas fa-times-circle mr-1"></i>
                                        Inactivo</span>
                                @endif
                            </td>
                            <td data-label="Acciones" class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="editProvider({{ $provider->id }})" title="Editar"
                                        class="btn-action btn-action-edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button wire:click="toggleStatus({{ $provider->id }})"
                                        title="{{ $provider->is_active ? 'Desactivar' : 'Activar' }}"
                                        class="btn-action {{ $provider->is_active ? 'btn-action-inactive' : 'btn-action-active' }}">
                                        <i class="fas {{ $provider->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $provider->id }})" title="Eliminar"
                                        class="btn-action btn-action-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-8">
                                No se encontraron proveedores.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $providers->links() }}
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal-overlay">
            <div class="modal-content-custom">
                <h2 class="h4 fw-bold mb-4 text-white" style="font-family: 'Syne', sans-serif;">
                    {{ $editingProvider ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                </h2>

                <form wire:submit.prevent="saveProvider">
                    <div class="row">
                        {{-- Información General --}}
                        <div class="col-12">
                            <div class="section-title mt-0">Identificación y Especialidad</div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre / Razón Social</label>
                            <input type="text" wire:model="business_name" class="form-input"
                                placeholder="Ej: Repuestos El Chino S.A.C.">
                            @error('business_name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">RUC</label>
                            <input type="text" wire:model="ruc" class="form-input" placeholder="20123456789">
                            @error('ruc') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Especialidad (Marcas o tipos de repuestos)</label>
                            <input type="text" wire:model="specialty" class="form-input"
                                placeholder="Ej: Toyota, Nissan, Motores, Suspensión...">
                            @error('specialty') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contacto --}}
                        <div class="col-12">
                            <div class="section-title">Información de Contacto</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">WhatsApp (Leads)</label>
                            <input type="text" wire:model="whatsapp_number" class="form-input" placeholder="+51 987654321">
                            @error('whatsapp_number') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono Fijo / Central</label>
                            <input type="text" wire:model="phone" class="form-input" placeholder="01 2345678">
                            @error('phone') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Email de Contacto (Atención / Notificaciones)</label>
                            <input type="email" wire:model="contact_email" class="form-input"
                                placeholder="ventas@proveedor.com">
                            @error('contact_email') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Ubicación --}}
                        <div class="col-12">
                            <div class="section-title">Ubicación y Pagos</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" wire:model="address" class="form-input" placeholder="Av. Principal 123">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ciudad</label>
                            <div class="autocomplete-wrapper" x-data="{ open: false }" @click.away="open = false">
                                <input type="text" wire:model.live.debounce.150ms="city" class="form-input"
                                    placeholder="Escriba para buscar ciudad..." @focus="open = true" @input="open = true"
                                    autocomplete="off">

                                @if(!empty($cityResults))
                                    <div class="autocomplete-dropdown" x-show="open" x-transition>
                                        @foreach($cityResults as $result)
                                            <div class="autocomplete-item" @click="open = false"
                                                wire:click="selectCity('{{ $result }}')">
                                                <i class="fas fa-map-marker-alt mr-2 text-warning" style="font-size: 0.8rem;"></i>
                                                {{ $result }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('city') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">País</label>
                            <input type="text" wire:model="country" class="form-input" placeholder="Perú">
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Número de Cuenta Bancaria</label>
                            <input type="text" wire:model="bank_account_number" class="form-input"
                                placeholder="BCP Soles: 191-...">
                        </div>

                        @if($editingProvider)
                        <div class="col-12">
                            <div class="section-title" style="color: #3b82f6; border-color: #3b82f6;">
                                <i class="fas fa-key me-2"></i>Acceso Portal B2B
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Nueva Contraseña del Portal B2B</label>
                            <input type="text" wire:model="new_portal_password" class="form-input"
                                placeholder="Dejar vacío para no cambiar la contraseña actual">
                            <div style="margin-top:8px; padding:10px 14px; background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2); border-radius:8px; font-size:0.82rem; color:#94a3b8; line-height:1.5;">
                                El proveedor accede al portal <strong style="color:#fff;">/b2b/acceso</strong> con su <strong style="color:#fff;">RUC</strong> y esta contraseña. Si dejas este campo vacío, la contraseña actual NO cambia.
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
                        <button type="button" wire:click="closeModal"
                            class="btn btn-outline-secondary flex-1 rounded-xl w-50"
                            style="height: 50px; border-radius: 12px; font-weight: 700; color: #fff; border: 1px solid var(--border);">Cancelar</button>
                        <button type="submit" class="btn-add flex-1 w-50"
                            style="height: 50px;">{{ $editingProvider ? 'Guardar Cambios' : 'Crear Proveedor' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- SweetAlert Integration --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:confirm-delete-provider', (data) => {
                const info = data[0];
                Swal.fire({
                    title: info.title,
                    text: info.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF3B5C',
                    cancelButtonColor: '#6B7A99',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    background: '#111827',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete-provider-confirmed', { id: info.id });
                    }
                });
            });
        });
    </script>
</div>