<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Motor y Componentes Internos',
                'icon' => 'fas fa-engine', // FontAwesome class
                'image_url' => 'images/categorias/Motor.png',
                'subcategories' => ['Pistones', 'Anillos', 'Válvulas', 'Empaquetaduras', 'Metales de Motor (Biela, Bancada, Levas)', 'Cigüeñal', 'Kit de Distribución']
            ],
            [
                'name' => 'Suspensión y Dirección',
                'icon' => 'fas fa-car-bump',
                'image_url' => 'images/categorias/Suspension.png',
                'subcategories' => ['Amortiguadores', 'Resortes', 'Terminales de dirección', 'Trapecios', 'Bocinas', 'Rótulas']
            ],
            [
                'name' => 'Sistema de Frenos',
                'icon' => 'fas fa-compact-disc', // Disc icon resembling brake disc
                'image_url' => 'images/categorias/Frenos.png',
                'subcategories' => ['Pastillas de freno', 'Discos', 'Tambores', 'Zapatas', 'Bombas de freno', 'Sensores ABS']
            ],
            [
                'name' => 'Sistema Eléctrico e Iluminación',
                'icon' => 'fas fa-bolt',
                'image_url' => 'images/categorias/Sistema_electrico.png',
                'subcategories' => ['Alternadores', 'Arrancadores', 'Bujías', 'Bobinas de encendido', 'Faros delanteros', 'Faros posteriores', 'Sensores']
            ],
            [
                'name' => 'Transmisión y Embrague',
                'icon' => 'fas fa-cogs',
                'image_url' => 'images/categorias/Embrague.png',
                'subcategories' => ['Kit de Embrague', 'Palieres', 'Crucetas', 'Filtros de transmisión']
            ],
            [
                'name' => 'Sistema de Refrigeración',
                'icon' => 'fas fa-snowflake',
                'image_url' => 'images/categorias/Sistema_refrigeracion_motor.png',
                'subcategories' => ['Radiadores', 'Bombas de agua', 'Termostatos', 'Ventiladores', 'Mangueras']
            ],
            [
                'name' => 'Carrocería y Accesorios',
                'icon' => 'fas fa-car-side',
                'image_url' => 'images/categorias/Carroceria.png',
                'subcategories' => ['Parachoques', 'Capó', 'Guardafangos', 'Espejos', 'Manijas']
            ],
            [
                'name' => 'Filtros y Mantenimiento',
                'icon' => 'fas fa-filter',
                'image_url' => 'images/categorias/Filtros.png',
                'subcategories' => ['Filtro de Aceite', 'Filtro de Aire', 'Filtro de Cabina', 'Filtro de Combustible']
            ],
        ];

        foreach ($categories as $catData) {
            $parent = Category::create([
                'name' => $catData['name'],
                'slug' => \Str::slug($catData['name']),
                'icon' => $catData['icon'],
                'image_url' => $catData['image_url'] ?? null,
                'parent_id' => null
            ]);

            foreach ($catData['subcategories'] as $subName) {
                Category::create([
                    'name' => $subName,
                    'slug' => \Str::slug($subName),
                    'parent_id' => $parent->id
                ]);
            }
        }
    }
}