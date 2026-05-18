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
            $table->unsignedBigInteger('m_supplier_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('specification')->nullable();
            $table->string('unit');
            $table->decimal('panjang_material', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('project_id')->nullable();
            $table->decimal('bq', 10, 0)->nullable();
            $table->decimal('cut_per_day', 10, 0)->nullable();
        });

        // FK dipisah agar tidak trigger errno 150 di beberapa versi MySQL/MariaDB
        Schema::table('m_materials', function (Blueprint $table) {
            $table->foreign('m_supplier_id')
                  ->references('id')
                  ->on('m_suppliers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_materials');
    }
};
