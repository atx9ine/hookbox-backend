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
        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_request_id')->constrained('webhook_requests')->cascadeOnDelete();
            $table->string('destination_url');
            $table->enum('method', ['POST', 'GET', 'DELETE', 'PUT', 'PATCH'])->nullable()->default('POST');
            $table->string('request_headers');
            $table->jsonb('request_body');
            $table->enum('status', ['sending', 'sent', 'failed'])->default('sending');
            $table->integer('attempts')->default(1);
            $table->date('last_attempted_at');
            $table->date('delivered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
    }
};
