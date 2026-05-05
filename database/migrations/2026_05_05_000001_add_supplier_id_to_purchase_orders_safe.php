<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('purchase_orders', 'supplier_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('supplier_id')->nullable()->after('purchase_request_id');
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('purchase_orders', 'supplier_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            });
        }
    }
};