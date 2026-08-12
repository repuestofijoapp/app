<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RucService
{
    protected $baseUrl = 'https://api.json.pe/v1';
    protected $token;

    public function __construct()
    {
        $this->token = '8c968ac90a015ba176926ebf65af4cbd3bce72384c6f08b2167d0ec164f8';
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
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("https://api.json.pe/api/ruc", [
                'ruc' => $ruc
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Estructura según docs: { success: true, data: { ruc, nombre_o_razon_social, ... } }
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    return $data['data'];
                }
            }

            Log::warning('RUC consultation failed', [
                'ruc' => $ruc,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('RUC consultation exception', [
                'ruc' => $ruc,
                'error' => $e->getMessage()
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
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("https://api.json.pe/api/dni", [
                'dni' => $dni
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    return $data['data'];
                }
            }

            Log::warning('DNI consultation failed', [
                'dni' => $dni,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('DNI consultation exception', [
                'dni' => $dni,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
}
