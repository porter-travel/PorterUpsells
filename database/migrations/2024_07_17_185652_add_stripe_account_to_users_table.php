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
            $table->string('stripe_account_number')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('stripe_account_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_account_number');
            $table->dropColumn('country_code');
            $table->dropColumn('currency');
            $table->dropColumn('stripe_account_active');
        });
    }
};
