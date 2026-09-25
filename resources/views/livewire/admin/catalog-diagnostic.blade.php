<div class="container-fluid">
<style>
    /* ─── Diagnostic Panel ─── */
    .cd-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2rem;
    }

    .cd-header-bar {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .cd-header-bar::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* ─── Stats ─── */
    .cd-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .cd-stat {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.2rem 1.5rem;
        text-align: center;
    }
    .cd-stat-val {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .cd-stat-label {
        font-size: 0.73rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.25rem;
    }

    /* ─── Filter row ─── */
    .cd-filter-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .cd-filter-select {
        background: var(--surface2);
        border: 1px solid var(--border);
        color: #fff;
        border-radius: 10px;
        padding: .6rem 1.2rem;
        font-size: .9rem;
        min-width: 220px;
    }
    .cd-filter-select:focus {
        outline: none;
        border-color: rgba(99,102,241,0.6);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }

    /* ─── Tabs ─── */
    .cd-tabs {
        display: flex;
        gap: .5rem;
        background: var(--surface3);
        border-radius: 14px;
        padding: 5px;
        margin-bottom: 1.5rem;
    }
    .cd-tab {
        flex: 1;
        text-align: center;
        padding: .65rem 1rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: .8rem;
        color: var(--muted);
        background: transparent;
        transition: all .2s;
        letter-spacing: .5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
    }
    .cd-tab.active {
        background: #6366f1;
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.4);
    }
    .cd-tab:hover:not(.active) {
        background: rgba(255,255,255,.06);
        color: #fff;
    }
    .cd-tab-badge {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 20px;
        padding: 1px 7px;
        font-size: .7rem;
        font-weight: 700;
    }
    .cd-tab:not(.active) .cd-tab-badge {
        background: var(--surface);
        color: var(--muted);
    }
    .cd-tab-badge.danger { background: rgba(239,68,68,0.3); color: #f87171; }
    .cd-tab.active .cd-tab-badge.danger { background: rgba(255,255,255,0.25); color: #fff; }

    /* ─── Empty state ─── */
    .cd-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--muted);
    }
    .cd-empty i { font-size: 2.5rem; margin-bottom: 1rem; opacity: .4; }

    /* ─── Duplicate group card ─── */
    .cd-group {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 14px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .cd-group-header {
        background: rgba(99,102,241,0.08);
        border-bottom: 1px solid var(--border);
        padding: .75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cd-group-title {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        color: #a5b4fc;
        font-size: .85rem;
        letter-spacing: .5px;
    }

    /* ─── Model row ─── */
    .cd-model-row {
        display: grid;
        grid-template-columns: 1fr 120px 80px auto;
        align-items: center;
        gap: 1rem;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        transition: background .15s;
    }
    .cd-model-row:last-child { border-bottom: none; }
    .cd-model-row:hover { background: rgba(255,255,255,.02); }

    .cd-model-name {
        font-weight: 600;
        color: #e2e8f0;
        font-size: .9rem;
    }
    .cd-model-years {
        color: var(--muted);
        font-size: .8rem;
    }
    .cd-product-count {
        text-align: center;
    }
    .cd-badge-count {
        background: var(--surface3);
        color: #94a3b8;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: .78rem;
        font-weight: 700;
    }
    .cd-badge-count.has-products {
        background: rgba(99,102,241,0.15);
        color: #a5b4fc;
    }

    /* ─── Merge btn ─── */
    .cd-btn-merge {
        background: rgba(239,68,68,0.12);
        border: 1px solid rgba(239,68,68,0.3);
        color: #f87171;
        border-radius: 8px;
        padding: .35rem .85rem;
        font-size: .78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }
    .cd-btn-merge:hover {
        background: rgba(239,68,68,0.25);
        border-color: rgba(239,68,68,0.5);
    }
    .cd-btn-merge:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* ─── Merge confirm box ─── */
    .cd-merge-confirm {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: .75rem;
        margin-bottom: .5rem;
    }
    .cd-merge-confirm p { color: #fca5a5; font-size: .88rem; margin-bottom: .75rem; }

    /* ─── Engine rows ─── */
    .cd-engine-group {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 14px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .cd-engine-header {
        background: rgba(245,158,11,0.08);
        border-bottom: 1px solid var(--border);
        padding: .75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cd-engine-code {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: #fbbf24;
        font-size: .9rem;
    }

    .cd-engine-row {
        display: grid;
        grid-template-columns: 1fr 140px 120px 80px;
        align-items: center;
        gap: 1rem;
        padding: .75rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        font-size: .85rem;
        color: #cbd5e1;
    }
    .cd-engine-row:last-child { border-bottom: none; }
    .cd-disp-tag {
        font-family: monospace;
        background: var(--surface3);
        border-radius: 6px;
        padding: 2px 8px;
        font-size: .8rem;
        color: #fbbf24;
    }

    /* ─── Pivot missing rows ─── */
    .cd-pivot-row {
        display: grid;
        grid-template-columns: 130px 1fr 80px 80px auto;
        align-items: center;
        gap: 1rem;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        font-size: .85rem;
        color: #cbd5e1;
    }
    .cd-pivot-row:last-child { border-bottom: none; }
    .cd-pivot-table {
        background: var(--surface2);
        border: 1px solid rgba(239,68,68,0.2);
        border-radius: 14px;
        overflow: hidden;
    }
    .cd-pivot-header {
        background: rgba(239,68,68,0.07);
        border-bottom: 1px solid rgba(239,68,68,0.2);
        padding: .75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ─── Buttons ─── */
    .cd-btn {
        border-radius: 10px;
        padding: .55rem 1.2rem;
        font-size: .83rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .cd-btn-primary {
        background: #6366f1;
        color: #fff;
        box-shadow: 0 4px 12px rgba(99,102,241,0.3);
    }
    .cd-btn-primary:hover { background: #4f46e5; }
    .cd-btn-danger {
        background: rgba(239,68,68,0.15);
        color: #f87171;
        border: 1px solid rgba(239,68,68,0.3);
    }
    .cd-btn-danger:hover { background: rgba(239,68,68,0.25); }
    .cd-btn-ghost {
        background: transparent;
        color: var(--muted);
        border: 1px solid var(--border);
    }
    .cd-btn-ghost:hover { background: var(--surface2); color: #fff; }
    .cd-btn-sm {
        padding: .3rem .75rem;
        font-size: .75rem;
        border-radius: 8px;
    }
    .cd-btn-warning {
        background: rgba(245,158,11,0.12);
        color: #fbbf24;
        border: 1px solid rgba(245,158,11,0.3);
    }
    .cd-btn-warning:hover { background: rgba(245,158,11,0.25); }
    .cd-btn-success {
        background: rgba(34,197,94,0.12);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.3);
    }
    .cd-btn-success:hover { background: rgba(34,197,94,0.25); }

    .cd-code { font-family: monospace; color: #94a3b8; font-size: .8rem; }

    /* ─── Unify form ─── */
    .cd-unify-form {
        background: rgba(245,158,11,0.07);
        border: 1px solid rgba(245,158,11,0.25);
        border-radius: 10px;
        padding: 1rem;
        margin-top: .5rem;
    }

    @media (max-width: 768px) {
        .cd-stats { grid-template-columns: repeat(2, 1fr); }
        .cd-model-row { grid-template-columns: 1fr auto; }
        .cd-engine-row { grid-template-columns: 1fr auto; }
        .cd-pivot-row { grid-template-columns: 1fr auto; }
    }
</style>

    {{-- Header --}}
    <div class="cd-header-bar">
        <div style="position: relative; z-index: 1;">
            <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:.5rem;">
                <i class="fas fa-stethoscope" style="font-size:1.6rem; color:#a5b4fc;"></i>
                <h1 class="h3 fw-bold text-white mb-0" style="font-family:'Syne',sans-serif; letter-spacing:-.5px;">
                    Diagnóstico de Catálogo
                </h1>
            </div>
            <p class="text-white mb-0" style="opacity:.65; font-size:.88rem;">
                Detecta y corrige modelos duplicados, motores inconsistentes y productos sin tabla pivote.
            </p>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="cd-stats">
        <div class="cd-stat">
            <div class="cd-stat-val">{{ number_format($totalModels) }}</div>
            <div class="cd-stat-label">Modelos</div>
        </div>
        <div class="cd-stat">
            <div class="cd-stat-val">{{ number_format($totalEngines) }}</div>
            <div class="cd-stat-label">Motores</div>
        </div>
        <div class="cd-stat">
            <div class="cd-stat-val">{{ number_format($totalProducts) }}</div>
            <div class="cd-stat-label">Productos</div>
        </div>
        <div class="cd-stat">
            <div class="cd-stat-val" style="color:#4ade80;">{{ number_format($pivotRows) }}</div>
            <div class="cd-stat-label">Filas Pivote</div>
        </div>
    </div>

    {{-- Main card --}}
    <div class="cd-card">

        {{-- Filter --}}
        <div class="cd-filter-row">
            <div style="display:flex; flex-direction:column; gap:.3rem;">
                <label style="font-size:.75rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">
                    Filtrar por Marca
                </label>
                <select wire:model.live="selectedMake" class="cd-filter-select">
                    <option value="">— Selecciona una marca —</option>
                    @foreach($makes as $make)
                        <option value="{{ $make }}">{{ $make }}</option>
                    @endforeach
                </select>
            </div>

            @if($selectedMake)
                <div style="margin-top:1.3rem;">
                    <button wire:click="analyzeAll" class="cd-btn cd-btn-ghost cd-btn-sm">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                </div>
            @endif

            @if($selectedMake && (count($duplicateGroups) + count($engineGroups) + count($pivotMissing)) === 0)
                <div style="margin-top:1.3rem; display:flex; align-items:center; gap:.5rem; color:#4ade80; font-size:.85rem; font-weight:600;">
                    <i class="fas fa-check-circle"></i> Sin problemas detectados en {{ $selectedMake }}
                </div>
            @endif
        </div>

        @if(!$selectedMake)
            <div class="cd-empty">
                <i class="fas fa-search-location d-block"></i>
                <p style="font-size:.95rem; font-weight:600; color:#e2e8f0; margin-bottom:.25rem;">Selecciona una marca para comenzar</p>
                <p style="font-size:.82rem;">El panel analizará los modelos, motores y productos de esa marca.</p>
            </div>
        @else

            {{-- Tabs --}}
            <div class="cd-tabs">
                <button class="cd-tab {{ $activeTab === 0 ? 'active' : '' }}" wire:click="$set('activeTab', 0)">
                    <i class="fas fa-copy" style="font-size:.75rem;"></i>
                    Modelos Duplicados
                    <span class="cd-tab-badge {{ count($duplicateGroups) > 0 ? 'danger' : '' }}">
                        {{ count($duplicateGroups) }}
                    </span>
                </button>
                <button class="cd-tab {{ $activeTab === 1 ? 'active' : '' }}" wire:click="$set('activeTab', 1)">
                    <i class="fas fa-wrench" style="font-size:.75rem;"></i>
                    Motores Inconsistentes
                    <span class="cd-tab-badge {{ count($engineGroups) > 0 ? 'danger' : '' }}">
                        {{ count($engineGroups) }}
                    </span>
                </button>
                <button class="cd-tab {{ $activeTab === 2 ? 'active' : '' }}" wire:click="$set('activeTab', 2)">
                    <i class="fas fa-unlink" style="font-size:.75rem;"></i>
                    Sin Pivote
                    <span class="cd-tab-badge {{ count($pivotMissing) > 0 ? 'danger' : '' }}">
                        {{ count($pivotMissing) }}
                    </span>
                </button>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- TAB 0 — Duplicate Models --}}
            {{-- ══════════════════════════════════════════════ --}}
            @if($activeTab === 0)
                @if(count($duplicateGroups) === 0)
                    <div class="cd-empty">
                        <i class="fas fa-check-circle d-block" style="color:#4ade80;"></i>
                        <p style="color:#4ade80; font-weight:600;">Sin modelos duplicados para {{ $selectedMake }}</p>
                    </div>
                @else
                    <p style="color:var(--muted); font-size:.83rem; margin-bottom:1rem;">
                        Se detectaron <strong style="color:#f87171;">{{ count($duplicateGroups) }}</strong> grupo(s) de modelos con nombre idéntico o equivalente.
                        El modelo con más productos se muestra primero y se sugiere como destino del merge.
                    </p>

                    @foreach($duplicateGroups as $gIdx => $group)
                        <div class="cd-group">
                            <div class="cd-group-header">
                                <span class="cd-group-title">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Grupo: {{ $group['key'] }}
                                </span>
                                <span style="font-size:.75rem; color:var(--muted);">
                                    {{ count($group['models']) }} variantes
                                </span>
                            </div>

                            @php $targetModel = $group['models'][0]; @endphp

                            @foreach($group['models'] as $mIdx => $model)
                                <div class="cd-model-row">
                                    <div>
                                        <div class="cd-model-name">
                                            @if($mIdx === 0)
                                                <span style="background:rgba(99,102,241,0.15); color:#a5b4fc; border-radius:4px; padding:1px 6px; font-size:.7rem; font-weight:700; margin-right:.4rem;">DESTINO</span>
                                            @endif
                                            {{ $model['name'] }}
                                        </div>
                                        <div class="cd-model-years">
                                            ID: {{ $model['id'] }}
                                            @if($model['start_year'])
                                                · {{ $model['start_year'] }}{{ $model['end_year'] && $model['end_year'] != $model['start_year'] ? '–'.$model['end_year'] : '' }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="cd-model-years">
                                        @if($model['start_year'])
                                            {{ $model['start_year'] }}{{ $model['end_year'] && $model['end_year'] != $model['start_year'] ? '–'.$model['end_year'] : '' }}
                                        @else
                                            <span style="opacity:.4;">—</span>
                                        @endif
                                    </div>

                                    <div class="cd-product-count">
                                        <span class="cd-badge-count {{ $model['product_count'] > 0 ? 'has-products' : '' }}">
                                            {{ $model['product_count'] }} prod.
                                        </span>
                                    </div>

                                    <div>
                                        @if($mIdx === 0)
                                            <span style="font-size:.75rem; color:#a5b4fc; font-weight:600;">
                                                <i class="fas fa-star me-1"></i>Se conserva
                                            </span>
                                        @else
                                            @if($mergeSourceId === $model['id'] && $mergeTargetId === $targetModel['id'])
                                                {{-- Confirm box --}}
                                            @else
                                                <button class="cd-btn-merge"
                                                    wire:click="startMerge({{ $model['id'] }}, {{ $targetModel['id'] }})">
                                                    <i class="fas fa-compress-alt me-1"></i>Fusionar →
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            {{-- Merge confirm --}}
                            @if($mergeSourceId && in_array($mergeSourceId, array_column($group['models'], 'id')))
                                <div style="padding: 0 1.25rem 1.25rem;">
                                    <div class="cd-merge-confirm">
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:.75rem;">
                                            <div>
                                                <h5 style="color:#fca5a5; font-size:.95rem; font-weight:700; margin-bottom:.25rem;">
                                                    <i class="fas fa-compress-alt me-1"></i> Configuración de Fusión de Modelos
                                                </h5>
                                                <p style="color:#cbd5e1; font-size:.82rem; margin-bottom:0;">
                                                    Todos los motores y productos del modelo a eliminar pasarán al modelo conservado.
                                                </p>
                                            </div>
                                            <button type="button" wire:click="swapMergeDirection"
                                                class="cd-btn cd-btn-ghost cd-btn-sm"
                                                style="border:1px solid rgba(255,255,255,0.2); font-size:.78rem; padding:4px 10px;"
                                                title="Cambiar cuál modelo se absorbe y cuál se conserva">
                                                <i class="fas fa-exchange-alt me-1 text-warning"></i> Invertir Dirección
                                            </button>
                                        </div>

                                        <div style="display:flex; gap:1.5rem; background:rgba(0,0,0,0.2); padding:.75rem 1rem; border-radius:8px; margin-bottom:1rem; align-items:center; flex-wrap:wrap;">
                                            <div>
                                                <span style="font-size:.7rem; text-transform:uppercase; color:#f87171; font-weight:700; display:block;">Se eliminará (Absorbido):</span>
                                                <strong style="color:#fca5a5; font-size:.9rem;">«{{ $mergeConfirmName }}»</strong>
                                                <span style="font-size:.75rem; color:var(--muted);">(ID: {{ $mergeSourceId }})</span>
                                            </div>
                                            <div style="color:var(--muted); font-size:1.2rem;">➔</div>
                                            <div>
                                                <span style="font-size:.7rem; text-transform:uppercase; color:#4ade80; font-weight:700; display:block;">Se conservará (Destino):</span>
                                                <strong style="color:#86efac; font-size:.9rem;">«{{ $mergeTargetName }}»</strong>
                                                <span style="font-size:.75rem; color:var(--muted);">(ID: {{ $mergeTargetId }})</span>
                                            </div>
                                        </div>

                                        {{-- Custom name input --}}
                                        <div style="margin-bottom:1rem;">
                                            <label style="font-size:.78rem; font-weight:700; color:#e2e8f0; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:.35rem;">
                                                <i class="fas fa-edit me-1 text-info"></i> Nombre final del modelo resultante (según tu investigación):
                                            </label>
                                            <input type="text" wire:model="mergeCustomName"
                                                class="form-control form-control-sm"
                                                style="background:rgba(0,0,0,0.4); border:1px solid rgba(99,102,241,0.5); color:#fff; font-weight:700; font-size:.92rem; max-width:420px; border-radius:8px; padding:.45rem .85rem;"
                                                placeholder="Ej. HILUX, BONGO III, etc.">
                                            <small style="color:#94a3b8; font-size:.75rem; display:block; margin-top:.35rem;">
                                                Puedes modificarlo con el nombre oficial que investigaste. Al confirmar, el modelo conservado tomará este nombre y actualizará las compatibilidades asociadas.
                                            </small>
                                        </div>

                                        {{-- Clarification if they should remain separated --}}
                                        <div style="background:rgba(59,130,246,0.12); border-left:3px solid #60a5fa; padding:.6rem .85rem; border-radius:6px; margin-bottom:1rem; font-size:.8rem; color:#bfdbfe;">
                                            <i class="fas fa-info-circle me-1"></i> <strong>¿Tu investigación dice que van separados?</strong> Si determinaste que son modelos, series o generaciones distintas que <u>no deben unirse</u>, simplemente presiona <strong>Cancelar</strong> y no los fusiones.
                                        </div>

                                        <div style="display:flex; gap:.75rem;">
                                            <button wire:click="executeMerge"
                                                class="cd-btn cd-btn-danger cd-btn-sm"
                                                style="font-weight:700; padding:.45rem 1.1rem;">
                                                <i class="fas fa-compress-alt me-1"></i> Guardar y Confirmar Fusión
                                            </button>
                                            <button wire:click="cancelMerge"
                                                class="cd-btn cd-btn-ghost cd-btn-sm">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endif

            {{-- ══════════════════════════════════════════════ --}}
            {{-- TAB 1 — Inconsistent Engines --}}
            {{-- ══════════════════════════════════════════════ --}}
            @if($activeTab === 1)
                @if(count($engineGroups) === 0)
                    <div class="cd-empty">
                        <i class="fas fa-check-circle d-block" style="color:#4ade80;"></i>
                        <p style="color:#4ade80; font-weight:600;">Sin motores inconsistentes para {{ $selectedMake }}</p>
                    </div>
                @else
                    <p style="color:var(--muted); font-size:.83rem; margin-bottom:1rem;">
                        Motores con el mismo código pero diferente <em>displacement</em> registrado.
                        Selecciona el valor correcto para unificarlos.
                    </p>

                    @foreach($engineGroups as $eIdx => $eGroup)
                        <div class="cd-engine-group">
                            <div class="cd-engine-header">
                                <span class="cd-engine-code">
                                    <i class="fas fa-cog me-1"></i>{{ $eGroup['code'] }}
                                </span>
                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <span style="font-size:.75rem; color:var(--muted);">
                                        {{ count($eGroup['engines']) }} registros · {{ count($eGroup['displacements']) }} displacements distintos
                                    </span>
                                    @if($unifyEngineGroupKey !== $eIdx)
                                        <button class="cd-btn cd-btn-warning cd-btn-sm"
                                            wire:click="startUnify({{ $eIdx }})">
                                            <i class="fas fa-compress"></i> Unificar
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Engine rows --}}
                            <div>
                                <div class="cd-engine-row" style="color:var(--muted); font-size:.72rem; text-transform:uppercase; letter-spacing:.8px; padding:.5rem 1.25rem; border-bottom:1px solid var(--border);">
                                    <span>Modelo</span><span>Displacement</span><span>Combustible</span><span>ID</span>
                                </div>
                                @foreach($eGroup['engines'] as $eng)
                                    <div class="cd-engine-row">
                                        <span>{{ $eng['model_name'] }}</span>
                                        <span>
                                            <span class="cd-disp-tag">{{ $eng['displacement'] ?? '—' }}</span>
                                        </span>
                                        <span>{{ $eng['fuel_type'] ?? '—' }}</span>
                                        <span class="cd-code">#{{ $eng['id'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Unify form --}}
                            @if($unifyEngineGroupKey === $eIdx)
                                <div style="padding: .75rem 1.25rem 1.25rem;">
                                    <div class="cd-unify-form">
                                        <p style="color:#fbbf24; font-size:.85rem; margin-bottom:.75rem;">
                                            <i class="fas fa-edit me-1"></i>
                                            Ingresa el displacement correcto para todos los registros del motor <strong>{{ $eGroup['code'] }}</strong>:
                                        </p>
                                        <div style="display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;">
                                            <input type="text"
                                                wire:model="unifyDisplacement"
                                                placeholder="Ej: 2438"
                                                class="cd-filter-select"
                                                style="min-width:160px; padding:.45rem 1rem; font-size:.88rem;">
                                            <button wire:click="executeUnify"
                                                class="cd-btn cd-btn-warning cd-btn-sm">
                                                <i class="fas fa-check"></i> Aplicar
                                            </button>
                                            <button wire:click="cancelUnify"
                                                class="cd-btn cd-btn-ghost cd-btn-sm">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endif

            {{-- ══════════════════════════════════════════════ --}}
            {{-- TAB 2 — Products missing from pivot --}}
            {{-- ══════════════════════════════════════════════ --}}
            @if($activeTab === 2)
                @if(count($pivotMissing) === 0)
                    <div class="cd-empty">
                        <i class="fas fa-check-circle d-block" style="color:#4ade80;"></i>
                        <p style="color:#4ade80; font-weight:600;">Todos los productos de {{ $selectedMake }} tienen filas en el pivote</p>
                    </div>
                @else
                    <div class="cd-pivot-table">
                        <div class="cd-pivot-header">
                            <span style="font-family:'Syne',sans-serif; font-weight:700; color:#f87171; font-size:.85rem;">
                                <i class="fas fa-unlink me-1"></i>
                                {{ count($pivotMissing) }} producto(s) sin filas en product_compatibilities
                                @php $orphanCount = collect($pivotMissing)->where('status','orphaned')->count(); @endphp
                                @if($orphanCount > 0)
                                    <span style="font-size:.75rem; color:#fbbf24; margin-left:.5rem;">
                                        · {{ $orphanCount }} con IDs huérfanos
                                    </span>
                                @endif
                            </span>
                            <button wire:click="resyncAll"
                                class="cd-btn cd-btn-success cd-btn-sm">
                                <i class="fas fa-sync-alt"></i> Re-sincronizar Válidos
                            </button>
                        </div>

                        {{-- Header row --}}
                        <div style="display:grid; grid-template-columns:130px 1fr 90px 90px auto; align-items:center; gap:1rem; padding:.6rem 1.25rem; color:var(--muted); font-size:.72rem; text-transform:uppercase; letter-spacing:.8px; background:rgba(0,0,0,.15); border-bottom:1px solid rgba(255,255,255,.06);">
                            <span>Código</span>
                            <span>Nombre</span>
                            <span>Estado</span>
                            <span>IDs</span>
                            <span>Acción</span>
                        </div>

                        @foreach($pivotMissing as $pRow)
                            @php $isOrphaned = ($pRow['status'] ?? '') === 'orphaned'; @endphp
                            <div style="display:grid; grid-template-columns:130px 1fr 90px 90px auto; align-items:center; gap:1rem; padding:.9rem 1.25rem; border-bottom:1px solid rgba(255,255,255,.04); {{ $isOrphaned ? 'background:rgba(245,158,11,0.04);' : '' }}">

                                {{-- Code --}}
                                <span style="font-family:monospace; font-size:.85rem; color:{{ $isOrphaned ? '#fbbf24' : '#e2e8f0' }}; font-weight:600;">
                                    {{ $pRow['supplier_code'] }}
                                </span>

                                {{-- Name + orphan detail --}}
                                <div>
                                    <div style="color:#cbd5e1; font-size:.83rem;">{{ Str::limit($pRow['name'], 50) }}</div>
                                    @if($isOrphaned && $pRow['orphan_detail'])
                                        <div style="color:#f59e0b; font-size:.73rem; margin-top:.2rem; opacity:.85;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $pRow['orphan_detail'] }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Status badge --}}
                                <span>
                                    @if($isOrphaned)
                                        <span style="background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.3); border-radius:20px; padding:2px 8px; font-size:.72rem; font-weight:700;">
                                            <i class="fas fa-unlink me-1"></i>Huérfano
                                        </span>
                                    @else
                                        <span style="background:rgba(34,197,94,0.1); color:#4ade80; border:1px solid rgba(34,197,94,0.25); border-radius:20px; padding:2px 8px; font-size:.72rem; font-weight:700;">
                                            <i class="fas fa-check me-1"></i>Válido
                                        </span>
                                    @endif
                                </span>

                                {{-- IDs count --}}
                                <span style="display:flex; gap:.3rem; flex-wrap:wrap;">
                                    <span class="cd-badge-count {{ !empty($pRow['valid_models']) ? 'has-products' : '' }}"
                                        title="{{ count($pRow['model_ids']) }} modelo(s) en JSON, {{ count($pRow['valid_models'] ?? []) }} válidos">
                                        M:{{ count($pRow['valid_models'] ?? []) }}/{{ count($pRow['model_ids']) }}
                                    </span>
                                    @if(!empty($pRow['engine_ids']))
                                        <span class="cd-badge-count {{ !empty($pRow['valid_engines']) ? 'has-products' : '' }}"
                                            title="{{ count($pRow['engine_ids']) }} motor(es) en JSON, {{ count($pRow['valid_engines'] ?? []) }} válidos">
                                            E:{{ count($pRow['valid_engines'] ?? []) }}/{{ count($pRow['engine_ids']) }}
                                        </span>
                                    @endif
                                </span>

                                {{-- Action --}}
                                <div style="display:flex; gap:.4rem; flex-wrap:wrap;">
                                    @if($isOrphaned)
                                        <button wire:click="clearOrphanedIds({{ $pRow['id'] }})"
                                            class="cd-btn cd-btn-warning cd-btn-sm"
                                            title="Elimina los IDs que ya no existen para poder reasignar el producto">
                                            <i class="fas fa-broom"></i> Limpiar IDs
                                        </button>
                                    @else
                                        <button wire:click="resyncProduct({{ $pRow['id'] }})"
                                            class="cd-btn cd-btn-success cd-btn-sm">
                                            <i class="fas fa-sync-alt"></i> Sync
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        @endif {{-- end if selectedMake --}}
    </div>
</div>
