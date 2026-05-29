<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_material_batches', function (Blueprint $table) {
            $table->id();
            $table->string('load_material_number', 50);
            $table->unsignedBigInteger('m_material_id');
            $table->unsignedBigInteger('m_warehouse_id');
            $table->unsignedBigInteger('t_good_receipt_item_id')->nullable();
            $table->decimal('initial_quantity', 10, 2)->default(0);
            $table->decimal('remaining_quantity', 10, 2)->default(0);
            $table->date('receipt_date');
            $table->timestamps();

            // Satu batch = kombinasi unik load_material_number + material + warehouse
            $table->unique(['load_material_number', 'm_material_id', 'm_warehouse_id'], 'batch_unique');

            $table->foreign('m_material_id')->references('id')->on('m_materials');
            $table->foreign('m_warehouse_id')->references('id')->on('m_warehouses');
            $table->foreign('t_good_receipt_item_id')->references('id')->on('t_good_receipt_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_material_batches');
    }
};
