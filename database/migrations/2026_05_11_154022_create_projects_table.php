<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menamai tabel secara eksplisit menjadi 'm_project'
        Schema::create('m_project', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // Kolom untuk menyimpan MMT, MPR, Panasonic
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_project');
    }
};