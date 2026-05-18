<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->unsignedBigInteger('t_good_issue_id');
            $table->unsignedBigInteger('t_production_qc_id')->nullable();
            $table->date('return_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('returned_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('t_good_issue_id')->references('id')->on('t_good_issues');
            $table->foreign('t_production_qc_id')->references('id')->on('t_production_qcs');
            $table->foreign('returned_by')->references('id')->on('m_users');
        });

        Schema::create('t_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_return_id');
            $table->unsignedBigInteger('m_material_id');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('Pcs');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('t_return_id')->references('id')->on('t_returns')->onDelete('cascade');
            $table->foreign('m_material_id')->references('id')->on('m_materials');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_return_items');
        Schema::dropIfExists('t_returns');
    }
};