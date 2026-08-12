<div class="container-fluid">
    <style>
        .user-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .user-card {
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

        .role-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .role-admin {
            background: rgba(190, 60, 59, 0.2);
            color: var(--accent-red);
            border: 1px solid rgba(190, 60, 59, 0.3);
        }

        .role-manager {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .role-user {
            background: rgba(0, 214, 143, 0.2);
            color: #00d68f;
            border: 1px solid rgba(0, 214, 143, 0.3);
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
            opacity: 0.6
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
            max-width: 500px;
            padding: 2.5rem;
            position: relative;
        }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fff;
            margin-bottom: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 2px var(--accent-red-glow);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--muted);
            font-size: 0.85rem;
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

        .btn-action-block {
            color: var(--muted);
        }

        .btn-action-block:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-action-unblock {
            color: #00d68f;
        }

        .btn-action-unblock:hover {
            background: rgba(0, 214, 143, 0.1);
            border-color: #00d68f;
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

            .table-custom tr td:first-child,
            .table-custom tr td:last-child {
                border-radius: 0;
            }

            .btn-action {
                width: 48px;
                height: 48px;
                font-size: 1.1rem;
            }

            .flex-mobile-column {
                flex-direction: column;
                align-items: stretch !important;
                gap: 1rem;
            }
        }
    </style>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="h2 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">
                Gestión de Usuarios</h1>
            <p class="text-white text-sm">Administra los accesos y permisos del sistema.</p>
        </div>
        <button wire:click="openModal" class="btn-add w-full md:w-auto">
            <i class="fas fa-plus"></i>
            Nuevo Usuario
        </button>
    </div>

    <div class="user-card mt-4">

        {{-- Search & Pagination Limit Toolbar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 pb-4">

            <input type="text" wire:model.live.debounce.300ms="search" class="search-input"
                placeholder="Buscar usuario o email...">

            <!-- <div class="d-flex justify-content-between align-items-center"> -->
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
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Documento</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Bloqueado por</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ !$user->is_active ? 'inactive' : '' }}">
                            <td data-label="Usuario">
                                <span class="fw-bold">{{ $user->name }}</span>
                            </td>
                            <td data-label="Email" class="text-white">{{ $user->email }}</td>
                            <td data-label="Teléfono" class="text-white">
                                @if($user->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank"
                                        class="text-success text-decoration-none">
                                        <i class="fab fa-whatsapp mr-1"></i> {{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-white-50 small">No registrado</span>
                                @endif
                            </td>
                            <td data-label="Documento">
                                @if($user->ruc_dni || $user->business_name)
                                    <div class="d-flex flex-column gap-1">
                                        @if($user->ruc_dni)
                                            <div>
                                                <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $user->getDocumentLabel() }}:</span>
                                                <span class="text-white fw-medium">{{ $user->ruc_dni }}</span>
                                            </div>
                                        @endif
                                        @if($user->business_name)
                                            <div>
                                                <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem;">R. Social:</span>
                                                <span class="text-white small">{{ $user->business_name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-white-50 small">—</span>
                                @endif
                            </td>
                            <td data-label="Rol">
                                @if($user->isAdmin())
                                    <span class="role-badge role-admin">Admin</span>
                                @elseif($user->isManager())
                                    <span class="role-badge role-manager">Gestor</span>
                                @elseif($user->isTransporte())
                                    <span class="role-badge" style="background: rgba(147, 51, 234, 0.2); color: #a855f7; border: 1px solid rgba(147, 51, 234, 0.3);">Transporte</span>
                                @else
                                    <div class="d-flex flex-column gap-1">
                                        <span class="role-badge role-user">{{ $user->getRoleLabel() }}</span>
                                        @php $addrCount = count($user->getSavedAddresses()); @endphp
                                        @if($addrCount > 0)
                                            <div class="mt-1">
                                                <button wire:click="openAddressModal({{ $user->id }})"
                                                    class="btn p-0 border-0 text-accent-red d-flex align-items-center gap-1"
                                                    title="Ver {{ $addrCount }} direcciones guardadas" style="background:transparent; font-size: 0.7rem;">
                                                    <i class="fas fa-map-marked-alt"></i>
                                                    <span class="badge rounded-pill bg-danger"
                                                        style="font-size: 0.6rem; padding: 2px 5px;">{{ $addrCount }}</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td data-label="Estado" class="text-center">
                                @if($user->is_active)
                                    <span class="text-green-400 text-xs font-bold">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                @else
                                    <span class="text-red-400 text-xs font-bold">
                                        <i class="fas fa-ban mr-1"></i> Bloqueado
                                    </span>
                                @endif
                            </td>
                            <td data-label="Bloqueado por">
                                @if(!$user->is_active && $user->blocked_at)
                                    <div class="d-flex flex-column" style="font-size: 0.75rem;">
                                        <span class="text-white fw-bold">{{ $user->blockedBy->name ?? 'Sistema' }}</span>
                                        <span class="text-white-50">{{ $user->blocked_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @else
                                    <span class="text-white-50 small">—</span>
                                @endif
                            </td>
                            <td data-label="Acciones" class="text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$user->isAdmin())
                                        <button wire:click="editUser({{ $user->id }})" title="Editar"
                                            class="btn-action btn-action-edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button wire:click="confirmBlock({{ $user->id }})"
                                            title="{{ $user->is_active ? 'Bloquear' : 'Desbloquear' }}"
                                            class="btn-action {{ $user->is_active ? 'btn-action-block' : 'btn-action-unblock' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-unlock' }}"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})" title="Eliminar"
                                            class="btn-action btn-action-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <span class="text-muted text-xs italic">Solo lectura</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-8">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links('vendor.pagination.custom-repuestofijo') }}
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal-overlay">
            <div class="modal-content-custom">
                <h2 class="h4 fw-bold mb-6 text-white" style="font-family: 'Syne', sans-serif;">
                    {{ $editingUser ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                </h2>

                <form wire:submit.prevent="saveUser">
                    <div class="mb-4">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" wire:model="name" class="form-input" placeholder="Nombre del usuario">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" wire:model="email" class="form-input" placeholder="email@ejemplo.com">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ $editingUser ? 'Nueva Contraseña (Opcional)' : 'Contraseña' }}</label>
                        <input type="password" wire:model="password" class="form-input" placeholder="••••••••">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 border-top border-white border-opacity-5 mt-4 mb-4">
                        <h6 class="text-white-50 small fw-bold text-uppercase ls-1 mb-3">Datos Fiscales</h6>
                        
                        <div class="mb-4">
                            <label class="form-label">Número de Documento (RUC/DNI)</label>
                            <input type="text" wire:model="ruc_dni" class="form-input" placeholder="8 o 11 dígitos">
                            @error('ruc_dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Razón Social</label>
                            <input type="text" wire:model="business_name" class="form-input" placeholder="Nombre legal de la empresa">
                            @error('business_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="form-label">Rol del Usuario</label>
                        <select wire:model="role" class="form-input">
                            @if(auth()->user()->isAdmin())
                                <option value="admin">Administrador</option>
                                <option value="manager">Gestor</option>
                            @endif
                            <option value="mechanic">Mecánico Particular</option>
                            <option value="workshop">Taller Automotriz</option>
                            <option value="store">Tienda de Repuestos</option>
                            <option value="transporte">Transporte</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <button type="button" wire:click="closeModal"
                            class="btn btn-outline-secondary flex-1 rounded-xl w-50"
                            style="height: 50px; border-radius: 12px; font-weight: 700; color: #fff; border: 1px solid var(--border);">Cancelar</button>
                        <button type="submit" class="btn-add flex-1 w-50"
                            style="height: 50px;">{{ $editingUser ? 'Guardar Cambios' : 'Crear Usuario' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Address Viewer Modal --}}
    @if($showAddressModal)
        <div class="modal-overlay" style="z-index: 2100;">
            <div class="modal-content-custom" style="max-width: 650px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="h4 fw-bold text-white mb-0" style="font-family: 'Syne', sans-serif;">Direcciones de
                        {{ $selectedUserName }}
                    </h3>
                    <button wire:click="closeAddressModal" class="btn p-0 text-white"
                        style="font-size: 1.5rem;">&times;</button>
                </div>

                <div class="overflow-hidden rounded-xl border border-white-10">
                    @forelse($selectedUserAddresses as $idx => $addr)
                        <div class="p-3 {{ !$loop->last ? 'border-bottom border-white-10' : '' }}"
                            style="background: rgba(255,255,255,0.02);">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 32px; height: 32px; background: {{ $addr['type'] === 'lima' ? 'rgba(0,214,143,0.1)' : 'rgba(59,130,246,0.1)' }};">
                                        <i class="fas {{ $addr['type'] === 'lima' ? 'fa-motorcycle' : 'fa-bus' }} {{ $addr['type'] === 'lima' ? 'text-green-400' : 'text-blue-400' }}"
                                            style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold small text-uppercase ls-1">{{ $addr['label'] }}</div>
                                        @if($addr['type'] === 'lima')
                                            <div class="small text-white-50 mt-1">{{ $addr['address'] }}, {{ $addr['district'] }}
                                            </div>
                                        @else
                                            <div class="small text-white-50 mt-1">
                                                Agencia: {{ $addr['agency'] }} · Ciudad: {{ $addr['city'] }}<br>
                                                <span class="x-small">Consignado: {{ $addr['recipient_name'] }}
                                                    ({{ $addr['recipient_dni'] }})</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($idx === 0)
                                    <span class="badge bg-danger x-small text-uppercase"
                                        style="font-size: 0.55rem; padding: 3px 7px;">Reciente</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">No hay direcciones guardadas.</div>
                    @endforelse
                </div>

                <div class="mt-4 text-center">
                    <button type="button" wire:click="closeAddressModal" class="btn-add w-100"
                        style="background:rgba(255,255,255,0.05); border: 1px solid var(--border);">Cerrar Panel</button>
                </div>
            </div>
        </div>
    @endif

    {{-- SweetAlert Integration --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Init tooltips
            const initTooltips = () => {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    if (!el._tooltipInstance) {
                        el._tooltipInstance = new bootstrap.Tooltip(el, { trigger: 'hover' });
                    }
                });
            };
            initTooltips();
            document.addEventListener('livewire:morph', initTooltips);

            Livewire.on('swal:confirm-delete', (data) => {
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
                        @this.dispatch('delete-confirmed', { id: info.id });
                    }
                });
            });

            Livewire.on('swal:confirm-block', (data) => {
                const info = data[0];
                Swal.fire({
                    title: info.title,
                    text: info.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#6B7A99',
                    confirmButtonText: 'Sí, proceder',
                    cancelButtonText: 'Cancelar',
                    background: '#111827',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('block-confirmed', { id: info.id });
                    }
                });
            });
        });
    </script>
</div>