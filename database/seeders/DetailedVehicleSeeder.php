<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailedVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Vehicle::updateOrCreate(
        ['plate' => 'ABC123'],
        [
            'vin' => '5FNYF4850BB601988',
            'engine_code' => 'J35Z',
            'engine_no' => 'J35Z43065954',
            'brand' => 'HONDA',
            'model' => 'PILOT',
            'year' => 2011,
            'manufacturing_year' => '2010',
            'body_type' => 'SUV',
            'color' => 'NEGRO CRYSTAL',
            'version_no' => 'EXL',
            'fuel_type' => 'GASOLINA',
            'cylinders' => 6,
            'displacement' => '3.471 cc',
        ]
        );

        \App\Models\Vehicle::updateOrCreate(
        ['plate' => 'TOY456'],
        [
            'vin' => 'JTDKB20U00000000',
            'engine_code' => '1ZR-FE',
            'brand' => 'TOYOTA',
            'model' => 'COROLLA',
            'year' => 2015,
            'manufacturing_year' => '2014',
            'body_type' => 'SEDAN',
            'color' => 'GRIS',
            'fuel_type' => 'GASOLINA',
            'cylinders' => 4,
            'displacement' => '1.6L',
        ]
        );

        \App\Models\Vehicle::updateOrCreate(
        ['plate' => 'NIS789'],
        [
            'vin' => '3N1AB7AP00000000',
            'engine_code' => 'MR20DE',
            'brand' => 'NISSAN',
            'model' => 'SENTRA',
            'year' => 2018,
            'manufacturing_year' => '2017',
            'body_type' => 'SEDAN',
            'color' => 'BLANCO',
            'fuel_type' => 'GASOLINA',
            'cylinders' => 4,
            'displacement' => '2.0L',
        ]
        );
    }
}
