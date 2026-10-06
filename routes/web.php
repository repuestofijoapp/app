<?php

// Internal product autocomplete — requires authenticated session
Route::middleware('auth')->get('/api/search-products', function(Illuminate\Http\Request $request) {
    $q = $request->query('q');
    return \App\Models\Product::where('name', 'like', "%$q%")
        ->orWhere('brand', 'like', "%$q%")
        ->orWhere('supplier_code', 'like', "%$q%")
        ->where('is_active', true)
        ->limit(10)
        ->get();
});

// Inventory upload — only accessible to authenticated admin/manager users
Route::middleware(['auth', 'admin.security'])->post('/inventory/upload', [\App\Http\Controllers\InventoryController::class, 'uploadCSV'])->name('inventory.upload');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Enums\UserRole;
use App\Livewire\SearchComponents\MainSearch;

Route::get('/', MainSearch::class)->name('home');
Route::get('/onboarding', \App\Livewire\Auth\Onboarding::class)->name('onboarding')->middleware('auth');

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.auth');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

use App\Http\Controllers\Auth\LoginController;
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});



// Auth routes
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    if (auth()->check() && auth()->user()->canAccessDashboard()) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard genérico (fallback)
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Dashboard mecánico
    Route::get('/mechanic/dashboard', function () {
        if (auth()->user()->role !== UserRole::Mechanic) {
            return redirect()->route('home');
        }
        return view('mechanic.dashboard');
    })->name('mechanic.dashboard');

    // HIDDEN ADMIN ROUTES
    Route::middleware(['admin.security'])->group(function () {
        Route::get('/Ayoro-sape-{secret}/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/Ayoro-sape-{secret}/profile', \App\Livewire\Admin\Profile::class)->name('admin.profile');
        Route::get('/Ayoro-sape-{secret}/users', \App\Livewire\Admin\UserManagement::class)->name('admin.users');
        Route::get('/Ayoro-sape-{secret}/providers', \App\Livewire\Admin\ProviderManagement::class)->name('admin.providers');
        Route::get('/Ayoro-sape-{secret}/products', \App\Livewire\Admin\ProductManagement::class)->name('admin.products');
        Route::get('/Ayoro-sape-{secret}/diagnostico', \App\Livewire\Admin\CatalogDiagnostic::class)->name('admin.catalog-diagnostic');
        Route::get('/Ayoro-sape-{secret}/zettabot', \App\Livewire\Admin\ZettaBotSettings::class)->name('admin.zettabot');
        Route::get('/Ayoro-sape-{secret}/pedidos', \App\Livewire\Admin\PedidoManagement::class)->name('admin.pedidos');
        Route::get('/Ayoro-sape-{secret}/en-vivo', \App\Livewire\Admin\EnVivo::class)->name('admin.en-vivo');
        Route::get('/Ayoro-sape-{secret}/soporte', \App\Livewire\Admin\SoporteManagement::class)->name('admin.soporte');
        Route::get('/Ayoro-sape-{secret}/vehiculos', \App\Livewire\Admin\VehicleManagement::class)->name('admin.vehicles');
        Route::get('/Ayoro-sape-{secret}/access-logs', \App\Livewire\Admin\AccessLogs::class)->name('admin.access-logs');
        Route::get('/Ayoro-sape-{secret}/security-alerts', \App\Livewire\Admin\SecurityAlerts::class)->name('admin.security-alerts');
        Route::get('/Ayoro-sape-{secret}/configuracion', \App\Livewire\Admin\SystemSettings::class)->name('admin.system-settings');
        Route::get('/Ayoro-sape-{secret}/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('admin.logs');
    });
});

// B2B Portal Routes
Route::get('/b2b/acceso', \App\Livewire\B2bLogin::class)->name('b2b.login');
Route::middleware(['provider.auth'])->group(function () {
    Route::get('/b2b/portal', \App\Livewire\B2bPortal::class)->name('b2b.portal');
});

// Green API Webhook (Publicly reachable — CSRF excluded in bootstrap/app.php)
use App\Http\Controllers\Api\WhatsApp\GreenApiWebhookController;
Route::post('/webhooks/green-api', [GreenApiWebhookController::class, 'handle'])->name('webhooks.green-api');

// Culqi Payment Webhook (Publicly reachable — CSRF excluded in bootstrap/app.php)
// Route::post('/webhooks/culqi', [\App\Http\Controllers\Api\CulqiWebhookController::class, 'handle'])->name('webhooks.culqi');

// Confirmación de Stock interactiva para Proveedores (Opción 3)
use App\Http\Controllers\Provider\ConfirmStockController;
Route::get('/proveedor/confirmar/{token}', [ConfirmStockController::class, 'show'])->name('provider.confirm.show');
Route::post('/proveedor/confirmar/{token}', [ConfirmStockController::class, 'submit'])->name('provider.confirm.submit');

// ── PÁGINAS LEGALES (Públicas) ──────────────────────────────────────────────
Route::get('/privacidad', fn() => view('legal.privacidad'))->name('legal.privacidad');
Route::get('/terminos', fn() => view('legal.terminos'))->name('legal.terminos');
Route::get('/libro-de-reclamaciones', fn() => view('legal.reclamaciones'))->name('legal.reclamaciones');

// Endpoint POST: guardar reclamación
Route::post('/libro-de-reclamaciones', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nombre'            => 'required|string|max:200',
        'email'             => 'required|email|max:200',
        'telefono'          => 'required|string|max:20',
        'tipo_reclamacion'  => 'required|string|max:100',
        'descripcion'       => 'required|string|min:30',
        'solucion_esperada' => 'required|string|max:100',
    ]);

    $code = 'RF-' . strtoupper(substr(md5(microtime(true) . $validated['email']), 0, 8));

    \DB::table('reclamaciones')->insert([
        'code'              => $code,
        'nombre'            => $validated['nombre'],
        'email'             => $validated['email'],
        'telefono'          => $validated['telefono'],
        'tipo_doc'          => $request->input('tipo_doc'),
        'num_doc'           => $request->input('num_doc'),
        'num_pedido'        => $request->input('num_pedido'),
        'fecha_pedido'      => $request->input('fecha_pedido') ?: null,
        'tipo_reclamacion'  => $validated['tipo_reclamacion'],
        'descripcion'       => $validated['descripcion'],
        'solucion_esperada' => $validated['solucion_esperada'],
        'user_id'           => auth()->id(),
        'ip_address'        => $request->ip(),
        'estado'            => 'pendiente',
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);

    return response()->json(['code' => $code, 'status' => 'ok']);
})->name('reclamaciones.store');

// Fallback route para capturar páginas no encontradas manteniendo la sesión y el usuario autenticado
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
