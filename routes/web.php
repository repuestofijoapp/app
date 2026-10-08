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
        Route::get('/Ayoro-sape-{secret}/reclamaciones', \App\Livewire\Admin\ReclamacionManagement::class)->name('admin.reclamaciones');
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

// Endpoint POST: guardar reclamación (max 10 intentos por minuto por IP)
Route::middleware(['throttle:10,1'])->post('/libro-de-reclamaciones', function (\Illuminate\Http\Request $request) {
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

    // Notificaciones por correo
    try {
        $fromEmail = config('mail.from.address', 'incidencias@repuestofijo.com');
        $fromName  = config('mail.from.name', 'Repuesto Fijo');
        $adminMail = env('MAIL_INCIDENCIAS_ADDRESS', 'incidencias@repuestofijo.com');

        // 1. Notificar al equipo de Repuesto Fijo
        \Mail::send([], [], function ($m) use ($validated, $code, $request, $fromEmail, $fromName, $adminMail) {
            $m->to($adminMail, 'Repuesto Fijo Incidencias')
              ->from($fromEmail, $fromName)
              ->subject("[RECLAMACIÓN] Nueva reclamación recibida: {$code}")
              ->html(
                  "<div style='font-family:Arial,sans-serif;max-width:600px;line-height:1.6;'>"
                . "<h2 style='color:#ff3b5c;margin-bottom:12px;'>Nueva reclamación recibida: {$code}</h2>"
                . "<p><strong>Reclamante:</strong> " . e($validated['nombre']) . "</p>"
                . "<p><strong>Email:</strong> " . e($validated['email']) . "</p>"
                . "<p><strong>Teléfono:</strong> " . e($validated['telefono']) . "</p>"
                . "<p><strong>Documento:</strong> " . e(strtoupper($request->input('tipo_doc', '—'))) . ": " . e($request->input('num_doc', '—')) . "</p>"
                . "<p><strong>Pedido:</strong> " . e($request->input('num_pedido', '—')) . "</p>"
                . "<p><strong>Tipo:</strong> " . e($validated['tipo_reclamacion']) . "</p>"
                . "<p><strong>Solución solicitada:</strong> " . e($validated['solucion_esperada']) . "</p>"
                . "<p><strong>Descripción:</strong><br>" . nl2br(e($validated['descripcion'])) . "</p>"
                . "<p><strong>IP:</strong> " . $request->ip() . "</p>"
                . "<hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>"
                . "<p><a href='" . url('/Ayoro-sape-' . env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026') . '/reclamaciones') . "' style='display:inline-block;background:#ff3b5c;color:#fff;padding:8px 16px;text-decoration:none;border-radius:6px;font-weight:bold;'>Ver en el panel de control</a></p>"
                . "</div>"
              );
        });

        // 2. Enviar constancia de recepción automática al cliente
        \Mail::send([], [], function ($m) use ($validated, $code, $fromEmail, $fromName) {
            $m->to($validated['email'], $validated['nombre'])
              ->from($fromEmail, $fromName)
              ->subject("Constancia de recepción de reclamación [{$code}] – Repuesto Fijo")
              ->html(
                  "<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;line-height:1.6;color:#333;'>"
                . "<div style='border-bottom:3px solid #ff3b5c;padding-bottom:12px;margin-bottom:20px;'>"
                . "<h2 style='color:#132530;margin:0;'>Repuesto Fijo</h2>"
                . "<p style='color:#666;font-size:13px;margin:4px 0 0;'>Libro de Reclamaciones Digital</p>"
                . "</div>"
                . "<p>Hola <strong>" . e($validated['nombre']) . "</strong>,</p>"
                . "<p>Te confirmamos que hemos recibido tu reclamación satisfactoriamente. A continuación te proporcionamos la constancia de registro:</p>"
                . "<div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin:20px 0;'>"
                . "<p style='margin:0 0 8px;'><strong>Código de seguimiento:</strong> <span style='font-family:monospace;font-size:16px;color:#ff3b5c;font-weight:bold;'>" . $code . "</span></p>"
                . "<p style='margin:0 0 8px;'><strong>Fecha de recepción:</strong> " . now()->format('d/m/Y H:i') . "</p>"
                . "<p style='margin:0 0 8px;'><strong>Tipo:</strong> " . e(ucfirst(str_replace('_', ' ', $validated['tipo_reclamacion']))) . "</p>"
                . "<p style='margin:0;'><strong>Solución esperada:</strong> " . e(ucfirst(str_replace('_', ' ', $validated['solucion_esperada']))) . "</p>"
                . "</div>"
                . "<p>Nuestro equipo revisará tu caso y te brindará una respuesta formal a este mismo correo en un plazo máximo de <strong>15 días hábiles</strong>, conforme a la normativa vigente.</p>"
                . "<p style='font-size:13px;color:#666;'>Si necesitas aportar información adicional, puedes responder directamente a este correo indicando tu código de seguimiento <strong>{$code}</strong>.</p>"
                . "<hr style='border:none;border-top:1px solid #eee;margin:24px 0;'>"
                . "<p style='font-size:12px;color:#999;margin:0;'>Atentamente,<br><strong>Equipo de Atención al Cliente — Repuesto Fijo</strong></p>"
                . "</div>"
              );
        });
    } catch (\Throwable $e) {
        \Log::error('reclamaciones.store mail error: ' . $e->getMessage());
    }

    return response()->json(['code' => $code, 'status' => 'ok']);
})->name('reclamaciones.store');

// Fallback route para capturar páginas no encontradas manteniendo la sesión y el usuario autenticado
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
