<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pedido;
use App\Models\Incidencia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationMailService
{
    protected string $fromEmail;
    protected string $fromName;
    protected string $supportEmail;

    public function __construct()
    {
        $this->fromEmail    = config('mail.from.address', 'pedidos@repuestofijo.com');
        $this->fromName     = config('mail.from.name', 'RepuestoFijo');
        $this->supportEmail = 'soporte@repuestofijo.com';
    }

    /**
     * 1. Bienvenida al completar Onboarding
     */
    public function sendWelcome(User $user): bool
    {
        if (empty($user->email)) return false;

        try {
            Mail::send('emails.bienvenida', ['user' => $user], function ($m) use ($user) {
                $m->to($user->email, $user->name)
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject('¡Bienvenido a RepuestoFijo! - Tu cuenta está activa');
            });
            Log::info("Email Bienvenida enviado a: {$user->email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Bienvenida a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 2. Confirmación de compra y pago exitoso
     */
    public function sendPaymentConfirmed(Pedido $pedido): bool
    {
        $email = $pedido->customer?->email;
        if (empty($email)) return false;

        try {
            $pedido->loadMissing(['customer', 'items.product']);

            Mail::send('emails.pedido_pagado', ['pedido' => $pedido], function ($m) use ($pedido, $email) {
                $m->to($email, $pedido->customer?->name ?? 'Cliente')
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject("Pago confirmado - Pedido #{$pedido->id} | RepuestoFijo");
            });
            Log::info("Email Pago Confirmado enviado para Pedido #{$pedido->id} a: {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Pago Confirmado #{$pedido->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 3. Pedido en camino (Despacho / Hito 3)
     */
    public function sendOrderInRoute(Pedido $pedido): bool
    {
        $email = $pedido->customer?->email;
        if (empty($email)) return false;

        try {
            $pedido->loadMissing(['customer']);

            Mail::send('emails.pedido_en_camino', ['pedido' => $pedido], function ($m) use ($pedido, $email) {
                $m->to($email, $pedido->customer?->name ?? 'Cliente')
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject("¡Tu pedido #{$pedido->id} está en camino! 🛵 | RepuestoFijo");
            });
            Log::info("Email Pedido en Camino enviado para Pedido #{$pedido->id} a: {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Pedido en Camino #{$pedido->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 4. Pedido entregado (Hito 4)
     */
    public function sendOrderDelivered(Pedido $pedido): bool
    {
        $email = $pedido->customer?->email;
        if (empty($email)) return false;

        try {
            $pedido->loadMissing(['customer']);

            Mail::send('emails.pedido_entregado', ['pedido' => $pedido], function ($m) use ($pedido, $email) {
                $m->to($email, $pedido->customer?->name ?? 'Cliente')
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject("Pedido #{$pedido->id} entregado con éxito ✅ | RepuestoFijo");
            });
            Log::info("Email Pedido Entregado enviado para Pedido #{$pedido->id} a: {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Pedido Entregado #{$pedido->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 5. Incidencia / Reclamo creado
     */
    public function sendIncidentCreated(Incidencia $incidencia): bool
    {
        $email = $incidencia->customer?->email;
        if (empty($email)) return false;

        try {
            $incidencia->loadMissing(['customer', 'pedido']);

            Mail::send('emails.incidencia_creada', ['incidencia' => $incidencia], function ($m) use ($incidencia, $email) {
                $m->to($email, $incidencia->customer?->name ?? 'Cliente')
                  ->cc('soporte@repuestofijo.com', 'Soporte RepuestoFijo')
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject("Reclamo registrado [{$incidencia->codigo}] - Pedido #{$incidencia->pedido_id} | RepuestoFijo");
            });
            Log::info("Email Incidencia Creada enviado para [{$incidencia->codigo}] a: {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Incidencia Creada [{$incidencia->codigo}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 6. Incidencia resuelta / actualizada
     */
    public function sendIncidentResolved(Incidencia $incidencia, string $nuevoEstado, ?string $notaCliente = null): bool
    {
        $email = $incidencia->customer?->email;
        if (empty($email)) return false;

        try {
            $incidencia->loadMissing(['customer', 'pedido']);

            $estadoLabel = match ($nuevoEstado) {
                'resuelta'   => 'Caso Resuelto',
                'rechazada'  => 'Reclamo Rechazado',
                'en_proceso' => 'En Auditoría / Proceso',
                default      => ucfirst($nuevoEstado),
            };

            $estadoColor = match ($nuevoEstado) {
                'resuelta'   => '#10b981',
                'rechazada'  => '#ef4444',
                'en_proceso' => '#f59e0b',
                default      => '#64748b',
            };

            Mail::send('emails.incidencia_resuelta', [
                'incidencia'  => $incidencia,
                'estadoLabel' => $estadoLabel,
                'estadoColor' => $estadoColor,
                'notaCliente' => $notaCliente,
            ], function ($m) use ($incidencia, $email, $estadoLabel) {
                $m->to($email, $incidencia->customer?->name ?? 'Cliente')
                  ->from($this->fromEmail, $this->fromName)
                  ->replyTo($this->supportEmail, 'Soporte RepuestoFijo')
                  ->subject("{$estadoLabel}: Reclamo [{$incidencia->codigo}] | RepuestoFijo");
            });
            Log::info("Email Incidencia Resuelta enviado para [{$incidencia->codigo}] a: {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando email Incidencia Resuelta [{$incidencia->codigo}]: " . $e->getMessage());
            return false;
        }
    }
}
