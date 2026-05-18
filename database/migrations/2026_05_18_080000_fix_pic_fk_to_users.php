<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix t_good_issues
        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->dropForeign('t_good_issues_m_pic_id_foreign');
            $table->foreign('m_pic_id')->references('id')->on('m_users')->nullOnDelete();
        });

        // Fix t_good_receipts
        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->dropForeign('t_good_receipts_m_pic_id_foreign');
            $table->foreign('m_pic_id')->references('id')->on('m_users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->dropForeign('t_good_issues_m_pic_id_foreign');
            $table->foreign('m_pic_id')->references('id')->on('m_pics')->nullOnDelete();
        });

        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->dropForeign('t_good_receipts_m_pic_id_foreign');
            $table->foreign('m_pic_id')->references('id')->on('m_pics')->nullOnDelete();
        });
    }
};