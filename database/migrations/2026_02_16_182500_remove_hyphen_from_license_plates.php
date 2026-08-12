<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove hyphen from existing plates
        DB::table('vehicles')->get()->each(function ($vehicle) {
            $newPlate = str_replace('-', '', $vehicle->plate);
            if ($newPlate !== $vehicle->plate) {
                DB::table('vehicles')
                    ->where('id', $vehicle->id)
                    ->update(['plate' => $newPlate]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // It's hard to accurately reverse this without knowing which plates originally had hyphens,
        // but typically they are in the format ABC-123.
        DB::table('vehicles')->get()->each(function ($vehicle) {
            if (strlen($vehicle->plate) === 6) {
                $newPlate = substr($vehicle->plate, 0, 3) . '-' . substr($vehicle->plate, 3);
                DB::table('vehicles')
                    ->where('id', $vehicle->id)
                    ->update(['plate' => $newPlate]);
            }
        });
    }
};
