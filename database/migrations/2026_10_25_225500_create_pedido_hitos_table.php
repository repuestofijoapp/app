<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedido_hitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->enum('tipo_hito', [
                'recojo_proveedor',
                'embalaje_hub',
                'entrega_motorizado',
                'entrega_cliente'
            ]);
            $table->string('foto_url');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->decimal('distancia_metros', 12, 2)->nullable();
            $table->boolean('dentro_de_rango')->default(true);
            $table->string('operador_nombre');
            $table->foreignId('operador_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['pedido_id', 'tipo_hito']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_hitos');
    }
};
