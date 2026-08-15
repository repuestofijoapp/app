<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');           // ruta en storage
            $table->string('title')->nullable();     // texto principal opcional
            $table->string('subtitle')->nullable();  // texto secundario opcional
            $table->string('button_text')->nullable(); // texto del botón opcional
            $table->string('button_url')->nullable();  // URL del botón opcional
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_slides');
    }
};
