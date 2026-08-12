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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuario asociado
            $table->string('whatsapp_number'); // Número de WhatsApp para Green API
            $table->string('specialty')->nullable(); // Especialidad del proveedor
            $table->integer('leads_count')->default(0); // Contador de leads (para freemium: 10 gratis/mes)
            $table->date('leads_reset_at')->nullable(); // Fecha de reset del contador mensual
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
