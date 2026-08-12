<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique(); // Placa del vehículo (única)
            $table->string('vin')->nullable(); // Número de chasis/VIN
            $table->string('engine_code')->nullable(); // Código del motor
            $table->string('brand')->nullable(); // Marca
            $table->string('model')->nullable(); // Modelo
            $table->year('year')->nullable(); // Año
            $table->timestamps();
            
            $table->index('plate'); // Índice para búsquedas rápidas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
