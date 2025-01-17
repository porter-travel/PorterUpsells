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
        Schema::table('calendar_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_booking_id')->nullable();
            $table->foreign('parent_booking_id')->references('id')->on('calendar_bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_bookings', function (Blueprint $table) {
            $table->dropForeign(['parent_booking_id']);
            $table->dropColumn('parent_booking_id');
        });
    }
};
