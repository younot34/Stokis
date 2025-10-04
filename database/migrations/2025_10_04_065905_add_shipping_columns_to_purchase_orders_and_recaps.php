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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('jasa_pengiriman')->nullable()->after('status');
            $table->string('resi_number')->nullable()->after('jasa_pengiriman');
            $table->string('image')->nullable()->after('resi_number');
        });

        Schema::table('purchase_order_recaps', function (Blueprint $table) {
            $table->string('jasa_pengiriman')->nullable()->after('status');
            $table->string('resi_number')->nullable()->after('jasa_pengiriman');
            $table->string('image')->nullable()->after('resi_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['jasa_pengiriman', 'resi_number', 'image']);
        });

        Schema::table('purchase_order_recaps', function (Blueprint $table) {
            $table->dropColumn(['jasa_pengiriman', 'resi_number', 'image']);
        });
    }
};
