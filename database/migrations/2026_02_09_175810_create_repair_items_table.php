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
        Schema::create('repair_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade'); // Orden de reparación
            $table->foreignId('oem_product_id')->constrained('oem_products')->onDelete('cascade'); // Producto OEM solicitado
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('set null'); // Proveedor que confirmó (nullable)
            $table->decimal('price', 10, 2)->nullable(); // Precio confirmado por el proveedor
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'timeout'])->default('pending');
            $table->string('green_api_message_id')->nullable(); // ID del mensaje en Green API
            $table->integer('retry_count')->default(0); // Contador de reintentos (máximo 3)
            $table->timestamp('last_retry_at')->nullable(); // Última vez que se reintentó
            $table->timestamp('confirmed_at')->nullable(); // Fecha de confirmación
            $table->timestamps();
            
            $table->index('repair_order_id');
            $table->index('oem_product_id');
            $table->index('provider_id');
            $table->index('status');
            $table->index('green_api_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_items');
    }
};
