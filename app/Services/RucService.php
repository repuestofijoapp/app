<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RucService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.jsonpe.base_url', 'https://api.json.pe');
        $this->token   = trim(config('services.jsonpe.token') ?: env('JSONPE_TOKEN', ''));
    }

    /**
     * Consulta un RUC en json.pe
     */
    public function consultRuc(string $ruc): ?array
    {
        if (strlen($ruc) !== 11) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post("{$this->baseUrl}/api/ruc", [
                'ruc' => $ruc
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Estructura: { success: true, data: { ruc, nombre_o_razon_social, ... } }
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    return $data['data'];
                }
            }

            Log::warning('RUC consultation failed', [
                'ruc'      => $ruc,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('RUC consultation exception', [
                'ruc'   => $ruc,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Consulta un DNI en json.pe
     */
    public function consultDni(string $dni): ?array
    {
        if (strlen($dni) !== 8) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post("{$this->baseUrl}/api/dni", [
                'dni' => $dni
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    return $data['data'];
                }
            }

            Log::warning('DNI consultation failed', [
                'dni'      => $dni,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('DNI consultation exception', [
                'dni'   => $dni,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Consulta un Carnet de Extranjería en json.pe
     * Respuesta: { success, data: { numero, nombres, apellido_paterno, apellido_materno } }
     *
     * @param  string $ce  Número del carnet (puede tener de 7 a 12 caracteres)
     * @return array|null  El array 'data' de la API, o null en caso de error
     */
    public function consultCe(string $ce): ?array
    {
        $ce = trim($ce);

        if (strlen($ce) < 7 || strlen($ce) > 12) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post("{$this->baseUrl}/api/ce", [
                'ce' => $ce
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Estructura: { success: true, data: { numero, nombres, apellido_paterno, apellido_materno } }
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    // Normalizar: construir nombre_completo igual que DNI para reutilizar la misma lógica
                    $d = $data['data'];
                    $d['nombre_completo'] = trim(
                        ($d['nombres'] ?? '') . ' ' .
                        ($d['apellido_paterno'] ?? '') . ' ' .
                        ($d['apellido_materno'] ?? '')
                    );
                    return $d;
                }
            }

            Log::warning('CE consultation failed', [
                'ce'       => $ce,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('CE consultation exception', [
                'ce'    => $ce,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
