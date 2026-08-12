<div class="container-fluid" wire:poll.8000ms>
    <style>
        /* ── En Vivo: estilos específicos ── */
        .envivo-stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.4rem 1.8rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            transition: border-color .25s;
        }

        .envivo-stat-card:hover {
            border-color: rgba(255, 255, 255, .15);
        }

        .envivo-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .envivo-stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
        }

        .envivo-stat-label {
            font-size: .72rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .07em;
            font-weight: 700;
        }

        /* ── Query Card ── */
        .query-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.35rem 1.5rem;
            transition: border-color .3s, transform .3s;
            position: relative;
            overflow: hidden;
        }

        .query-card:hover {
            border-color: rgba(190, 60, 59, .35);
            transform: translateY(-2px);
        }

        .query-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            border-radius: 18px 0 0 18px;
        }

        .query-card.urgente::before {
            background: #ff3b5c;
        }

        .query-card.normal::before {
            background: #fbbf24;
        }

        .query-card.tranquilo::before {
            background: #00d68f;
        }

        /* ── Timer ring ── */
        .timer-wrap {
            position: relative;
            width: 60px;
            height: 60px;
            flex-shrink: 0;
        }

        .timer-svg {
            transform: rotate(-90deg);
        }

        .timer-track {
            fill: none;
            stroke: rgba(255, 255, 255, .07);
            stroke-width: 4;
        }

        .timer-fill {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
            transition: stroke-dashoffset .9s linear, stroke .9s;
        }

        .timer-text {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: .72rem;
            font-weight: 800;
            color: #fff;
        }

        /* ── product pill ── */
        .prod-pill {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 8px;
            padding: .25rem .65rem;
            font-size: .75rem;
            color: #d1d5db;
            white-space: nowrap;
        }

        /* ── empty state ── */
        .envivo-empty {
            background: var(--surface);
            border: 1px dashed rgba(255, 255, 255, .08);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        /* ── Pulse dot ── */
        @keyframes live-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(.75);
            }
        }

        .live-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ff3b5c;
            display: inline-block;
            animation: live-pulse 1.1s ease-in-out infinite;
        }
    </style>

    {{-- ── Header ── --}}
    <div class="mb-4 d-flex align-items-center gap-3">
        <div>
            <h1 class="h2 fw-bold text-white mb-1" style="font-family:'Syne',sans-serif; letter-spacing:-.5px;">
                <span class="live-dot me-2"></span>En Vivo
            </h1>
            <p class="text-white opacity-50" style="font-size:.85rem;">
                Consultas activas de ZettaBot · Se actualiza cada 8 segundos
            </p>
        </div>
        <div class="ms-auto">
            <span class="badge px-3 py-2"
                style="background:rgba(255,59,92,.12); border:1px solid rgba(255,59,92,.25); color:#ff3b5c; border-radius:10px; font-family:'Syne',sans-serif; font-size:.72rem; font-weight:800; letter-spacing:.05em;">
                <i class="fas fa-broadcast-tower me-1"></i>LIVE
            </span>
        </div>
    </div>

    {{-- ── Alerta Quota Exceeded ── --}}
    @if($quotaWarning)
    <div class="alert border-0 mb-4 d-flex align-items-start gap-3"
        style="background:rgba(251,191,36,.08); border:1px solid rgba(251,191,36,.3) !important; border-radius:14px; padding:1.1rem 1.3rem;">
        <div style="font-size:1.4rem; flex-shrink:0;">⚠️</div>
        <div>
            <div class="fw-bold mb-1" style="color:#fbbf24; font-family:'Syne',sans-serif; font-size:.9rem;">GREEN API: CUOTA SUPERADA</div>
            <div style="color:rgba(255,255,255,.65); font-size:.82rem; line-height:1.55;">
                La cuenta de Green API ha superado el límite de mensajes de su plan. Esto significa que las <strong style="color:#fff;">respuestas de los proveedores por WhatsApp no llegarán al sistema</strong> hasta que se restablezca la cuota.<br>
                <strong style="color:#fbbf24;">Acción requerida:</strong> Usa el botón <em>«Confirmar precio manualmente»</em> en cada consulta activa, o accede a <a href="https://console.green-api.com" target="_blank" style="color:#fbbf24;">console.green-api.com</a> para revisar tu plan.
            </div>
        </div>
    </div>
    @endif

    {{-- ── Flash mensaje de éxito ── --}}
    @if(session('envivo_success'))
    <div class="alert border-0 mb-4 d-flex align-items-center gap-2"
        style="background:rgba(0,214,143,.10); border:1px solid rgba(0,214,143,.25) !important; border-radius:12px; color:#00d68f; font-size:.85rem;">
        <i class="fas fa-check-circle"></i>
        {{ session('envivo_success') }}
    </div>
    @endif

    {{-- ── Modal Confirmación Manual ── --}}
    @if($confirmingQueryId)
        @php
            $selectedQuery = $activas->firstWhere('id', $confirmingQueryId);
            if (!$selectedQuery) {
                $selectedQuery = \App\Models\ZbotQuery::with('provider')->find($confirmingQueryId);
            }
            $selectedProvName = $selectedQuery?->provider?->business_name ?? $selectedQuery?->provider?->whatsapp_number ?? 'Proveedor #' . ($selectedQuery?->provider_id ?? 'N/A');
            $selectedItems = $selectedQuery?->items_json ?? [];
        @endphp
    <div style="position:fixed; inset:0; background:rgba(0,0,0,.75); z-index:1055; display:flex; align-items:center; justify-content:center;">
        <div style="background:#1a2233; border:1px solid rgba(255,255,255,.12); border-radius:20px; padding:2rem; width:100%; max-width:420px; box-shadow:0 24px 60px rgba(0,0,0,.6);">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:42px;height:42px;border-radius:12px;background:rgba(251,191,36,.12);display:flex;align-items:center;justify-content:center;color:#fbbf24;font-size:1.1rem;">
                    <i class="fas fa-pencil-alt"></i>
                </div>
                <div>
                    <div class="fw-bold text-white" style="font-family:'Syne',sans-serif;">Confirmar precio manualmente</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.45);">Consulta #{{ $confirmingQueryId }}</div>
                </div>
            </div>
            
            <!-- Detalle del Proveedor y Producto -->
            <div class="mb-3 p-3 text-start" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:12px;">
                <div class="mb-2">
                    <div style="font-size:.68rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; font-weight:700; margin-bottom:.2rem;">Proveedor</div>
                    <div class="fw-bold text-white" style="font-size:.85rem; font-family:'Syne',sans-serif;">
                        <i class="fas fa-store me-1 text-warning" style="font-size:.8rem;"></i> {{ $selectedProvName }}
                    </div>
                </div>
                
                <div>
                    <div style="font-size:.68rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; font-weight:700; margin-bottom:.2rem;">Productos Solicitados</div>
                    <div class="d-flex flex-column gap-2">
                        @foreach($selectedItems as $item)
                            @php
                                $itemName = is_array($item) ? ($item['name'] ?? $item['product_name'] ?? $item['product']['name'] ?? 'Producto') : $item;
                                $itemQty = is_array($item) ? ($item['quantity'] ?? $item['qty'] ?? 1) : 1;
                            @endphp
                            <div class="p-2 rounded text-start" style="background:rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.03);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-white text-truncate fw-bold" style="max-width:240px; font-size:.78rem;" title="{{ $itemName }}">
                                        <i class="fas fa-cog me-1 text-muted" style="font-size:.7rem;"></i> 
                                        {{ $itemName }}
                                    </span>
                                    <span class="badge bg-secondary" style="font-size:.65rem; color:#fff;">
                                        ×{{ $itemQty }}
                                    </span>
                                </div>
                                
                                @if(is_array($item) && isset($item['product']))
                                    <div class="mt-1 d-flex flex-wrap gap-x-2 gap-y-1 text-muted" style="font-size:.7rem; padding-left: 1rem; border-top: 1px dashed rgba(255,255,255,0.05); pt-1;">
                                        @if(!empty($item['product']['brand']))
                                            <span style="margin-right:8px;"><strong>Marca:</strong> {{ $item['product']['brand'] }}</span>
                                        @endif
                                        @if(!empty($item['product']['supplier_code']))
                                            <span style="margin-right:8px;">
                                                <strong>Cod:</strong> {{ $item['product']['supplier_code'] }}
                                                @if(!empty($item['product']['oversize']))
                                                    <span style="color:#fbbf24;font-weight:700;"> {{ $item['product']['oversize'] }}</span>
                                                @endif
                                            </span>
                                        @endif
                                        @if(!empty($item['product']['oem_code']))
                                            <span class="text-truncate" style="max-width: 100%; margin-right:8px;" title="{{ $item['product']['oem_code'] }}"><strong>OEM:</strong> {{ $item['product']['oem_code'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <p style="font-size:.83rem;color:rgba(255,255,255,.55);line-height:1.55;" class="mb-3">
                El proveedor respondió por WhatsApp pero Green API no pudo enviar el webhook. Introduce el precio confirmado manualmente.
            </p>
            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;">Precio S/</label>
                <input type="number" step="0.01" min="0" wire:model="manualPrice"
                    class="form-control"
                    style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);color:#fff;border-radius:10px;padding:.7rem 1rem;font-size:1.1rem;font-weight:700;"
                    placeholder="Ej: 150.00" autofocus>
                @if($manualError)
                    <div style="color:#ff3b5c;font-size:.78rem;margin-top:.4rem;"><i class="fas fa-exclamation-circle me-1"></i>{{ $manualError }}</div>
                @endif
            </div>
            <div class="d-flex gap-2">
                <button wire:click="manualConfirm" class="btn flex-grow-1 fw-bold"
                    style="background:#00d68f;color:#000;border-radius:10px;padding:.65rem;">
                    <i class="fas fa-check me-2"></i>Confirmar precio
                </button>
                <button wire:click="cancelManualConfirm" class="btn"
                    style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);border-radius:10px;padding:.65rem 1rem;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Stat cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="envivo-stat-card">
                <div class="envivo-stat-icon" style="background:rgba(255,59,92,.12); color:#ff3b5c;">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <div>
                    <div class="envivo-stat-val">{{ $totalActivos }}</div>
                    <div class="envivo-stat-label">Activas ahora</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="envivo-stat-card">
                <div class="envivo-stat-icon" style="background:rgba(251,191,36,.10); color:#fbbf24;">
                    <i class="fas fa-hourglass-end"></i>
                </div>
                <div>
                    <div class="envivo-stat-val">{{ $totalExpirados }}</div>
                    <div class="envivo-stat-label">Expiradas hoy</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="envivo-stat-card">
                <div class="envivo-stat-icon" style="background:rgba(0,214,143,.10); color:#00d68f;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <div class="envivo-stat-val">{{ $totalHoy }}</div>
                    <div class="envivo-stat-label">Total consultas hoy</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Grid de consultas activas ── --}}
    @if($activas->isEmpty())
        <div class="envivo-empty">
            <i class="fas fa-satellite-dish fa-3x mb-4" style="color:rgba(255,255,255,.1);"></i>
            <h4 class="fw-bold text-white mb-2" style="font-family:'Syne',sans-serif;">Sin consultas activas</h4>
            <p style="color:var(--muted); font-size:.88rem;">
                ZettaBot no tiene consultas en espera en este momento.<br>
                Esta vista se actualiza automáticamente.
            </p>
        </div>
    @else
        <div class="row g-3">
            @foreach($activas as $query)
                @php
                    $expiresAt = $query->expires_at;
                    $totalSecs = 9 * 60; // ventana de 9 minutos
                    $remainSecs = $expiresAt ? max(0, now()->diffInSeconds($expiresAt, false)) : $totalSecs;
                    $elapsed = $totalSecs - $remainSecs;
                    $pct = $totalSecs > 0 ? ($elapsed / $totalSecs) : 0;

                    // Urgencia
                    if ($remainSecs < 60) {
                        $urgencia = 'urgente';
                        $timerColor = '#ff3b5c';
                    } elseif ($remainSecs < 180) {
                        $urgencia = 'normal';
                        $timerColor = '#fbbf24';
                    } else {
                        $urgencia = 'tranquilo';
                        $timerColor = '#00d68f';
                    }

                    // Tiempo display
                    $mins = floor($remainSecs / 60);
                    $secs = $remainSecs % 60;
                    $timeStr = sprintf('%d:%02d', $mins, $secs);

                    // SVG ring
                    $r = 24;
                    $circ = 2 * M_PI * $r;
                    $dash = $circ * (1 - $pct);

                    // Proveedor
                    $provName = $query->provider->business_name ?? $query->provider->whatsapp_number ?? 'Proveedor #' . $query->provider_id;

                    // Items
                    $items = $query->items_json ?? [];
                @endphp

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="query-card {{ $urgencia }}">

                        {{-- Top row: proveedor + timer --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            {{-- Avatar letra --}}
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                style="width:40px; height:40px; background:rgba(255,255,255,.06); font-family:'Syne',sans-serif; font-size:.85rem; color:{{ $timerColor }}; border:1px solid rgba(255,255,255,.08); flex-shrink:0;">
                                {{ strtoupper(substr($provName, 0, 1)) }}
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-white"
                                    style="font-family:'Syne',sans-serif; font-size:.88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $provName }}
                                </div>
                                <div style="font-size:.72rem; color:var(--muted);">
                                    <i class="fas fa-hashtag me-1" style="font-size:.6rem;"></i>ID {{ $query->id }}
                                    &nbsp;·&nbsp;
                                    {{ $query->created_at->format('H:i') }}
                                </div>
                            </div>

                            {{-- Timer ring --}}
                            <div class="timer-wrap" title="{{ $remainSecs > 0 ? 'Expira en ' . $timeStr : 'Expirado' }}"
                                data-remain="{{ $remainSecs }}" data-expires="{{ $expiresAt?->timestamp ?? 0 }}">
                                <svg class="timer-svg" width="60" height="60" viewBox="0 0 60 60">
                                    <circle class="timer-track" cx="30" cy="30" r="{{ $r }}" />
                                    <circle class="timer-fill" cx="30" cy="30" r="{{ $r }}" stroke="{{ $timerColor }}"
                                        stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $circ - $dash }}" />
                                </svg>
                                <div class="timer-text" style="color:{{ $timerColor }};">
                                    {{ $timeStr }}
                                </div>
                            </div>
                        </div>

                        {{-- Productos solicitados --}}
                        @if(!empty($items))
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach(array_slice($items, 0, 4) as $item)
                                    <span class="prod-pill">
                                        @if(is_array($item))
                                            @php
                                                $pillCode = $item['product']['supplier_code'] ?? $item['name'] ?? $item['product_name'] ?? 'Producto';
                                                $pillOversize = $item['product']['oversize'] ?? null;
                                            @endphp
                                            <span class="fw-bold">{{ $pillCode }}</span>
                                            @if($pillOversize)
                                                <span style="color:#fbbf24;font-size:.7rem;font-weight:700;"> {{ $pillOversize }}</span>
                                            @endif
                                            @if(!empty($item['quantity'])) <span style="color:var(--muted);">×{{ $item['quantity'] }}</span> @endif
                                        @else
                                            {{ $item }}
                                        @endif
                                    </span>
                                @endforeach
                                @if(count($items) > 4)
                                    <span class="prod-pill" style="color:var(--muted);">+{{ count($items) - 4 }} más</span>
                                @endif
                            </div>
                        @endif

                        {{-- Footer: reminders + status --}}
                        <div class="d-flex align-items-center justify-content-between mt-1 mb-3">
                            <div style="font-size:.72rem; color:var(--muted);">
                                <i class="fas fa-bell me-1"></i>
                                {{ $query->reminders_sent ?? 0 }} recordatorio(s) enviado(s)
                            </div>
                            <span class="badge px-2 py-1 rounded-pill" style="font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em;
                                                @if($urgencia === 'urgente') background:rgba(255,59,92,.15); color:#ff3b5c; border:1px solid rgba(255,59,92,.3);
                                                @elseif($urgencia === 'normal') background:rgba(251,191,36,.12); color:#fbbf24; border:1px solid rgba(251,191,36,.25);
                                                @else background:rgba(0,214,143,.10); color:#00d68f; border:1px solid rgba(0,214,143,.2);
                                                @endif">
                                @if($urgencia === 'urgente') <i class="fas fa-exclamation me-1"></i>Urgente
                                @elseif($urgencia === 'normal') <i class="fas fa-clock me-1"></i>Normal
                                @else <i class="fas fa-check me-1"></i>OK
                                @endif
                            </span>
                        </div>

                        {{-- Botón confirmación manual (visible siempre para admin) --}}
                        <button wire:click="openManualConfirm({{ $query->id }})"
                            class="btn btn-sm w-100 fw-bold"
                            style="background:rgba(251,191,36,.10); border:1px solid rgba(251,191,36,.25); color:#fbbf24; border-radius:10px; font-size:.75rem; padding:.45rem;">
                            <i class="fas fa-pencil-alt me-1"></i>Confirmar precio manualmente
                        </button>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Nota de actualización --}}
        <div class="text-center mt-4" style="color:var(--muted); font-size:.75rem;">
            <i class="fas fa-sync-alt me-1"></i>
            Actualización automática cada 8s · {{ now()->format('H:i:s') }}
        </div>
    @endif

    {{-- ── JS: timers corriendo en cliente (no depende del poll) ── --}}
    <script>
        (function () {
            function initTimers() {
                document.querySelectorAll('.timer-wrap[data-expires]').forEach(function (wrap) {
                    const expiresTs = parseInt(wrap.dataset.expires, 10);
                    if (!expiresTs) return;

                    const r = 24;
                    const circ = 2 * Math.PI * r;
                    const total = 9 * 60;

                    const fillEl = wrap.querySelector('.timer-fill');
                    const textEl = wrap.querySelector('.timer-text');

                    function tick() {
                        const now = Math.floor(Date.now() / 1000);
                        const remain = Math.max(0, expiresTs - now);
                        const elapsed = total - remain;
                        const pct = Math.min(1, elapsed / total);

                        // ring
                        const dash = circ * (1 - pct);
                        fillEl.style.strokeDashoffset = circ - dash;

                        // color
                        let color = '#00d68f';
                        if (remain < 60) color = '#ff3b5c';
                        else if (remain < 180) color = '#fbbf24';
                        fillEl.setAttribute('stroke', color);
                        textEl.style.color = color;

                        // text
                        const m = Math.floor(remain / 60);
                        const s = remain % 60;
                        textEl.textContent = m + ':' + String(s).padStart(2, '0');

                        if (remain > 0) requestAnimationFrame(tick);
                    }
                    tick();
                });
            }

            // Primera vez
            initTimers();

            // Re-iniciar después de cada poll de Livewire (Livewire v3)
            document.addEventListener('livewire:navigated', initTimers);
            document.addEventListener('livewire:updated', initTimers);
        })();
    </script>
</div>