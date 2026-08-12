<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OemProduct;
use App\Models\Category;

class OemProductSeeder extends Seeder
{
    public function run(): void
    {
        // Helper to get subcategory ID by name
        $getSubId = fn($name) => Category::where('name', $name)->value('id');

        $products = [
            // TOYOTA COROLLA
            [
                'oem_code' => '90915-YZZF1',
                'name' => 'Filtro de Aceite Toyota',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite original Toyota.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+Toyota',
                'specs' => ['Altura' => '85mm'],
                'compatible_models' => ['Corolla', 'Yaris'],
                'common_brands' => ['Toyota', 'Lexus']
            ],
            [
                'oem_code' => '04465-02220',
                'name' => 'Pastillas de Freno Delanteras Toyota',
                'category_id' => $getSubId('Pastillas de freno'),
                'description' => 'Juego de pastillas de freno delanteras.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Pastillas+Toyota',
                'specs' => ['Posición' => 'Delantera'],
                'compatible_models' => ['Corolla'],
                'common_brands' => ['Toyota']
            ],
            // NISSAN SENTRA
            [
                'oem_code' => '15208-65F0A',
                'name' => 'Filtro de Aceite Nissan',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite genuino Nissan.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+Nissan',
                'specs' => ['Válvula' => 'Sí'],
                'compatible_models' => ['Sentra', 'Versa'],
                'common_brands' => ['Nissan']
            ],
            [
                'oem_code' => 'D1060-3TA0A',
                'name' => 'Pastillas de Freno Nissan',
                'category_id' => $getSubId('Pastillas de freno'),
                'description' => 'Pastillas de freno de cerámica.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Pastillas+Nissan',
                'specs' => ['Material' => 'Cerámica'],
                'compatible_models' => ['Sentra'],
                'common_brands' => ['Nissan']
            ],
            // KIA RIO
            [
                'oem_code' => '26300-35505',
                'name' => 'Filtro de Aceite Kia/Hyundai',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite compatible con varios modelos.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+Kia',
                'specs' => ['Rosca' => 'M20x1.5'],
                'compatible_models' => ['Rio', 'Accent'],
                'common_brands' => ['Kia', 'Hyundai']
            ],
            // HONDA CIVIC
            [
                'oem_code' => '15400-RTA-003',
                'name' => 'Filtro de Aceite Honda',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite original Honda.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+Honda',
                'specs' => ['Filtración' => 'Alta eficiencia'],
                'compatible_models' => ['Civic', 'CR-V'],
                'common_brands' => ['Honda']
            ],
            [
                'oem_code' => '45022-T2G-A01',
                'name' => 'Pastillas de Freno Honda',
                'category_id' => $getSubId('Pastillas de freno'),
                'description' => 'Pastillas de freno originales.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Pastillas+Honda',
                'specs' => ['Incluye' => 'Shims'],
                'compatible_models' => ['Civic'],
                'common_brands' => ['Honda']
            ],
            // MAZDA 3
            [
                'oem_code' => 'PE01-14-302B',
                'name' => 'Filtro de Aceite Mazda Skyactiv',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite para motores Skyactiv.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+Mazda',
                'specs' => ['Tecnología' => 'Skyactiv'],
                'compatible_models' => ['Mazda 3', 'CX-5'],
                'common_brands' => ['Mazda']
            ],
            // VOLKSWAGEN JETTA
            [
                'oem_code' => '06J115403Q',
                'name' => 'Filtro de Aceite VW/Audi',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite para motores TSI/TFSI.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+VW',
                'specs' => ['Tipo' => 'Cartucho'],
                'compatible_models' => ['Jetta', 'Golf', 'A3'],
                'common_brands' => ['Volkswagen', 'Audi']
            ],
            // CHEVROLET CRUZE
            [
                'oem_code' => '55594651',
                'name' => 'Filtro de Aceite GM/ACDelco',
                'category_id' => $getSubId('Filtro de Aceite'),
                'description' => 'Filtro de aceite original GM.',
                'image_url' => 'https://via.placeholder.com/300x300.png?text=Filtro+GM',
                'specs' => ['Duración' => 'Larga vida'],
                'compatible_models' => ['Cruze', 'Sonic'],
                'common_brands' => ['Chevrolet', 'GM']
            ],
        ];

        foreach ($products as $data) {
            // Ensure category exists before creating
            if ($data['category_id']) {
                OemProduct::firstOrCreate(['oem_code' => $data['oem_code']], $data);
            }
        }
    }
}