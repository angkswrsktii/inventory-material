<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_purchase_request_id')->constrained('t_purchase_requests')->cascadeOnDelete();
            $table->foreignId('m_material_id')->nullable()->constrained('m_materials')->nullOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_purchase_request_items');
    }
};
