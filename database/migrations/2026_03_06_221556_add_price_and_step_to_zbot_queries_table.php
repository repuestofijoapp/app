<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('zbot_queries', function (Blueprint $table) {
            $table->string('price')->nullable()->after('items_json');
            $table->enum('current_step', ['initial', 'asking_price', 'completed'])->default('initial')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('zbot_queries', function (Blueprint $table) {
            $table->dropColumn(['price', 'current_step']);
        });
    }
};
