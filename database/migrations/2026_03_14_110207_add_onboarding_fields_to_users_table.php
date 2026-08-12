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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('onboarding_completed_at')->after('email_verified_at')->nullable();
            $table->string('receipt_type')->after('role')->nullable(); // boleta | factura
            // Laravel 11 handles ENUM updates natively
            $table->enum('role', ['admin', 'manager', 'mechanic', 'workshop', 'store', 'transporte', 'provider'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed_at', 'receipt_type']);
            // We don't necessarily want to revert the enum if it has data
        });
    }
};
