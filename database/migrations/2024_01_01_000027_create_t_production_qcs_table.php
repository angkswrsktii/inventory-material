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
            $table->string('wo_number')->nullable();
            $table->unsignedBigInteger('m_part_id');
            $table->unsignedBigInteger('checked_by');
            $table->date('qc_date');
            $table->decimal('quantity_passed', 10, 0)->default(0);
            $table->decimal('quantity_failed', 10, 0)->default(0);
            $table->decimal('quantity_failed_retur', 10, 0)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'approved'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('t_good_issue_id')->nullable();
        });

        Schema::table('t_production_qcs', function (Blueprint $table) {
            $table->foreign('m_part_id')->references('id')->on('m_parts');
            $table->foreign('checked_by')->references('id')->on('m_users');
            $table->foreign('t_good_issue_id')->references('id')->on('t_good_issues');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_production_qcs');
    }
};
