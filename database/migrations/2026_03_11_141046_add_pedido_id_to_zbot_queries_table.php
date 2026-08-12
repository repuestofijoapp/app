<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('zbot_queries', function (Blueprint $table) {
            // Vínculo directo al pedido que originó la consulta
            $table->unsignedBigInteger('pedido_id')->nullable()->after('provider_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('set null');

            // Reminders enviados (si no existía ya)
            if (!Schema::hasColumn('zbot_queries', 'reminders_sent')) {
                $table->unsignedTinyInteger('reminders_sent')->default(0)->after('expires_at');
            }

            // current_step (si no existía)
            if (!Schema::hasColumn('zbot_queries', 'current_step')) {
                $table->string('current_step')->default('initial')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('zbot_queries', function (Blueprint $table) {
            $table->dropForeign(['pedido_id']);
            $table->dropColumn('pedido_id');
        });
    }
};
