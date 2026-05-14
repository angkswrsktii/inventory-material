<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_production_qcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_part_id')->constrained('m_parts');
            $table->foreignId('checked_by')->constrained('m_users');
            $table->date('qc_date');
            $table->decimal('quantity_passed', 10, 2)->default(0);
            $table->decimal('quantity_failed', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_production_qcs');
    }
};
