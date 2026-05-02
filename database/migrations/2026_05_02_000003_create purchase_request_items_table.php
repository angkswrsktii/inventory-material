<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained()->nullOnDelete(); // bisa null jika material baru
            $table->string('material_name'); // nama material (denormalized, untuk material baru)
            $table->string('material_code')->nullable();
            $table->string('unit'); // satuan
            $table->string('specification')->nullable();
            $table->decimal('quantity_requested', 15, 2);
            $table->decimal('quantity_approved', 15, 2)->nullable();
            $table->decimal('estimated_price', 15, 2)->nullable(); // harga estimasi per unit
            $table->text('item_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};
