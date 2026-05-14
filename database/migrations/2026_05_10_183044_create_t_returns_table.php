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
            $table->foreignId('t_good_issue_id')->constrained('t_good_issues');
            $table->foreignId('t_production_qc_id')->nullable()->constrained('t_production_qcs');
            $table->date('return_date');
            $table->text('notes')->nullable();
            $table->foreignId('returned_by')->constrained('m_users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('t_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_return_id')->constrained('t_returns')->cascadeOnDelete();
            $table->foreignId('m_material_id')->constrained('m_materials');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_return_items');
        Schema::dropIfExists('t_returns');
    }
};
