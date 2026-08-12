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
        Schema::table('providers', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('user_id');
            $table->string('contact_email')->nullable()->after('business_name');
            $table->string('phone')->nullable()->after('whatsapp_number');
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->default('Perú')->after('city');
            $table->string('ruc')->nullable()->after('country');
            $table->string('bank_account_number')->nullable()->after('ruc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'contact_email',
                'phone',
                'address',
                'city',
                'country',
                'ruc',
                'bank_account_number'
            ]);
        });
    }
};
