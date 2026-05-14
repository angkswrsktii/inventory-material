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
        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->foreignId('m_pic_id')->nullable()->constrained('m_pics')->nullOnDelete()->after('m_warehouse_id');
        });

        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->foreignId('m_pic_id')->nullable()->constrained('m_pics')->nullOnDelete()->after('received_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_good_issues', function (Blueprint $table) {
            $table->dropForeign(['m_pic_id']);
            $table->dropColumn('m_pic_id');
        });

        Schema::table('t_good_receipts', function (Blueprint $table) {
            $table->dropForeign(['m_pic_id']);
            $table->dropColumn('m_pic_id');
        });
    }
};
