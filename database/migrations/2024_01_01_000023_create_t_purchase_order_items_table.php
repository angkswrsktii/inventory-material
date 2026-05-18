<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_purchase_order_id');
            $table->unsignedBigInteger('m_material_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 15, 2)->nullable();
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->decimal('price_per_qty', 15, 0)->nullable();
        });

        Schema::table('t_purchase_order_items', function (Blueprint $table) {
            $table->foreign('t_purchase_order_id')->references('id')->on('t_purchase_orders')->cascadeOnDelete();
            $table->foreign('m_material_id')->references('id')->on('m_materials');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_purchase_order_items');
    }
};