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
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Mecánico que crea la orden
            $table->string('vehicle_plate'); // Placa del vehículo (FK a vehicles)
            $table->enum('status', ['draft', 'pending', 'confirmed', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_price', 10, 2)->nullable(); // Precio total de los repuestos
            $table->decimal('commission', 10, 2)->default(10.00); // Comisión fija S/ 10.00
            $table->decimal('delivery_cost', 10, 2)->nullable(); // Costo de delivery
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('vehicle_plate');
            $table->index('status');
            
            // Foreign key a vehicles usando plate
            $table->foreign('vehicle_plate')->references('plate')->on('vehicles')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_orders');
    }
};
