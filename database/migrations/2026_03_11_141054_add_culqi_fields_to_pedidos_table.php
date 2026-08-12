<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Culqi charge ID (se llena al recibir el webhook de Culqi)
            $table->string('culqi_charge_id')->nullable()->after('metodo_pago');

            // Timestamp exacto en que Culqi confirma el pago
            $table->timestamp('payment_confirmed_at')->nullable()->after('culqi_charge_id');

            // Razón de cancelación (para auditoría)
            $table->string('cancellation_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['culqi_charge_id', 'payment_confirmed_at', 'cancellation_reason']);
        });
    }
};
