<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();

            // Vínculos
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');

            // Tipo de incidencia
            $table->enum('tipo', [
                'no_llego',           // El pedido no llegó
                'producto_incorrecto',// Llegó un producto distinto al pedido
                'producto_defectuoso',// El producto llegó dañado o no funciona
                'cobro_incorrecto',   // El monto cobrado no coincide
                'otro',               // Cualquier otro problema
            ]);

            // Descripción del cliente
            $table->text('descripcion')->nullable();

            // Gestión interna
            $table->enum('status', [
                'abierta',     // Recién reportada, sin atender
                'en_revision', // Admin/manager la está revisando
                'resuelta',    // Se resolvió
                'cerrada',     // Cerrada sin resolución (spam, duplicado, etc.)
            ])->default('abierta');

            $table->text('resolucion')->nullable(); // Nota interna de cómo se resolvió
            $table->foreignId('atendida_por')->nullable()->constrained('users')->onDelete('set null'); // Admin que la gestionó
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('pedido_id');
            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
