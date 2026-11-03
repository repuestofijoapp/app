<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class ZettaBotSettings extends Component
{
    public string $activeProvider = 'green_api';
    public bool $showGreenApiWarning = false;

    public function mount(): void
    {
        $this->activeProvider = SystemSetting::get('whatsapp_provider', 'green_api');
    }

    /**
     * Cambia el proveedor activo de WhatsApp.
     * Al cambiar a Green API muestra un aviso de sesión.
     */
    public function switchProvider(string $provider): void
    {
        if (!in_array($provider, ['green_api', 'apis_peru'])) return;

        SystemSetting::set('whatsapp_provider', $provider);

        // Limpiar cache de estado y token para que se re-evalúe con el nuevo proveedor
        Cache::forget('zettabot_status');
        Cache::forget('apis_peru_wa_token');

        $this->activeProvider = $provider;

        if ($provider === 'green_api') {
            $this->showGreenApiWarning = true;
        } else {
            $this->showGreenApiWarning = false;
            $this->dispatch('notify', ['type' => 'success', 'message' => '✅ Proveedor cambiado a APIs Perú']);
        }
    }

    public function dismissWarning(): void
    {
        $this->showGreenApiWarning = false;
    }

    public function render()
    {
        $greenStatus   = $this->activeProvider === 'green_api' ? app(\App\Services\WhatsAppService::class)->getStatus() : null;
        $apisPeruReady = !empty(env('APIS_PERU_DEVICE_ID'));

        return view('livewire.admin.zetta-bot-settings', [
            'activeProvider'      => $this->activeProvider,
            'showGreenApiWarning' => $this->showGreenApiWarning,
            'greenStatus'         => $greenStatus,
            'apisPeruReady'       => $apisPeruReady,
        ])->layout('layouts.app');
    }
}
