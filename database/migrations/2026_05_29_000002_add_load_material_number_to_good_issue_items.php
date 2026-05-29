<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_good_issue_items', function (Blueprint $table) {
            $table->string('load_material_number', 50)->nullable()->after('m_material_id');
        });
    }

    public function down(): void
    {
        Schema::table('t_good_issue_items', function (Blueprint $table) {
            $table->dropColumn('load_material_number');
        });
    }
};
