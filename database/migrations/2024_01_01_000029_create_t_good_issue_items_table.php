<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_good_issue_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_good_issue_id');
            $table->unsignedBigInteger('m_material_id');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('Pcs');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('t_good_issue_id')->references('id')->on('t_good_issues')->onDelete('cascade');
            $table->foreign('m_material_id')->references('id')->on('m_materials');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_good_issue_items');
    }
};