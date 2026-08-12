<div>
    <style>
        :root {
            --bg-dark: #0A0E1A;
            --sidebar-bg: #111827;
            --card-bg: #111827;
            --border-color: rgba(255, 255, 255, 0.07);
            --text-main: #F0F4FF;
            --text-muted: #6B7A99;
            --accent-red: #1da04f;
            --accent-hover: #15803d;
        }
        
        .b2b-layout {
            display: flex;
            min-height: 100vh;
            background: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: -1.5rem; /* Negate the container padding from layouts.app if any */
        }
        
        .b2b-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            padding: 30px 23px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #1e2d3d;
        }
        
        .b2b-logo {
            padding: 16px 24px 32px;
        }
        
        .b2b-nav-item {
            padding: 14px 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            color: var(--text-muted);
            transition: .2s;
            border-left: 3px solid transparent;
            background: transparent;
            border-top: none;
            border-right: none;
            border-bottom: none;
            width: 100%;
            text-align: left;
        }
        
        .b2b-nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.02);
        }
        
        .b2b-nav-item.active {
            background: var(--card-bg);
            color: #fff;
            border-left: 3px solid var(--accent-red);
            font-weight: 600;
        }
        
        .b2b-main {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }
        
        .b2b-topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }
        
        .b2b-topbar h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 4px;
            color: #fff;
        }
        
        .b2b-badge-live {
            background: var(--accent-red);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .b2b-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid var(--border-color);
            margin-bottom: 24px;
        }
        
        .b2b-card h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .b2b-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        
        .b2b-table th {
            text-align: left;
            padding: 12px 16px;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid #1e2d3d;
        }
        
        .b2b-table td {
            padding: 16px;
            border-bottom: 1px solid #1e2d3d;
            color: #cbd5e1;
            vertical-align: middle;
        }
        
        .b2b-upload-zone {
            border: 2px dashed #334155;
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            cursor: pointer;
            transition: .2s;
            background: #121b26;
        }
        
        .b2b-upload-zone:hover {
            border-color: var(--accent-red);
            background: #16202d;
        }
        
        .btn-primary {
            background: var(--accent-red);
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: .2s;
        }
        
        .btn-primary:hover {
            background: var(--accent-hover);
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .status-active { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .status-inactive { background: rgba(220, 38, 38, 0.15); color: #ef4444; }
    </style>

    <div class="b2b-layout">
        <!-- Sidebar -->
        <div class="b2b-sidebar">
            <div class="b2b-logo">
                <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" style="width: 100%; max-width: 180px; display: block; margin-bottom: 8px;">
                <div style="font-size: 0.85rem; color: #64748b; font-weight: 500; margin-left: 5px;">Panel de Socio B2B</div>
            </div>
            
            <button wire:click="setTab('dashboard')" class="b2b-nav-item {{ $currentTab === 'dashboard' ? 'active' : '' }}">
                <i class="fas fa-chart-line fa-fw"></i> Dashboard
            </button>
            <button wire:click="setTab('pedidos')" class="b2b-nav-item {{ $currentTab === 'pedidos' ? 'active' : '' }}">
                <i class="fas fa-box fa-fw"></i> Pedidos Asignados
            </button>
            <button wire:click="setTab('catalog')" class="b2b-nav-item {{ $currentTab === 'catalog' ? 'active' : '' }}">
                <i class="fas fa-folder-open fa-fw"></i> Mi Catálogo
            </button>
            <button wire:click="setTab('upload')" class="b2b-nav-item {{ $currentTab === 'upload' ? 'active' : '' }}">
                <i class="fas fa-upload fa-fw"></i> Actualizar Stock (CSV)
            </button>
            
            <div style="flex: 1;"></div>
            
            <div class="px-4 py-3" style="border-top: 1px solid #1e2d3d;">
                <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-building-circle-check"></i> {{ \Illuminate\Support\Str::limit($provider->business_name, 20) }}
                </div>
                <button wire:click="logout" class="b2b-nav-item" style="color: var(--accent-red); padding: 8px 0; border: none !important;">
                    <i class="fas fa-sign-out-alt fa-fw"></i> Cerrar Sesión
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="b2b-main">
            
            @if (session()->has('success'))
                <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #4ade80; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #f87171; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="b2b-topbar">
                <div>
                    <h1>Buen día, {{ $provider->business_name }}</h1>
                    <p style="color: #64748b; font-size: 0.9rem;">RUC: {{ $provider->ruc }} | Especialidad: {{ $provider->specialty }}</p>
                </div>
                <div>
                    <span class="b2b-badge-live">● CONEXIÓN B2B ACTIVA</span>
                </div>
            </div>

            <!-- DASHBOARD TAB -->
            @if($currentTab === 'dashboard')
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="b2b-card" style="margin-bottom: 0;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Pedidos Activos</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #fff;">{{ $pendingItems->count() }}</div>
                </div>
                <div class="b2b-card" style="margin-bottom: 0;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Productos en Catálogo</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #fff;">{{ $products->total() }}</div>
                </div>
            </div>

            <div class="b2b-card">
                <h2>Últimas Asignaciones (Pendientes de Envío)</h2>
                <table class="b2b-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingItems->take(5) as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #fff;">{{ $item->product->name }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">SKU: {{ $item->product->supplier_code }} | Orden #{{ $item->pedido->id }}</div>
                                </td>
                                <td style="font-weight: 600;">{{ $item->cantidad }}</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 30px; color: #64748b;">No tienes asignaciones pendientes recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- PEDIDOS TAB -->
            @if($currentTab === 'pedidos')
            <div class="b2b-card">
                <h2>Pedidos Asignados para Despacho</h2>
                <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 24px;">Estos son los repuestos que los clientes han solicitado. Por favor, prepara el despacho.</p>
                <table class="b2b-table">
                    <thead>
                        <tr>
                            <th>Orden ID</th>
                            <th>Producto / SKU</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingItems as $item)
                            <tr>
                                <td><span style="color: var(--accent-red); font-weight: 600;">#{{ str_pad($item->pedido->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div style="font-weight: 600; color: #fff;">{{ $item->product->name }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b;">SKU: {{ $item->product->supplier_code }}</div>
                                </td>
                                <td style="font-weight: 600;">{{ $item->cantidad }}</td>
                                <td><span class="status-badge status-pending">Pendiente de Envío</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">No hay pedidos pendientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- CATALOGO TAB -->
            @if($currentTab === 'catalog')
            <div class="b2b-card">
                <h2>Mi Catálogo Activo</h2>
                <table class="b2b-table">
                    <thead>
                        <tr>
                            <th>SKU Proveedor</th>
                            <th>Nombre del Producto</th>
                            <th>Precio (S/)</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td><span style="font-family: monospace; color: #94a3b8;">{{ $product->supplier_code }}</span></td>
                                <td style="font-weight: 500;">{{ $product->name }}</td>
                                <td><span style="color: #4ade80; font-weight: 600;">S/ {{ number_format($product->price, 2) }}</span></td>
                                <td>
                                    @if($product->is_active)
                                        <span class="status-badge status-active">Activo</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactivo / Agotado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">Catálogo vacío. Sube tu CSV para sincronizar stock.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    {{ $products->links() }}
                </div>
            </div>
            @endif

            <!-- UPLOAD TAB -->
            @if($currentTab === 'upload')
            <div class="b2b-card">
                <h2>Actualizar Stock Masivo (CSV)</h2>
                <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 24px;">
                    Para sincronizar tu inventario con RepuestoFijo, sube tu archivo CSV. Las columnas obligatorias son: <br>
                    <code style="background: #121b26; padding: 4px 8px; border-radius: 4px; color: var(--accent-red); margin-top: 8px; display: inline-block;">sku_proveedor, price, stock</code>
                </p>
                
                <form wire:submit.prevent="uploadInventory">
                    <div class="b2b-upload-zone" onclick="document.getElementById('csvInput').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #334155; margin-bottom: 16px;"></i>
                        <h3 style="font-size: 1.1rem; margin-bottom: 8px; color: #fff;">Selecciona tu archivo CSV</h3>
                        <p style="margin: 0;">o arrástralo y suéltalo aquí</p>
                        
                        @if($csvFile)
                            <div style="margin-top: 20px; padding: 12px; background: rgba(230, 57, 70, 0.1); border: 1px solid var(--accent-red); border-radius: 8px; display: inline-block;">
                                <i class="fas fa-file-csv" style="color: var(--accent-red); margin-right: 8px;"></i>
                                <span style="color: #fff; font-weight: 600;">{{ $csvFile->getClientOriginalName() }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <input type="file" wire:model="csvFile" id="csvInput" style="display: none;" accept=".csv">
                    @error('csvFile') <div style="color: #ef4444; margin-top: 8px; font-size: 0.85rem;">{{ $message }}</div> @enderror
                    
                    <div style="margin-top: 24px; text-align: right;">
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Procesar Archivo CSV</span>
                            <span wire:loading><i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>
