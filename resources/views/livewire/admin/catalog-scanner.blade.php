<div class="d-inline-block">
    {{-- Trigger button --}}
    <button wire:click="openModal" class="btn-scan-catalog px-4 fw-bold text-white" title="Escanear catálogo con IA">
        <i class="fas fa-camera me-2"></i> Escanear Catálogo
    </button>

    @if($showModal)
        <div class="modal-overlay-scan" wire:click.self="closeModal">
            <div class="scan-modal-box">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="scan-icon-box">
                            <i class="fas fa-brain text-purple-glow fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 text-white fw-bold">Escáner de Catálogo IA</h4>
                            <p class="mb-0 text-white opacity-60" style="font-size:0.82rem;">
                                Sube una captura de la tabla del catálogo — Gemini extrae los datos automáticamente
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn text-white p-1"
                        style="font-size:2rem; line-height:1;">&times;</button>
                </div>

                @if(!$showResults)
                    {{-- Upload Area --}}
                    <div class="scan-upload-area mb-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-table text-purple-glow mb-2" style="font-size:2.5rem;opacity:0.6;"></i>
                            <p class="text-white opacity-70 mb-0" style="font-size:0.88rem;">
                                Sube la captura de pantalla del catálogo de anillos
                            </p>
                            <p class="text-white opacity-40 mb-0" style="font-size:0.78rem;">
                                PNG, JPG, WEBP — Máx. 5MB
                            </p>
                        </div>
                        <input type="file" wire:model="catalogImage" accept="image/*" class="form-control border-0 text-white"
                            style="background: rgba(139,92,246,0.08); border-radius:10px; padding:10px;">

                        @if($catalogImage)
                            <div class="mt-3 text-center">
                                <img src="{{ $catalogImage->temporaryUrl() }}" class="img-fluid rounded-3 shadow"
                                    style="max-height:200px; border: 2px solid rgba(139,92,246,0.3);">
                                <p class="text-white opacity-60 mt-1" style="font-size:0.78rem;">Vista previa</p>
                            </div>
                        @endif

                        @error('catalogImage')
                            <div class="alert alert-danger mt-2 py-2 px-3 rounded-3" style="font-size:0.82rem;">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($errorMessage)
                            <div class="alert alert-warning mt-3 py-2 px-3 rounded-3"
                                style="font-size:0.82rem; background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.3); color:#fbbf24;">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $errorMessage }}
                            </div>
                        @endif
                    </div>

                    {{-- Scan button --}}
                    <button wire:click="scanCatalog" wire:loading.attr="disabled"
                        class="btn-purple-scan w-100 py-3 fw-bold rounded-4" @if($scanning) disabled @endif>
                        <span wire:loading.remove wire:target="scanCatalog">
                            <i class="fas fa-wand-magic-sparkles me-2"></i> ANALIZAR CON IA
                        </span>
                        <span wire:loading wire:target="scanCatalog">
                            <i class="fas fa-spinner fa-spin me-2"></i> Analizando imagen... (puede tardar 10-20s)
                        </span>
                    </button>

                @else
                    {{-- ✅ Results --}}
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white fw-bold">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                {{ count($scannedData) }} producto(s) detectados
                            </span>
                        </div>
                        <button wire:click="$set('showResults', false)" class="btn btn-sm btn-outline-secondary rounded-3">
                            <i class="fas fa-undo me-1"></i> Nueva imagen
                        </button>
                    </div>

                    {{-- Results table --}}
                    <div class="scan-results-table mb-4" style="max-height:350px; overflow-y:auto;">
                        @foreach($scannedData as $i => $row)
                            <div class="scan-result-card mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="fw-bold text-purple-glow"
                                        style="font-size:1rem;">{{ $row['supplier_code'] ?? '—' }}</span>
                                    @if(!empty($row['oem_code']))
                                        <span class="badge-oem">Código Original: {{ is_array($row['oem_code']) ? implode(', ', $row['oem_code']) : $row['oem_code'] }}</span>
                                    @endif
                                </div>

                                <div class="row g-2" style="font-size:0.8rem; color:rgba(255,255,255,0.7);">
                                    @if(!empty($row['make']))
                                        <div class="col-4">
                                            <span class="scan-label">MARCA</span>
                                            <span class="d-block text-white">{{ $row['make'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['models']))
                                        <div class="col-8">
                                            <span class="scan-label">MODELOS</span>
                                            <span class="d-block text-white">{{ implode(', ', $row['models']) }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['engines']))
                                        <div class="col-6">
                                            <span class="scan-label">MOTOR</span>
                                            <span class="d-block text-white">{{ implode(', ', $row['engines']) }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['displacement']))
                                        <div class="col-3">
                                            <span class="scan-label">CC</span>
                                            <span class="d-block text-white">{{ $row['displacement'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['bore']))
                                        <div class="col-3">
                                            <span class="scan-label">DIÁMETRO</span>
                                            <span class="d-block text-white">{{ $row['bore'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['heights']))
                                        <div class="col-4">
                                            <span class="scan-label">ALTURAS</span>
                                            <span class="d-block text-white">{{ $row['heights'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['radial']))
                                        <div class="col-4">
                                            <span class="scan-label">ANCHO RADIAL</span>
                                            <span class="d-block text-white">{{ is_array($row['radial']) ? implode(' / ', $row['radial']) : $row['radial'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row['shape']))
                                        <div class="col-4">
                                            <span class="scan-label">FORMA</span>
                                            <span class="d-block text-white">{{ $row['shape'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Apply button --}}
                    <button wire:click="applyToProducts" class="btn-purple-scan w-100 py-3 fw-bold rounded-4 bg-success-scan">
                        <i class="fas fa-cloud-upload-alt me-2"></i>
                        APLICAR A PRODUCTOS EN BASE DE DATOS
                    </button>
                    <p class="text-white opacity-50 text-center mt-2" style="font-size:0.75rem;">
                        Actualiza TODAS las sobremedidas del mismo código
                    </p>
                @endif

            </div>
        </div>
    @endif

    <style>
        .btn-scan-catalog {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-family: 'Syne', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
            transition: 0.3s;
        }

        .btn-scan-catalog:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
            color: white;
        }

        .modal-overlay-scan {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .scan-modal-box {
            background: #0f172a;
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 24px;
            padding: 2rem;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 40px rgba(139, 92, 246, 0.1);
        }

        .scan-icon-box {
            background: rgba(139, 92, 246, 0.1);
            border-radius: 14px;
            padding: 0.8rem 1rem;
        }

        .scan-upload-area {
            background: rgba(255, 255, 255, 0.02);
            border: 2px dashed rgba(139, 92, 246, 0.25);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .btn-purple-scan {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border: none;
            color: white;
            font-family: 'Syne', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
            transition: 0.3s;
        }

        .btn-purple-scan:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(124, 58, 237, 0.5);
        }

        .btn-purple-scan:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .bg-success-scan {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4) !important;
        }

        .scan-result-card {
            background: rgba(139, 92, 246, 0.06);
            border: 1px solid rgba(139, 92, 246, 0.15);
            border-radius: 12px;
            padding: 1rem;
        }

        .scan-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
        }

        .badge-oem {
            background: rgba(250, 204, 21, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(250, 204, 21, 0.25);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .text-purple-glow {
            color: #a78bfa;
        }
    </style>
</div>