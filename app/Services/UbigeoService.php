<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class UbigeoService
{
    protected static ?array $data = null;

    /**
     * Cargar dataset completo de Ubigeo INEI
     */
    public static function getData(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        self::$data = Cache::rememberForever('peru_ubigeo_data', function () {
            $path = resource_path('data/ubigeo_peru.json');
            if (!file_exists($path)) {
                return [];
            }
            return json_decode(file_get_contents($path), true) ?: [];
        });

        return self::$data;
    }

    /**
     * Lista de Departamentos: ['01' => 'AMAZONAS', '15' => 'LIMA', ...]
     */
    public static function getDepartamentos(): array
    {
        $data = self::getData();
        $deps = [];
        foreach ($data as $code => $dep) {
            $deps[$code] = $dep['nombre'];
        }
        asort($deps);
        return $deps;
    }

    /**
     * Lista de Provincias para un departamento dado: ['1501' => 'LIMA', ...]
     */
    public static function getProvincias(string $depCode): array
    {
        $data = self::getData();
        if (!isset($data[$depCode]['provincias'])) {
            return [];
        }

        $provs = [];
        foreach ($data[$depCode]['provincias'] as $code => $prov) {
            $provs[$code] = $prov['nombre'];
        }
        asort($provs);
        return $provs;
    }

    /**
     * Lista de Distritos para una provincia dada: ['150101' => 'LIMA', '150134' => 'SAN MARTIN DE PORRES', ...]
     */
    public static function getDistritos(string $provCode): array
    {
        $depCode = substr($provCode, 0, 2);
        $data = self::getData();

        if (!isset($data[$depCode]['provincias'][$provCode]['distritos'])) {
            return [];
        }

        $distritos = $data[$depCode]['provincias'][$provCode]['distritos'];
        asort($distritos);
        return $distritos;
    }

    /**
     * Buscar información completa a partir de un código Ubigeo de 6 dígitos
     */
    public static function findUbigeo(string $ubigeoCode): ?array
    {
        if (strlen($ubigeoCode) !== 6) {
            return null;
        }

        $depCode = substr($ubigeoCode, 0, 2);
        $provCode = substr($ubigeoCode, 0, 4);
        $data = self::getData();

        if (
            isset($data[$depCode]) &&
            isset($data[$depCode]['provincias'][$provCode]) &&
            isset($data[$depCode]['provincias'][$provCode]['distritos'][$ubigeoCode])
        ) {
            return [
                'ubigeo' => $ubigeoCode,
                'departamento_codigo' => $depCode,
                'departamento' => $data[$depCode]['nombre'],
                'provincia_codigo' => $provCode,
                'provincia' => $data[$depCode]['provincias'][$provCode]['nombre'],
                'distrito' => $data[$depCode]['provincias'][$provCode]['distritos'][$ubigeoCode],
            ];
        }

        return null;
    }

    /**
     * Intentar encontrar el código de ubigeo mediante coincidencia por nombre de distrito y departamento
     */
    public static function matchDistrict(string $districtName, string $departmentName = 'LIMA'): ?array
    {
        $data = self::getData();
        $targetDist = strtoupper(trim($districtName));
        $targetDep = strtoupper(trim($departmentName));

        // Primer intento: buscando con el departamento
        foreach ($data as $depCode => $dep) {
            $depName = strtoupper($dep['nombre']);
            $depMatch = empty($targetDep) 
                || $depName === $targetDep 
                || str_contains($targetDep, $depName) 
                || str_contains($depName, $targetDep);

            if (!$depMatch) {
                continue;
            }

            foreach ($dep['provincias'] as $provCode => $prov) {
                foreach ($prov['distritos'] as $distCode => $dist) {
                    $normDist = strtoupper(trim($dist));
                    if (
                        $normDist === $targetDist ||
                        preg_match('/\b' . preg_quote($normDist, '/') . '\b/u', $targetDist) ||
                        str_contains($targetDist, $normDist) ||
                        str_contains($normDist, $targetDist)
                    ) {
                        return [
                            'ubigeo' => (string)$distCode,
                            'departamento_codigo' => (string)$depCode,
                            'departamento' => $dep['nombre'],
                            'provincia_codigo' => (string)$provCode,
                            'provincia' => $prov['nombre'],
                            'distrito' => $dist,
                        ];
                    }
                }
            }
        }

        // Segundo intento: si no coincidió con el departamento (por ej. Google devuelve nombres raros), buscar en todos los distritos
        foreach ($data as $depCode => $dep) {
            foreach ($dep['provincias'] as $provCode => $prov) {
                foreach ($prov['distritos'] as $distCode => $dist) {
                    $normDist = strtoupper(trim($dist));
                    if (
                        $normDist === $targetDist ||
                        preg_match('/\b' . preg_quote($normDist, '/') . '\b/u', $targetDist) ||
                        str_contains($targetDist, $normDist)
                    ) {
                        return [
                            'ubigeo' => (string)$distCode,
                            'departamento_codigo' => (string)$depCode,
                            'departamento' => $dep['nombre'],
                            'provincia_codigo' => (string)$provCode,
                            'provincia' => $prov['nombre'],
                            'distrito' => $dist,
                        ];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Cálculo de distancia geodésica mediante fórmula de Haversine (en metros)
     * 
     * @param float $lat1 Latitud punto A
     * @param float $lon1 Longitud punto A
     * @param float $lat2 Latitud punto B
     * @param float $lon2 Longitud punto B
     * @return float Distancia en metros
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Radio de la Tierra en metros

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }

    /**
     * Valida si un punto GPS se encuentra dentro de la geocerca permitida (por defecto 200m)
     */
    public static function isWithinGeofence(float $targetLat, float $targetLng, float $currentLat, float $currentLng, float $toleranceMeters = 200.0): array
    {
        $distance = self::calculateDistance($targetLat, $targetLng, $currentLat, $currentLng);
        return [
            'distancia_metros' => $distance,
            'dentro_de_rango' => $distance <= $toleranceMeters,
            'tolerancia_metros' => $toleranceMeters,
        ];
    }
}
