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
        Schema::create('purchase_order_recap_discount_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_recap_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->timestamps();

            // FK dengan nama singkat
            $table->foreign('purchase_order_recap_id', 'fk_porc_discount_recap')
                ->references('id')
                ->on('purchase_order_recaps')
                ->onDelete('cascade');

            $table->foreign('product_id', 'fk_porc_discount_product')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_recap_discount_items');
    }
};
