<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Proveedor que ofrece este producto
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');

            // Categoría del producto (Anillos, Metales, Empaques, etc.)
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Marca fabricante del repuesto (NPR, NDC, Toyan, etc.)
            $table->string('brand')->nullable()->index();

            // Código del proveedor/fabricante del repuesto (ej: SWH-30433, CB-1134, TO-55568528)
            $table->string('supplier_code')->index();

            // Código OEM del fabricante original del vehículo (ej: 13011-PH3-000)
            $table->string('oem_code')->nullable()->index();

            // Códigos OEM adicionales / referencias cruzadas de otros fabricantes
            $table->json('additional_oem_codes')->nullable();

            // Sobremedida: STD, 025, 050, 075, etc.
            $table->string('oversize', 10)->nullable()->default('STD');

            // Nombre descriptivo del producto
            $table->string('name')->nullable();

            // Motores compatibles: ["H22", "P13", "P5M", "D17A"]
            $table->json('compatible_engines')->nullable();

            // Vehículos compatibles en texto: "PRELUDE, CIVIC, CRV"
            $table->string('compatible_vehicles')->nullable();

            // Especificaciones técnicas: {diametro: "87MM", ancho: "1.2x1.2x2.8"}
            $table->json('specs')->nullable();

            // Notas adicionales: "METAL", "100% GRAFITO", etc.
            $table->string('notes')->nullable();

            // Precio (en soles, nullable hasta que se ingrese)
            $table->decimal('price', 10, 2)->nullable();

            // Estado
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            // Índice compuesto para búsqueda por proveedor + código
            $table->index(['provider_id', 'supplier_code']);
            // Índice para búsqueda por vehículo compatible
            $table->index('compatible_vehicles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
