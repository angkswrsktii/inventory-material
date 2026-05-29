<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_good_receipt_items', function (Blueprint $table) {
            $table->string('load_material_number', 50)->nullable()->after('m_material_id');
            $table->index('load_material_number');
        });
    }

    public function down(): void
    {
        Schema::table('t_good_receipt_items', function (Blueprint $table) {
            $table->dropIndex(['load_material_number']);
            $table->dropColumn('load_material_number');
        });
    }
};
