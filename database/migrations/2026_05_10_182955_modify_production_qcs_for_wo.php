<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ini sebelumnya menambahkan wo_number, t_good_issue_id, is_ng_returned, dan status
 * ke t_production_qcs. Kolom-kolom tersebut sudah dipindahkan langsung ke migration dasar
 * 2024_01_01_000027_create_t_production_qcs_table.php.
 * Migration ini dipertahankan agar batch history tidak berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Semua kolom sudah ada di 2024_01_01_000027_create_t_production_qcs_table
    }

    public function down(): void
    {
        // nothing to reverse
    }
};
