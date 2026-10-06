<?php

namespace App\Livewire\Auth;

use App\Enums\UserRole;
use App\Services\RucService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Onboarding extends Component
{
    public $step = 1;
    public $role = '';
    public $receiptType = '';

    // Factura (empresa)
    public $ruc = '';
    public $businessName = '';

    // Boleta — un único campo para DNI o Carnet de Extranjería
    public $doc = '';          // el número que escribe el usuario
    public $detectedDocType = null; // 'dni' | 'ce' | null  (detectado automáticamente)
    public $fullName = '';

    public $isConsulting = false;

    // ── Rate limiting ─────────────────────────────────────────────────────
    // Máx. 8 consultas por hora por usuario (evita scraping en el onboarding)
    private const MAX_ATTEMPTS = 8;
    private const DECAY_SECONDS = 3600; // 1 hora

    protected $rules = [
        'role'        => 'required',
        'receiptType' => 'required_if:step,2',
        'ruc'         => 'required_if:receiptType,factura|digits:11',
        'doc'         => 'required_if:receiptType,boleta',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->onboarding_completed_at ||
            auth()->user()->canAccessDashboard()) {
            return redirect()->route('home');
        }
    }

    // ── Helpers de rate limit ─────────────────────────────────────────────

    private function rateLimitKey(): string
    {
        return 'onboarding-doc:' . auth()->id();
    }

    private function checkRateLimit(string $field = 'doc'): bool
    {
        $key = $this->rateLimitKey();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            $this->addError($field, "Has superado el límite de consultas por seguridad. Vuelve a intentarlo en {$minutes} min.");
            return false;
        }

        RateLimiter::hit($key, self::DECAY_SECONDS);
        return true;
    }

    /** Cuántos intentos le quedan al usuario */
    public function getRemainingAttemptsProperty(): int
    {
        return RateLimiter::remaining($this->rateLimitKey(), self::MAX_ATTEMPTS);
    }

    // ── Navegación ────────────────────────────────────────────────────────

    public function setRole($role)
    {
        $this->role = $role;
        $this->step = 2;
    }

    public function setReceiptType($type)
    {
        $this->receiptType     = $type;
        $this->ruc             = '';
        $this->businessName    = '';
        $this->doc             = '';
        $this->fullName        = '';
        $this->detectedDocType = null;
        $this->resetValidation();
    }

    // ── RUC ──────────────────────────────────────────────────────────────

    public function updatedRuc($value)
    {
        $this->businessName = '';

        if (strlen($value) === 11) {
            $this->consultarRuc();
        }
    }

    public function consultarRuc()
    {
        $this->validate(['ruc' => 'required|digits:11']);

        if (!$this->checkRateLimit('ruc')) return;

        $this->isConsulting = true;
        try {
            $data = (new RucService())->consultRuc($this->ruc);

            if ($data) {
                $this->businessName = $data['nombre_o_razon_social'] ?? $data['nombre'] ?? '';
            } else {
                $this->addError('ruc', 'No se encontró el RUC. Verifica el número.');
            }
        } catch (\Exception $e) {
            $this->addError('ruc', 'Error al consultar la API. Intenta de nuevo.');
        } finally {
            $this->isConsulting = false;
        }
    }

    // ── Documento unificado (DNI / CE) ────────────────────────────────────

    /**
     * Se dispara cada vez que cambia el input $doc.
     * Auto-detecta el tipo y lanza la consulta cuando el largo es suficiente.
     */
    public function updatedDoc($value)
    {
        $this->fullName        = '';
        $this->detectedDocType = null;
        $this->resetValidation(['doc']);

        $clean = trim($value);
        $len   = strlen($clean);

        if ($len === 0) return;

        // Criterio de discriminación:
        // - Carnet de Extranjería: suele empezar con "00", tener letras, o tener entre 9 y 12 caracteres.
        // - DNI: exactamente 8 dígitos numéricos y NO empieza con "00" (RENIEC no emite DNIs con 00).
        $isCeFormat = str_starts_with($clean, '00') || !ctype_digit($clean) || $len > 8;

        if ($len === 8 && ctype_digit($clean) && !$isCeFormat) {
            $this->detectedDocType = 'dni';
            $this->consultarDoc();
        } elseif ($isCeFormat && $len >= 7 && $len <= 12) {
            $this->detectedDocType = 'ce';
            // Consultar automáticamente si ya tiene 9 o más caracteres, o si contiene letras
            if ($len >= 9 || !ctype_digit($clean)) {
                $this->consultarDoc();
            }
        }
    }

    /**
     * Permite consultar al presionar Enter o al perder el foco (wire:blur).
     */
    public function consultarDocManual()
    {
        $clean = trim($this->doc);
        $len   = strlen($clean);

        if ($len === 0) return;

        $isCeFormat = str_starts_with($clean, '00') || !ctype_digit($clean) || $len > 8;

        if ($len === 8 && ctype_digit($clean) && !$isCeFormat) {
            $this->detectedDocType = 'dni';
        } elseif ($len >= 7 && $len <= 12) {
            $this->detectedDocType = 'ce';
        } else {
            $this->addError('doc', 'El documento debe tener 8 dígitos (DNI) o entre 7-12 caracteres (Carnet).');
            return;
        }

        $this->consultarDoc();
    }

    private function consultarDoc()
    {
        if (!$this->checkRateLimit('doc')) return;

        $this->isConsulting = true;
        $this->fullName     = '';

        try {
            $service = new RucService();

            if ($this->detectedDocType === 'dni') {
                $data = $service->consultDni($this->doc);
                $errorMsg = 'No se encontró el DNI. Verifica el número.';
            } else {
                $data = $service->consultCe($this->doc);
                $errorMsg = 'No se encontró el Carnet de Extranjería. Verifica el número.';
            }

            if ($data) {
                $this->fullName = $data['nombre_completo'] ?? '';
            } else {
                $this->addError('doc', $errorMsg);
            }
        } catch (\Exception $e) {
            $this->addError('doc', 'Error al consultar la API. Intenta de nuevo.');
        } finally {
            $this->isConsulting = false;
        }
    }

    // ── Guardar ───────────────────────────────────────────────────────────

    public function completeOnboarding()
    {
        if ($this->receiptType === 'factura') {
            $this->validate([
                'ruc'          => 'required|digits:11',
                'businessName' => 'required',
            ]);
        } elseif ($this->receiptType === 'boleta') {
            $this->validate([
                'doc'      => 'required|min:7|max:12',
                'fullName' => 'required',
            ]);
        }

        $user = auth()->user();

        $newRole = match($this->role) {
            'workshop' => UserRole::Workshop,
            'store'    => UserRole::Store,
            default    => UserRole::Mechanic,
        };

        $user->update([
            'role'                    => $newRole,
            'receipt_type'            => $this->receiptType,
            'ruc_dni'                 => ($this->receiptType === 'factura')
                                            ? $this->ruc
                                            : (($this->receiptType === 'boleta') ? $this->doc : $user->ruc_dni),
            'business_name'           => ($this->receiptType === 'factura')
                                            ? $this->businessName
                                            : (($this->receiptType === 'boleta') ? $this->fullName : $user->business_name),
            'onboarding_completed_at' => now(),
        ]);

        return redirect()->route('home')->with('notify', [
            'type'    => 'success',
            'message' => 'Perfil completado correctamente. ¡Bienvenido!',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.onboarding')
            ->layout('layouts.app');
    }
}
