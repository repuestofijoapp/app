<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_oversizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('oversize', 10)->default('STD');
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            // Unique index to prevent duplicate oversizes for the same product
            $table->unique(['product_id', 'oversize']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_oversizes');
    }
};
