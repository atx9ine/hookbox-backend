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
        Schema::create('webhook_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_endpoint_id')->constrained('webhook_endpoints')->cascadeOnDelete();
            $table->enum('method', ['POST', 'GET', 'DELETE', 'PUT', 'PATCH'])->nullable()->default('POST');
            $table->string('path');
            $table->jsonb('query');
            $table->jsonb('headers');
            $table->jsonb('body');
            $table->ipAddress('id_address');
            // $table->string('content-type');
            $table->date('received_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_requests');
    }
};
