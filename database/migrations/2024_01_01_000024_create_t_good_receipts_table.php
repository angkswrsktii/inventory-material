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
            $table->foreignId('t_purchase_order_id')->nullable()->constrained('t_purchase_orders')->nullOnDelete();
            $table->foreignId('m_warehouse_id')->constrained('m_warehouses');
            $table->date('receipt_date');
            $table->string('delivery_note_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->constrained('m_users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('t_good_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_good_receipt_id')->constrained('t_good_receipts')->cascadeOnDelete();
            $table->foreignId('t_purchase_order_item_id')->nullable()->constrained('t_purchase_order_items')->nullOnDelete();
            $table->foreignId('m_material_id')->constrained('m_materials');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_good_receipt_items');
        Schema::dropIfExists('t_good_receipts');
    }
};
