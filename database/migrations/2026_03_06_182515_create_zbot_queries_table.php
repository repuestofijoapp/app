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
        Schema::create('zbot_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->string('chat_id'); // 51999888777@c.us
            $table->string('message_id')->nullable(); // From Green API
            $table->enum('status', ['waiting', 'confirmed', 'denied', 'expired', 'reassigned'])->default('waiting');
            $table->json('items_json'); // List of products and quantities asked
            $table->text('response_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zbot_queries');
    }
};
