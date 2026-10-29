<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Código secuencial legible para cliente y soporte: INC-000123
            if (!Schema::hasColumn('incidencias', 'codigo')) {
                $table->string('codigo', 20)->nullable()->unique()->after('id');
            }

            // Submotivo detallado según el flujo de 2 pasos
            if (!Schema::hasColumn('incidencias', 'submotivo')) {
                $table->string('submotivo', 100)->nullable()->after('tipo');
            }

            // Evidencia fotográfica del cliente (almacenamiento privado)
            if (!Schema::hasColumn('incidencias', 'foto_path')) {
                $table->string('foto_path')->nullable()->after('descripcion');
            }

            // Teléfono verificado por WhatsApp al momento de radicar el reclamo
            if (!Schema::hasColumn('incidencias', 'telefono_verificado')) {
                $table->string('telefono_verificado', 25)->nullable()->after('foto_path');
            }

            // Historial de cambios de estado con autor y timestamp
            if (!Schema::hasColumn('incidencias', 'historial_estados')) {
                $table->json('historial_estados')->nullable()->after('status');
            }

            // Nota de resolución visible para el cliente en "Mis pedidos"
            if (!Schema::hasColumn('incidencias', 'nota_resolucion_cliente')) {
                $table->text('nota_resolucion_cliente')->nullable()->after('resolucion');
            }
        });

        // Modificar columna 'status' para admitir los nuevos estados del brief sin perder datos
        // Estados: abierta, en_revision, reenvio_camino, reembolsado, resuelta, rechazada, cerrada
        try {
            DB::statement("ALTER TABLE incidencias MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'abierta'");
            DB::statement("ALTER TABLE incidencias MODIFY COLUMN tipo VARCHAR(50) NOT NULL");
        } catch (\Throwable $e) {
            // Ignorar en SQLite / pruebas si no soporta ALTER COLUMN
        }

        // Garantizar unicidad de incidencia por pedido (1 incidencia por pedido)
        try {
            Schema::table('incidencias', function (Blueprint $table) {
                $table->unique('pedido_id', 'incidencias_pedido_id_unique');
            });
        } catch (\Throwable $e) {
            // Ya existe o no aplica
        }
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            try {
                $table->dropUnique('incidencias_pedido_id_unique');
            } catch (\Throwable $e) {}

            $dropColumns = [];
            foreach (['codigo', 'submotivo', 'foto_path', 'telefono_verificado', 'historial_estados', 'nota_resolucion_cliente'] as $col) {
                if (Schema::hasColumn('incidencias', $col)) {
                    $dropColumns[] = $col;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        try {
            DB::statement("ALTER TABLE incidencias MODIFY COLUMN status ENUM('abierta', 'en_revision', 'resuelta', 'cerrada') DEFAULT 'abierta'");
            DB::statement("ALTER TABLE incidencias MODIFY COLUMN tipo ENUM('no_llego', 'producto_incorrecto', 'producto_defectuoso', 'cobro_incorrecto', 'otro')");
        } catch (\Throwable $e) {}
    }
};
