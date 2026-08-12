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
        Schema::create('oem_products', function (Blueprint $table) {
            $table->id();
            $table->string('oem_code')->unique(); // Código OEM (único)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Categoría del producto
            $table->string('name'); // Nombre legible del producto (ej. "Filtro de Aceite")
            $table->text('description')->nullable(); // Descripción del producto
            $table->string('image_url')->nullable(); // URL de la imagen del producto
            $table->json('specs')->nullable(); // Especificaciones técnicas (JSON: medidas, material, etc.)
            $table->json('compatible_models')->nullable(); // Modelos compatibles (JSON)
            $table->json('common_brands')->nullable(); // Marcas comunes que usan este OEM (JSON)
            $table->timestamps();

            $table->index('oem_code');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oem_products');
    }
};
