<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id(); // Este será el número de pedido (ej: 1000000)
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transporte_id')->nullable()->constrained('users')->onDelete('set null');

            // Datos de Envío
            $table->enum('tipo_envio', ['Lima', 'Provincia'])->default('Lima');
            $table->string('distrito')->nullable();
            $table->text('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->string('telefono_contacto')->nullable();

            // Datos de Pago
            $table->string('metodo_pago')->nullable(); // Yape, Plin, Transferencia, etc.

            // Seguridad para Provincia
            $table->string('clave_secreta')->nullable(); // Clave aleatoria de 4 dígitos (se guardará encriptada)

            // Totales
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('costo_envio', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);

            // Estado
            $table->enum('status', [
                'pendiente',
                'por_confirmar',
                'pagado',
                'en_preparacion',
                'en_camino',
                'entregado',
                'cancelado'
            ])->default('pendiente');

            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('transporte_id');
            $table->index('status');
        });

        // Iniciar la numeración en 7 dígitos (1000000)
        DB::statement("ALTER TABLE pedidos AUTO_INCREMENT = 1000000;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
