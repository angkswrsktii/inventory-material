<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_supplier_id')->nullable()->constrained('m_suppliers')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('specification')->nullable();
            $table->string('unit');
            $table->decimal('panjang_material', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_materials');
    }
};
