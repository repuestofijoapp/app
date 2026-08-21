<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'fuel_types')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('fuel_types')->nullable()->after('fuel_type');
            });
        }

        // Migrate existing fuel_type data → fuel_types array
        DB::statement("
            UPDATE products
            SET fuel_types = JSON_ARRAY(fuel_type)
            WHERE fuel_type IS NOT NULL AND fuel_type != ''
        ");
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('fuel_types');
        });
    }
};
