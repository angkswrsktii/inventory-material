<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_customer_id')->nullable();
            $table->string('part_no')->unique();
            $table->string('part_name');
            $table->decimal('panjang_part', 10, 2)->nullable();
            $table->decimal('bq', 10, 4)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('m_parts', function (Blueprint $table) {
            $table->foreign('m_customer_id')
                  ->references('id')
                  ->on('m_customers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_parts');
    }
};
