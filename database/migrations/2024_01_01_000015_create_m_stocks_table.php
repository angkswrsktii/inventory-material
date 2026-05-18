<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_warehouse_id');
            $table->unsignedBigInteger('m_material_id')->nullable();
            $table->unsignedBigInteger('m_part_id')->nullable();
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('max_stock', 10, 2)->default(0);
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('m_stocks', function (Blueprint $table) {
            $table->foreign('m_warehouse_id')->references('id')->on('m_warehouses')->cascadeOnDelete();
            $table->foreign('m_material_id')->references('id')->on('m_materials')->cascadeOnDelete();
            $table->foreign('m_part_id')->references('id')->on('m_parts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_stocks');
    }
};
