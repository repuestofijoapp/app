<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'plate' => 'ABC123',
                'vin' => 'VIN1234567890',
                'engine_code' => '1ZR-FE',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2018
            ],
            [
                'plate' => 'XYZ987',
                'vin' => 'VIN0987654321',
                'engine_code' => 'HR16DE',
                'brand' => 'Nissan',
                'model' => 'Sentra',
                'year' => 2015
            ],
            [
                'plate' => 'LMN456',
                'vin' => 'VIN1122334455',
                'engine_code' => 'G4LC',
                'brand' => 'Kia',
                'model' => 'Rio',
                'year' => 2020
            ],
            [
                'plate' => 'HDA001',
                'vin' => 'VIN2233445566',
                'engine_code' => 'R18Z1',
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2019
            ],
            [
                'plate' => 'HYU002',
                'vin' => 'VIN3344556677',
                'engine_code' => 'Gamma 1.6',
                'brand' => 'Hyundai',
                'model' => 'Elantra',
                'year' => 2017
            ],
            [
                'plate' => 'MZD003',
                'vin' => 'VIN4455667788',
                'engine_code' => 'PE-VPS',
                'brand' => 'Mazda',
                'model' => '3',
                'year' => 2021
            ],
            [
                'plate' => 'VWA004',
                'vin' => 'VIN5566778899',
                'engine_code' => 'EA888',
                'brand' => 'Volkswagen',
                'model' => 'Jetta',
                'year' => 2016
            ],
            [
                'plate' => 'CHV005',
                'vin' => 'VIN6677889900',
                'engine_code' => 'LUV',
                'brand' => 'Chevrolet',
                'model' => 'Cruze',
                'year' => 2014
            ],
            [
                'plate' => 'FRD006',
                'vin' => 'VIN7788990011',
                'engine_code' => 'Duratec 2.0',
                'brand' => 'Ford',
                'model' => 'Focus',
                'year' => 2013
            ],
            [
                'plate' => 'SUB007',
                'vin' => 'VIN8899001122',
                'engine_code' => 'FB20',
                'brand' => 'Subaru',
                'model' => 'Impreza',
                'year' => 2022
            ],
        ];

        foreach ($vehicles as $data) {
            Vehicle::firstOrCreate(['plate' => $data['plate']], $data);
        }
    }
}
