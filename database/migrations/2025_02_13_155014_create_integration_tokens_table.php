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
        Schema::create('integration_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('friendly_name');
            $table->string('type');
            $table->string('token');
            $table->string('secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_tokens');
    }
};
