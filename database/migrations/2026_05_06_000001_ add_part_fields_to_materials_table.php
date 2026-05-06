<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'part_name')) {
                $table->string('part_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('materials', 'part_no')) {
                $table->string('part_no')->nullable()->after('part_name');
            }
            if (!Schema::hasColumn('materials', 'customer')) {
                $table->string('customer')->nullable()->after('part_no');
            }
            if (!Schema::hasColumn('materials', 'panjang_material')) {
                $table->decimal('panjang_material', 10, 2)->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('materials', 'panjang_part')) {
                $table->decimal('panjang_part', 10, 2)->nullable()->after('panjang_material');
            }
            if (!Schema::hasColumn('materials', 'bq')) {
                $table->decimal('bq', 10, 4)->nullable()->after('panjang_part')
                    ->comment('Batang per Quantity (panjang_material / (panjang_part + 3))');
            }
            if (!Schema::hasColumn('materials', 'max_stock')) {
                $table->decimal('max_stock', 10, 2)->nullable()->after('minimum_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('materials', 'part_name')        ? 'part_name'        : null,
                Schema::hasColumn('materials', 'part_no')          ? 'part_no'          : null,
                Schema::hasColumn('materials', 'customer')         ? 'customer'         : null,
                Schema::hasColumn('materials', 'panjang_material') ? 'panjang_material' : null,
                Schema::hasColumn('materials', 'panjang_part')     ? 'panjang_part'     : null,
                Schema::hasColumn('materials', 'bq')               ? 'bq'               : null,
                Schema::hasColumn('materials', 'max_stock')        ? 'max_stock'        : null,
            ]));
        });
    }
};