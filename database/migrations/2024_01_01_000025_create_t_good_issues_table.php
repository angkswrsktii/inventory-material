<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_good_issues', function (Blueprint $table) {
            $table->id();
            $table->string('gi_number')->unique();
            $table->unsignedBigInteger('m_warehouse_id');
            $table->unsignedBigInteger('m_part_id')->nullable();
            $table->date('issue_date');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('issued_by');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('m_pic_id')->nullable();
            $table->integer('m_project_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->foreign('m_warehouse_id')->references('id')->on('m_warehouses');
            $table->foreign('m_part_id')->references('id')->on('m_parts')->nullOnDelete();
            $table->foreign('issued_by')->references('id')->on('m_users');
            $table->foreign('received_by')->references('id')->on('m_users')->nullOnDelete();
            $table->foreign('m_pic_id')->references('id')->on('m_users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_good_issues');
    }
};