<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('direccion');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->string('ubigeo_code', 6)->nullable()->after('distrito');
            $table->string('departamento', 60)->nullable()->after('distrito');
            $table->string('provincia', 60)->nullable()->after('departamento');
            $table->string('delivery_token', 64)->unique()->nullable()->after('clave_secreta');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'ubigeo_code', 'departamento', 'provincia', 'delivery_token']);
        });
    }
};
