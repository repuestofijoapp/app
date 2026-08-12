<div class="profile-container container-fluid">
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
        .profile-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            height: fit-content;
        }
        .profile-avatar-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .profile-avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 4px solid var(--accent-red);
            box-shadow: 0 0 20px var(--accent-red-glow);
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 600;
        }
        .input-group input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #fff;
            transition: all 0.3s;
        }
        .input-group input:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 2px var(--accent-red-glow);
        }
        .btn-save {
            background: var(--accent-red);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            font-family: 'Syne', sans-serif;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--accent-red-glow);
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .section-title i {
            color: var(--accent-red);
        }
        .file-upload-label {
            display: inline-block;
            background: var(--surface2);
            border: 1px dashed var(--muted);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .file-upload-label:hover {
            border-color: var(--accent-red);
            color: #fff;
        }
        input[type="file"] {
            display: none;
        }
    </style>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="h2 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">Mi Perfil</h1>
            <p class="text-white">Gestiona tu información personal y configuración de cuenta.</p>
        </div>       
    </div>

    <div class="profile-grid">
        {{-- Left Column: Avatar & Basic Info --}}
        <div class="profile-card">
            <h2 class="section-title fw-bold"><i class="fas fa-user-circle"></i> Información General</h2>            
            <form wire:submit.prevent="updateProfile">
                <div class="profile-avatar-wrapper">
                    @if ($photo)
                        <div class="profile-avatar-preview" style="background-image: url('{{ $photo->temporaryUrl() }}')"></div>
                    @elseif (auth()->user()->profile_photo_path)
                        <div class="profile-avatar-preview" style="background-image: url('{{ Storage::url(auth()->user()->profile_photo_path) }}')"></div>
                    @else
                        <div class="profile-avatar-preview" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=1A2235&bold=true&size=300')"></div>
                    @endif

                    <label class="file-upload-label">
                        <i class="fas fa-camera mr-2"></i> Cambiar Foto
                        <input type="file" wire:model="photo">
                    </label>
                    <div wire:loading wire:target="photo" class="text-xs text-accent-red">Subiendo...</div>
                    @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" wire:model="name">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label>Correo Electrónico</label>
                    <input type="email" wire:model="email" disabled style="opacity: 0.6; cursor: not-allowed;">
                    <label>El correo no se puede cambiar por seguridad.</label>
                </div>

                <button type="submit" class="btn-save" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateProfile">Guardar Cambios</span>
                    <span wire:loading wire:target="updateProfile">Guardando...</span>
                </button>
            </form>
        </div>

        {{-- Right Column: Security --}}
        <div class="profile-card">
            <h2 class="section-title fw-bold"><i class="fas fa-shield-alt"></i> Seguridad</h2>
            <p class="text-white text-sm mb-6">Actualiza tu contraseña para mantener tu cuenta segura.</p>

            <form wire:submit.prevent="updatePassword">
                <div class="input-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" wire:model="password">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="input-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" wire:model="password_confirmation">
                </div>

                <button type="submit" class="btn-save" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updatePassword">Actualizar Contraseña</span>
                    <span wire:loading wire:target="updatePassword">Actualizando...</span>
                </button>
            </form>
        </div>
    </div>
</div>
