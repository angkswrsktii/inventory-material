<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('t_return_items');
        Schema::dropIfExists('t_returns');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
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
        });

        Schema::create('t_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_return_id');
            $table->unsignedBigInteger('m_material_id');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
