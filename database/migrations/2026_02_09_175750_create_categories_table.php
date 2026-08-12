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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la categoría
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade'); // Categoría padre (jerarquía)
            $table->string('icon')->nullable(); // Ícono para la parrilla visual
            $table->string('slug')->unique(); // Slug para URLs amigables
            $table->integer('order')->default(0); // Orden de visualización
            $table->timestamps();
            
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
