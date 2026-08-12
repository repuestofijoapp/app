<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OemProduct;
use App\Models\Category;

class SampleProductSeeder extends Seeder
{
    public function run(): void
    {
        // 48: Filtro de Aceite
        // 16: Pastillas de freno
        // 25: Bujías

        $products = [
            [
                'oem_code' => '15400-PLM-A02',
                'category_id' => 48,
                'name' => 'Filtro de Aceite Honda Genuine',
                'description' => 'Filtro de aceite original para motores Honda serie J. Alta eficiencia.',
                'compatible_models' => json_encode(['PILOT', 'J35Z', 'CR-V']),
                'common_brands' => json_encode(['Honda']),
            ],
            [
                'oem_code' => '04465-02220',
                'category_id' => 16,
                'name' => 'Pastillas de Freno Toyota Ceramic',
                'description' => 'Pastillas de freno cerámicas para mayor durabilidad y menor ruido.',
                'compatible_models' => json_encode(['COROLLA', '1ZR-FE', 'Yaris']),
                'common_brands' => json_encode(['Toyota', 'Lexus']),
            ],
            [
                'oem_code' => '22401-ED815',
                'category_id' => 25,
                'name' => 'Bujía NGK Iridium Nissan',
                'description' => 'Bujía de iridio de larga duración para motores Nissan MR20.',
                'compatible_models' => json_encode(['SENTRA', 'MR20DE', 'Qashqai']),
                'common_brands' => json_encode(['Nissan']),
            ],
            [
                'oem_code' => 'PE-0001',
                'category_id' => 48,
                'name' => 'Filtro de Aceite Genérico COROLLA',
                'description' => 'Filtro de aceite económico para mantenimiento preventivo.',
                'compatible_models' => json_encode(['COROLLA', '1ZR-FE']),
                'common_brands' => json_encode(['Toyota']),
            ],
            [
                'oem_code' => 'PE-0002',
                'category_id' => 16,
                'name' => 'Pastillas de Freno PILOT Heavy Duty',
                'description' => 'Pastillas de freno para uso severo y remolque.',
                'compatible_models' => json_encode(['PILOT', 'J35Z']),
                'common_brands' => json_encode(['Honda']),
            ],
        ];

        foreach ($products as $product) {
            OemProduct::updateOrCreate(
            ['oem_code' => $product['oem_code']],
                $product
            );
        }
    }
}
