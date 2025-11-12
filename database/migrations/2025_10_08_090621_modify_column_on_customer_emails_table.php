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
        Schema::table('customer_emails', function (Blueprint $table) {
            $table->dropForeign(['hotel_email_id']);
            $table->dropColumn('hotel_email_id');
            $table->foreignId('email_template_id')->nullable()->constrained('email_templates')->onDelete('set null')->after('booking_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_emails', function (Blueprint $table) {
            $table->dropForeign(['email_template_id']);
            $table->dropColumn('email_template_id');
            $table->foreignId('hotel_email_id')->nullable()->constrained('hotel_emails')->onDelete('set null')->after('booking_id');
        });
    }
};
