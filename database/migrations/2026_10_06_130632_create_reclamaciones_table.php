<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();          // Código de seguimiento RF-XXXXXXXX
            $table->string('nombre', 200);
            $table->string('email', 200);
            $table->string('telefono', 20);
            $table->string('tipo_doc', 30)->nullable();
            $table->string('num_doc', 30)->nullable();
            $table->string('num_pedido', 50)->nullable();
            $table->date('fecha_pedido')->nullable();
            $table->string('tipo_reclamacion', 100);       // reclamacion / queja
            $table->text('descripcion');
            $table->string('solucion_esperada', 100);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->enum('estado', ['pendiente', 'en_revision', 'respondida', 'cerrada'])->default('pendiente');
            $table->text('respuesta')->nullable();          // Respuesta de RepuestoFijo
            $table->timestamp('respondida_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamaciones');
    }
};

