<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_production_qcs', function (Blueprint $table) {
            $table->string('wo_number')->nullable()->after('id');
            $table->foreignId('t_good_issue_id')->nullable()->constrained('t_good_issues')->after('m_part_id');
            $table->boolean('is_ng_returned')->default(false)->after('quantity_failed');
            $table->string('status', 30)->default('draft')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('t_production_qcs', function (Blueprint $table) {
            $table->dropForeign(['t_good_issue_id']);
            $table->dropColumn(['wo_number', 't_good_issue_id', 'is_ng_returned', 'status']);
        });
    }
};
