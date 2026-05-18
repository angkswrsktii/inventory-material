<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_mutasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_warehouse_id');
            $table->unsignedBigInteger('m_material_id')->nullable();
            $table->unsignedBigInteger('m_part_id')->nullable();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['reference_type', 'reference_id']);
        });

        Schema::table('t_mutasis', function (Blueprint $table) {
            $table->foreign('m_warehouse_id')->references('id')->on('m_warehouses');
            $table->foreign('m_material_id')->references('id')->on('m_materials');
            $table->foreign('m_part_id')->references('id')->on('m_parts');
            $table->foreign('created_by')->references('id')->on('m_users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_mutasis');
    }
};
