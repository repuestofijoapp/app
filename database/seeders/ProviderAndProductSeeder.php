<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProviderAndProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create/Update EMASA (Autorex)
        $emasa = Provider::updateOrCreate(
            ['business_name' => 'Autorex Peruana S.A.'],
            [
                'ruc' => '20100078945',
                'specialty' => 'Bosch, Tokico, Mitsuboshi',
                'whatsapp_number' => '+51999888777',
                'phone' => '01 7061100',
                'contact_email' => 'ventas@autorex.com.pe',
                'address' => 'Av. Republica de Panama 4045',
                'city' => 'Lima',
                'country' => 'Perú',
                'is_active' => true,
            ]
        );

        // 2. Create/Update current provider (Importaciones Vital)
        $vital = Provider::updateOrCreate(
            ['business_name' => 'Importaciones Vital S.A.C.'],
            [
                'ruc' => '20554433221',
                'specialty' => 'Repuestos Importados Propios',
                'whatsapp_number' => '+51944556677',
                'phone' => '01 4443322',
                'contact_email' => 'gerencia@vital.com.pe',
                'address' => 'Av. Iquitos 456',
                'city' => 'Lima',
                'country' => 'Perú',
                'is_active' => true,
            ]
        );

        // 3. Categories (Ensure they exist)
        $catBaterias = Category::firstOrCreate(['name' => 'Baterías']);
        $catSuspension = Category::firstOrCreate(['name' => 'Suspensión']);
        $catFajas = Category::firstOrCreate(['name' => 'Fajas y Correas']);
        $catFiltros = Category::firstOrCreate(['name' => 'Filtros']);

        // 4. Products for EMASA
        $productsEmasa = [
            [
                'name' => 'Batería Bosch S4 12V 13 Placas',
                'brand' => 'Bosch',
                'supplier_code' => 'BOS-S4-13',
                'oem_code' => 'BAT-001',
                'price' => 450.00,
                'category_id' => $catBaterias->id,
            ],
            [
                'name' => 'Amortiguador Tokico Trasero Toyota Yaris',
                'brand' => 'Tokico',
                'supplier_code' => 'TOK-TY-01',
                'oem_code' => '48530-0D560',
                'price' => 380.00,
                'category_id' => $catSuspension->id,
            ],
            [
                'name' => 'Faja de Alternador Bosch Multi V',
                'brand' => 'Bosch',
                'supplier_code' => 'BOS-6PK-123',
                'oem_code' => '6PK1230',
                'price' => 85.00,
                'category_id' => $catFajas->id,
            ],
        ];

        foreach ($productsEmasa as $p) {
            Product::updateOrCreate(
                ['supplier_code' => $p['supplier_code'], 'provider_id' => $emasa->id],
                $p
            );
        }

        // 5. Products for Vital (Imported ones)
        $productsVital = [
            [
                'name' => 'Faja de Distribución Mitsuboshi Honda Civic',
                'brand' => 'Mitsuboshi',
                'supplier_code' => 'MIT-HC-01',
                'oem_code' => '14400-P2E-004',
                'price' => 120.00,
                'category_id' => $catFajas->id,
            ],
            [
                'name' => 'Filtro de Aceite Beste Premium',
                'brand' => 'Beste',
                'supplier_code' => 'BES-FIL-01',
                'oem_code' => '15400-PLM-A01',
                'price' => 45.00,
                'category_id' => $catFiltros->id,
            ],
        ];

        foreach ($productsVital as $p) {
            Product::updateOrCreate(
                ['supplier_code' => $p['supplier_code'], 'provider_id' => $vital->id],
                $p
            );
        }
    }
}
