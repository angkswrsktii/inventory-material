<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ini sebelumnya menambahkan m_pic_id ke t_good_receipts dan t_good_issues.
 * Kolom tersebut sudah dipindahkan langsung ke migration dasar masing-masing tabel,
 * sehingga migration ini tidak perlu melakukan apa-apa.
 * Tetap dipertahankan agar urutan batch tidak berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->foreign('m_pic_id')->references('id')->on('m_pics')->nullOnDelete();
        });

        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->foreign('m_pic_id')->references('id')->on('m_pics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->dropForeign(['m_pic_id']);
        });

        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->dropForeign(['m_pic_id']);
        });
    }
};