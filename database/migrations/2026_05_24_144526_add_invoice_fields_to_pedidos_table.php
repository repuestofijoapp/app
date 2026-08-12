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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('billing_type')->default('boleta')->after('cancellation_reason'); // boleta o factura
            $table->string('invoice_url')->nullable()->after('billing_type');
            $table->string('invoice_xml')->nullable()->after('invoice_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['billing_type', 'invoice_url', 'invoice_xml']);
        });
    }
};
