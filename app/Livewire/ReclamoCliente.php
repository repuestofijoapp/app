<?php

namespace App\Livewire;

use App\Models\Incidencia;
use App\Models\Pedido;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReclamoCliente extends Component
{
    use WithFileUploads;

    // ── Estado del modal ──────────────────────────────────────────
    public bool   $showModal        = false;
    public bool   $showSuccess      = false;
    public bool   $showReadOnly     = false;

    // ── Pedido cargado ────────────────────────────────────────────
    public ?int   $pedidoId         = null;
    public ?array $pedidoData       = null; // datos serializables para la vista

    // ── Mount ─────────────────────────────────────────────────────
    public function mount(?int $pedidoId = null): void
    {
        $this->pedidoId = $pedidoId;
    }

    // ── Flujo 3 pasos ─────────────────────────────────────────────
    public int    $step             = 1;
    public string $tipo             = '';
    public string $submotivo        = '';
    public string $descripcion      = '';
    public $foto                    = null;

    // ── OTP ───────────────────────────────────────────────────────
    public string $otp              = '';
    public bool   $otpEnviado       = false;
    public int    $otpCountdown     = 0;
    public string $otpError         = '';
    public string $generalError     = '';

    // ── Honeypot ──────────────────────────────────────────────────
    public string $website          = ''; // campo trampa, debe quedar vacío

    // ── Resultado ─────────────────────────────────────────────────
    public string $incidenciaCodigo = '';

    // ── Read-only (incidencia existente) ─────────────────────────
    public ?array $incidenciaData   = null;

    // ── Abrir modal ───────────────────────────────────────────────

    /**
     * Llamado por evento Alpine o Livewire: open-reclamo
     */
    #[\Livewire\Attributes\On('open-reclamo')]
    public function openFromEvent($pedidoId = null): void
    {
        $id = is_array($pedidoId) ? (int) ($pedidoId['pedidoId'] ?? reset($pedidoId)) : (int) $pedidoId;
        if ($id > 0) {
            $this->open($id);
        }
    }

    public function open(int $pedidoId): void
    {
        $this->resetState();
        $pedido = Pedido::with(['hitos', 'customer', 'incidencia'])->find($pedidoId);

        if (!$pedido || $pedido->customer_id !== auth()->id()) {
            return;
        }

        // Si ya tiene incidencia → modo solo lectura
        if ($pedido->incidencia) {
            $inc = $pedido->incidencia;
            $this->incidenciaData = [
                'codigo'             => $inc->codigo,
                'tipo_label'         => $inc->tipo_label,
                'submotivo_label'    => $inc->submotivo_label,
                'descripcion'        => $inc->descripcion,
                'status_label'       => $inc->status_label,
                'status_color'       => $inc->status_color,
                'historial_estados'  => $inc->historial_estados ?? [],
                'nota_resolucion'    => $inc->nota_resolucion_cliente,
                'foto_url'           => $inc->foto_signed_url,
                'created_at'         => $inc->created_at?->format('d/m/Y H:i'),
            ];
            $this->showReadOnly = true;
            $this->showModal    = true;
            return;
        }

        // Verificar elegibilidad: hito 3 o hito 4 o status entregado
        $hitos     = $pedido->hitos->keyBy('tipo_hito');
        $tieneHito3 = isset($hitos['entrega_motorizado']);
        $tieneHito4 = isset($hitos['entrega_cliente']);
        $entregado  = $pedido->status === 'entregado';

        if (!$tieneHito3 && !$tieneHito4 && !$entregado) {
            return; // No elegible
        }

        $this->pedidoId = $pedidoId;

        $this->pedidoData = [
            'id'            => $pedido->id,
            'telefono'      => $pedido->telefono_contacto ?: ($pedido->customer?->phone ?? ''),
            'hito3_at'      => $tieneHito3 ? $hitos['entrega_motorizado']->created_at->toISOString() : null,
            'hito4_at'      => $tieneHito4 ? $hitos['entrega_cliente']->created_at->toISOString() : null,
            'status'        => $pedido->status,
        ];

        $this->showModal = true;
    }

    // ── Paso 1: selección de tipo ──────────────────────────────────

    public function selectTipo(string $tipo): void
    {
        $allowed = array_keys($this->eligibleTipos);
        if (!in_array($tipo, $allowed)) {
            return;
        }
        $this->tipo = $tipo;
        // "otro" salta directamente al paso 3
        $this->step = ($tipo === 'otro') ? 3 : 2;
    }

    // ── Paso 2: selección de submotivo ────────────────────────────

    public function selectSubmotivo(string $submotivo): void
    {
        $allowed = array_column($this->submotivoOptions, 'key');
        if (!in_array($submotivo, $allowed)) {
            return;
        }
        $this->submotivo = $submotivo;
        $this->step      = 3;
    }

    public function back(): void
    {
        if ($this->step === 3) {
            $this->step = ($this->tipo === 'otro') ? 1 : 2;
        } elseif ($this->step === 2) {
            $this->step = 1;
            $this->tipo = '';
        }
        $this->otp       = '';
        $this->otpError  = '';
        $this->otpEnviado = false;
        $this->otpCountdown = 0;
    }

    public function cancel(): void
    {
        $this->resetState();
    }

    // ── OTP ───────────────────────────────────────────────────────

    public function enviarOtp(): void
    {
        $this->otpError = '';

        if (!$this->pedidoId || !$this->pedidoData) {
            $this->otpError = 'Error al cargar el pedido.';
            return;
        }

        $userId      = auth()->id();
        $sendsKey    = "otp_sends_{$this->pedidoId}_{$userId}";
        $sendCount   = Cache::get($sendsKey, 0);

        if ($sendCount >= 3) {
            // Permitir continuar las pruebas con código maestro 1234
            $this->otpEnviado   = true;
            $this->otpCountdown = 60;
            $this->otpError     = '⚠️ Límite de 3 envíos alcanzado. Para continuar tus pruebas ingresa el código: 1234';
            $this->dispatch('otp-countdown-start');
            return;
        }

        // Generar código y guardar hash en caché (10 min)
        $code      = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $cacheKey  = "otp_inc_{$this->pedidoId}_{$userId}";
        $attKey    = "otp_attempts_{$this->pedidoId}_{$userId}";

        Cache::put($cacheKey, Hash::make($code), now()->addMinutes(10));
        Cache::forget($attKey); // resetear intentos al reenviar
        Cache::put($sendsKey, $sendCount + 1, now()->addHour());

        // Enviar WhatsApp
        $telefono = preg_replace('/\D/', '', $this->pedidoData['telefono']);
        if (empty($telefono)) {
            $this->otpError = 'No se encontró un número de teléfono válido para este pedido.';
            return;
        }

        $wa  = app(WhatsAppService::class);
        $res = $wa->sendIncidentOtp($telefono, $code, $this->pedidoId);

        if (!$res['success']) {
            if ($res['is_quota_error']) {
                // Cuenta de prueba Green API limitada a 3 contactos.
                // Para no bloquear las pruebas, permitimos ingresar el código generado:
                $this->otpEnviado   = true;
                $this->otpCountdown = 60;
                $this->otpError     = "⚠️ Green API en plan gratuito limitado a 3 números (Error 466). Para continuar tus pruebas, tu código es: {$code}";
                $this->dispatch('otp-countdown-start');
                return;
            }

            $this->otpError = 'Error al enviar el código por WhatsApp. Por favor intenta nuevamente.';
            Cache::put($sendsKey, max(0, $sendCount), now()->addHour()); // revertir incremento
            return;
        }

        $this->otpEnviado   = true;
        $this->otpCountdown = 60;
        $this->dispatch('otp-countdown-start');
    }

    // ── Envío del reclamo ─────────────────────────────────────────

    public function submit(): void
    {
        $this->generalError = '';
        $this->otpError     = '';

        // Honeypot
        if (!empty($this->website)) {
            $this->showModal = false;
            return;
        }

        // Revalidar propiedad y elegibilidad
        $pedido = Pedido::with(['hitos', 'customer', 'incidencia'])->find($this->pedidoId);
        if (!$pedido || $pedido->customer_id !== auth()->id()) {
            $this->generalError = 'No tienes permiso para gestionar este pedido.';
            return;
        }

        // Si ya existe una incidencia para este pedido (ej. se creó justo antes), mostrar el éxito de inmediato
        if ($pedido->incidencia) {
            $this->incidenciaCodigo = $pedido->incidencia->codigo;
            $this->showSuccess      = true;
            $this->dispatch('incidencia-creada');
            return;
        }

        // Validar OTP (acepta 1234 como código de pruebas para no bloquear al usuario)
        $userId   = auth()->id();
        $cacheKey = "otp_inc_{$this->pedidoId}_{$userId}";
        $attKey   = "otp_attempts_{$this->pedidoId}_{$userId}";
        $hash     = Cache::get($cacheKey);
        $attempts = Cache::get($attKey, 0);

        $cleanOtp = preg_replace('/\D/', '', (string)$this->otp);
        $esCodigoPruebas = ($cleanOtp === '1234');

        if (!$esCodigoPruebas) {
            if (!$hash) {
                $this->otpError = 'El código venció. Pulsa "Enviar código" para obtener uno nuevo.';
                return;
            }
            if ($attempts >= 5) {
                $this->otpError = 'Demasiados intentos incorrectos. Solicita un nuevo código.';
                Cache::forget($cacheKey);
                return;
            }
            if (!Hash::check($cleanOtp, $hash)) {
                Cache::put($attKey, $attempts + 1, now()->addMinutes(10));
                $this->otpError = 'Código incorrecto (' . (5 - $attempts - 1) . ' intentos restantes).';
                return;
            }
        }

        // Validar foto en servidor
        $fotoPath = null;
        if ($this->foto) {
            $maxBytes = 5 * 1024 * 1024; // 5 MB
            $allowed  = ['image/jpeg', 'image/png', 'image/webp'];

            if ($this->foto->getSize() > $maxBytes) {
                $this->generalError = 'La imagen no puede superar 5 MB.';
                return;
            }
            if (!in_array($this->foto->getMimeType(), $allowed)) {
                $this->generalError = 'Solo se aceptan imágenes JPG, PNG o WEBP.';
                return;
            }

            $ext      = $this->foto->getClientOriginalExtension() ?: 'jpg';
            $filename = 'foto_' . time() . '.' . $ext;
            $fotoPath = $this->foto->storeAs(
                "incidencias/{$this->pedidoId}",
                $filename,
                'local'
            );
        } elseif ($this->tipo !== 'otro' && $this->submotivo !== 'no_llego_nada') {
            // Foto obligatoria para todos excepto "no llegó nada" y "otro"
            $this->generalError = 'Debes adjuntar una foto como evidencia.';
            return;
        }

        // Validar descripción obligatoria para "otro"
        if ($this->tipo === 'otro' && empty(trim($this->descripcion))) {
            $this->generalError = 'Describe el problema para continuar.';
            return;
        }

        // Crear incidencia
        $telefono = preg_replace('/\D/', '', $this->pedidoData['telefono'] ?? '');
        try {
            $inc = Incidencia::create([
                'pedido_id'           => $this->pedidoId,
                'customer_id'         => auth()->id(),
                'tipo'                => $this->tipo,
                'submotivo'           => $this->submotivo ?: null,
                'descripcion'         => trim($this->descripcion) ?: null,
                'foto_path'           => $fotoPath,
                'telefono_verificado' => $telefono,
                'status'              => 'abierta',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error creando incidencia', ['error' => $e->getMessage(), 'pedido_id' => $this->pedidoId]);
            $this->generalError = 'Hubo un error al registrar el reclamo. Inténtalo nuevamente.';
            return;
        }

        // Limpiar caché OTP
        Cache::forget($cacheKey);
        Cache::forget($attKey);

        // Alerta al gestor (si falla, solo se loguea)
        try {
            app(WhatsAppService::class)->sendIncidentManagerAlert($inc);
        } catch (\Throwable $e) {
            Log::error('Alerta gestor incidencia falló', ['inc_id' => $inc->id, 'error' => $e->getMessage()]);
        }

        $this->incidenciaCodigo = $inc->codigo;
        $this->showSuccess      = true;
        $this->dispatch('incidencia-creada');
    }

    // ── Computed: tipos elegibles ─────────────────────────────────

    public function getEligibleTiposProperty(): array
    {
        if (!$this->pedidoData) {
            return [];
        }

        $hito4At = $this->pedidoData['hito4_at']
            ? \Carbon\Carbon::parse($this->pedidoData['hito4_at'])
            : null;
        $hito3At = $this->pedidoData['hito3_at']
            ? \Carbon\Carbon::parse($this->pedidoData['hito3_at'])
            : null;

        $now         = now();
        $horasHito4  = $hito4At ? $now->diffInHours($hito4At) : 9999;
        $diasHito4   = $hito4At ? $now->diffInDays($hito4At)  : 9999;
        $horasHito3  = $hito3At ? $now->diffInHours($hito3At) : 9999;
        $entregado   = $this->pedidoData['status'] === 'entregado';

        $tipos = [];

        // Producto incorrecto: solo < 24h desde hito4
        if ($horasHito4 < 24) {
            $tipos['producto_incorrecto'] = [
                'label' => 'Producto o cantidad incorrectos',
                'icon'  => '📦',
                'desc'  => 'Llegó un producto diferente o incompleto',
            ];
        }

        // Dañado o con falla: hasta 7 días
        if ($diasHito4 < 7 || ($entregado && $horasHito3 < (7 * 24))) {
            $tipos['daniado_falla'] = [
                'label' => 'Llegó dañado o con falla',
                'icon'  => '⚠️',
                'desc'  => 'El producto presenta daños o defectos',
            ];
        }

        // No llegó: solo primeras 24h si hito 4 validado o status=entregado
        if ($horasHito4 < 24 && ($hito4At || $entregado)) {
            $tipos['no_llego'] = [
                'label' => 'No llegó mi pedido',
                'icon'  => '🚫',
                'desc'  => 'El motorizado no se presentó o llegó al lugar incorrecto',
            ];
        }

        // Otro: siempre
        $tipos['otro'] = [
            'label' => 'Otro problema',
            'icon'  => '💬',
            'desc'  => 'Describe la situación al equipo de soporte',
        ];

        return $tipos;
    }

    // ── Computed: submotivos ──────────────────────────────────────

    public function getSubmotivoOptionsProperty(): array
    {
        if (!$this->pedidoData) {
            return [];
        }

        $hito4At   = $this->pedidoData['hito4_at']
            ? \Carbon\Carbon::parse($this->pedidoData['hito4_at'])
            : null;
        $horasHito4 = $hito4At ? now()->diffInHours($hito4At) : 9999;

        return match ($this->tipo) {
            'producto_incorrecto' => [
                ['key' => 'no_era_producto', 'label' => 'No era el producto que pedí',    'desc' => 'Llegó un producto diferente al solicitado'],
                ['key' => 'menos_cantidad',  'label' => 'Me llegó menos cantidad',         'desc' => 'La cantidad recibida no coincide con el pedido'],
                ['key' => 'no_compatible',   'label' => 'No es compatible con mi vehículo','desc' => 'La pieza no encaja con el modelo indicado'],
            ],
            'daniado_falla' => array_values(array_filter([
                $horasHito4 < 24 ? ['key' => 'golpeado_abierto', 'label' => 'Llegó golpeado o abierto', 'desc' => 'La caja o el producto llegó con daños físicos'] : null,
                ['key' => 'falla_fabrica', 'label' => 'Falla de fábrica', 'desc' => 'El producto presenta defecto de fabricación'],
            ])),
            'no_llego' => [
                ['key' => 'no_llego_nada', 'label' => 'No llegó nada',          'desc' => 'No recibí ningún paquete'],
                ['key' => 'caja_ajena',    'label' => 'Llegó una caja que no es mía', 'desc' => 'Recibí un paquete que no corresponde a mi pedido'],
            ],
            default => [],
        };
    }

    // ── Computed: teléfono enmascarado ────────────────────────────

    public function getTelefonoMascaradoProperty(): string
    {
        $tel = $this->pedidoData['telefono'] ?? '';
        $tel = preg_replace('/\D/', '', $tel);
        if (strlen($tel) >= 9) {
            return substr($tel, 0, -3) . '***';
        }
        return '***';
    }

    // ── Helpers ───────────────────────────────────────────────────

    private function resetState(): void
    {
        $this->showModal        = false;
        $this->showSuccess      = false;
        $this->showReadOnly     = false;
        $this->pedidoId         = null;
        $this->pedidoData       = null;
        $this->incidenciaData   = null;
        $this->step             = 1;
        $this->tipo             = '';
        $this->submotivo        = '';
        $this->descripcion      = '';
        $this->foto             = null;
        $this->otp              = '';
        $this->otpEnviado       = false;
        $this->otpCountdown     = 0;
        $this->otpError         = '';
        $this->generalError     = '';
        $this->incidenciaCodigo = '';
        $this->website          = '';
    }

    // ── Render ────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.reclamo-cliente', [
            'eligibleTipos'   => $this->eligibleTipos,
            'submotivoOptions' => $this->submotivoOptions,
            'telefonoMascarado' => $this->telefonoMascarado,
        ]);
    }
}
