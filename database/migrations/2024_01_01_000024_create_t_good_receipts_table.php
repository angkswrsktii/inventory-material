<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_good_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('gr_number')->unique();
            $table->unsignedBigInteger('t_purchase_order_id')->nullable();
            $table->unsignedBigInteger('m_warehouse_id');
            $table->date('receipt_date');
            $table->string('delivery_note_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('received_by');
            $table->unsignedBigInteger('m_pic_id')->nullable();
            $table->integer('m_project_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->foreign('t_purchase_order_id')->references('id')->on('t_purchase_orders')->nullOnDelete();
            $table->foreign('m_warehouse_id')->references('id')->on('m_warehouses');
            $table->foreign('received_by')->references('id')->on('m_users');
            $table->foreign('m_pic_id')->references('id')->on('m_users')->nullOnDelete();
        });

        Schema::create('t_good_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_good_receipt_id');
            $table->unsignedBigInteger('t_purchase_order_item_id')->nullable();
            $table->unsignedBigInteger('m_material_id');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('t_good_receipt_items', function (Blueprint $table) {
            $table->foreign('t_good_receipt_id')->references('id')->on('t_good_receipts')->cascadeOnDelete();
            $table->foreign('t_purchase_order_item_id')->references('id')->on('t_purchase_order_items')->nullOnDelete();
            $table->foreign('m_material_id')->references('id')->on('m_materials');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_good_receipt_items');
        Schema::dropIfExists('t_good_receipts');
    }
};