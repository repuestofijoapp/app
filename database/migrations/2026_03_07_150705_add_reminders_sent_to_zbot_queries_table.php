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
            $table->integer('reminders_sent')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zbot_queries', function (Blueprint $table) {
            $table->dropColumn('reminders_sent');
        });
    }
};
