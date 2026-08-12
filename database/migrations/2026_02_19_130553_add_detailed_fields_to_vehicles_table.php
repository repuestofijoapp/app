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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('manufacturing_year')->nullable()->after('year');
            $table->string('body_type')->nullable()->after('manufacturing_year');
            $table->string('color')->nullable()->after('body_type');
            $table->string('version_no')->nullable()->after('color');
            $table->string('serie_no')->nullable()->after('version_no');
            // 'vin' already exists in the original table
            $table->string('engine_no')->nullable()->after('engine_code');
            $table->string('engine_power')->nullable()->after('engine_no');
            $table->string('fuel_type')->nullable()->after('engine_power');
            $table->integer('cylinders')->nullable()->after('fuel_type');
            $table->string('displacement')->nullable()->after('cylinders');
            $table->string('weight_dry')->nullable()->after('displacement');
            $table->string('weight_net')->nullable()->after('weight_dry');
            $table->string('payload')->nullable()->after('weight_net');
            $table->string('weight_gross')->nullable()->after('payload');
            $table->integer('seats')->nullable()->after('weight_gross');
            $table->string('length')->nullable()->after('seats');
            $table->string('width')->nullable()->after('length');
            $table->string('height')->nullable()->after('width');
            $table->string('wheel_formula')->nullable()->after('height');
            $table->integer('passengers')->nullable()->after('wheel_formula');
            $table->integer('doors')->nullable()->after('passengers');
            $table->integer('wheels')->nullable()->after('doors');
            $table->integer('axles')->nullable()->after('wheels');
            $table->string('usage_type')->nullable()->after('axles');
            $table->string('category_code')->nullable()->after('usage_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'manufacturing_year', 'body_type', 'color', 'version_no',
                'serie_no', 'engine_no', 'engine_power', 'fuel_type',
                'cylinders', 'displacement', 'weight_dry', 'weight_net',
                'payload', 'weight_gross', 'seats', 'length', 'width',
                'height', 'wheel_formula', 'passengers', 'doors',
                'wheels', 'axles', 'usage_type', 'category_code'
            ]);
        });
    }
};
