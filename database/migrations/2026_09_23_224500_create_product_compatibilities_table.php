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
        if (!Schema::hasTable('product_compatibilities')) {
            Schema::create('product_compatibilities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('make_id')->nullable();
                $table->unsignedBigInteger('car_model_id');
                $table->unsignedBigInteger('engine_id')->nullable();
                $table->string('source', 50)->default('catalog'); // 'catalog', 'manual', 'derived'
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('make_id')->references('id')->on('makes')->onDelete('set null');
                $table->foreign('car_model_id')->references('id')->on('car_models')->onDelete('cascade');
                $table->foreign('engine_id')->references('id')->on('engines')->onDelete('cascade');

                // Performance Indexes
                $table->index(['car_model_id', 'engine_id'], 'idx_compat_model_engine');
                $table->index('product_id', 'idx_compat_product');
                $table->index('engine_id', 'idx_compat_engine');
                $table->index('make_id', 'idx_compat_make');

                // Unique compound key (avoid duplicates)
                // In MySQL, unique with nullable allows multiple nulls, but for model+engine it protects duplicates
                $table->unique(['product_id', 'car_model_id', 'engine_id'], 'unique_prod_model_engine');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_compatibilities');
    }
};
