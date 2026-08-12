<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VehicleDataService
{
    protected $placaApiUrl = 'https://www.placaapi.co/api/reg.asmx/CheckPeru';
    protected $verifikApiUrl = 'https://api.verifik.co/v2/pe/vehiculo/placa';
    protected $placaApiUsername;
    protected $verifikApiKey;

    public function __construct()
    {
        $this->placaApiUsername = config('services.placaapi.username');
        $this->verifikApiKey = config('services.verifik.key');
    }

    /**
     * Search for vehicle data by plate number
     * First tries local database, then external APIs
     */
    public function searchVehicleByPlate(string $plate): ?Vehicle
    {
        // Clean and format plate
        $plate = $this->formatPlate($plate);

        // Check local database first
        $vehicle = Vehicle::where('plate', $plate)->first();
        if ($vehicle) {
            return $vehicle;
        }

        // Try external APIs
        $externalData = $this->fetchExternalVehicleData($plate);
        if ($externalData) {
            return $this->createVehicleFromExternalData($externalData);
        }

        return null;
    }

    /**
     * Fetch vehicle data from external APIs
     */
    protected function fetchExternalVehicleData(string $plate): ?array
    {
        // Try Verifik API first (more reliable)
        $verifikData = $this->fetchFromVerifik($plate);
        if ($verifikData) {
            return $verifikData;
        }

        // Fallback to PlacaAPI
        $placaApiData = $this->fetchFromPlacaAPI($plate);
        if ($placaApiData) {
            return $placaApiData;
        }

        return null;
    }

    /**
     * Fetch data from Verifik API
     */
    protected function fetchFromVerifik(string $plate): ?array
    {
        if (!$this->verifikApiKey) {
            Log::warning('Verifik API key not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->verifikApiKey,
                'Content-Type' => 'application/json',
            ])->get($this->verifikApiUrl, [
                'plate' => $plate
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Transform Verifik response to our format
                return [
                    'plate' => $plate,
                    'vin' => $data['vin'] ?? null,
                    'engine_code' => $data['engineSerialNumber'] ?? null,
                    'brand' => $data['make'] ?? null,
                    'model' => $data['model'] ?? null,
                    'year' => $data['year'] ?? null,
                    'source' => 'verifik'
                ];
            }

            Log::warning('Verifik API error', [
                'plate' => $plate,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Verifik API exception', [
                'plate' => $plate,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Fetch data from PlacaAPI
     */
    protected function fetchFromPlacaAPI(string $plate): ?array
    {
        if (!$this->placaApiUsername) {
            Log::warning('PlacaAPI username not configured');
            return null;
        }

        try {
            $response = Http::get($this->placaApiUrl, [
                'RegistrationNumber' => $plate,
                'username' => $this->placaApiUsername
            ]);

            if ($response->successful()) {
                // PlacaAPI returns SOAP XML, need to parse it
                $xml = simplexml_load_string($response->body());
                $json = json_encode($xml);
                $data = json_decode($json, true);

                if (isset($data['Make'])) {
                    return [
                        'plate' => $plate,
                        'vin' => $data['VIN'] ?? null,
                        'engine_code' => null, // PlacaAPI doesn't provide engine code
                        'brand' => $data['Make'] ?? null,
                        'model' => $data['Model'] ?? null,
                        'year' => $data['RegistrationYear'] ?? null,
                        'source' => 'placaapi'
                    ];
                }
            }

            Log::warning('PlacaAPI error', [
                'plate' => $plate,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('PlacaAPI exception', [
                'plate' => $plate,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Create Vehicle model from external API data
     */
    protected function createVehicleFromExternalData(array $data): Vehicle
    {
        return Vehicle::create([
            'plate' => $data['plate'],
            'vin' => $data['vin'],
            'engine_code' => $data['engine_code'],
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => $data['year'],
        ]);
    }

    /**
     * Format plate number (remove dashes, uppercase, etc.)
     */
    protected function formatPlate(string $plate): string
    {
        return strtoupper(str_replace(['-', ' '], '', trim($plate)));
    }

    /**
     * Search vehicles by partial plate match
     */
    public function searchVehiclesByPartialPlate(string $partialPlate): array
    {
        $partialPlate = $this->formatPlate($partialPlate);

        return Vehicle::where('plate', 'LIKE', '%' . $partialPlate . '%')
                     ->limit(10)
                     ->get()
                     ->toArray();
    }
}