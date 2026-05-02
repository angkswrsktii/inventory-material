<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom enum role agar mendukung 4 nilai
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','karyawan','pimpinan','kepala_gudang') NOT NULL DEFAULT 'karyawan'");
    }

    public function down(): void
    {
        // Kembalikan ke 2 nilai saja (pastikan tidak ada data pimpinan/kepala_gudang dulu)
        DB::statement("UPDATE users SET role = 'karyawan' WHERE role IN ('pimpinan','kepala_gudang')");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','karyawan') NOT NULL DEFAULT 'karyawan'");
    }
};