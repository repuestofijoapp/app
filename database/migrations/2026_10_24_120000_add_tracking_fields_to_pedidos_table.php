<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('carrier')->nullable()->after('status');
            $table->string('tracking_number')->nullable()->after('carrier');
            $table->text('tracking_url')->nullable()->after('tracking_number');
            $table->dateTime('estimated_delivery')->nullable()->after('tracking_url');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['carrier', 'tracking_number', 'tracking_url', 'estimated_delivery']);
        });
    }
};
