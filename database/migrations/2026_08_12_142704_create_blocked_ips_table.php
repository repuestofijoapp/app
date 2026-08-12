<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add blocked_by (admin user_id) to blacklist_ips table.
     */
    public function up(): void
    {
        Schema::table('blacklist_ips', function (Blueprint $table) {
            $table->unsignedBigInteger('blocked_by')->nullable()->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('blacklist_ips', function (Blueprint $table) {
            $table->dropColumn('blocked_by');
        });
    }
};

