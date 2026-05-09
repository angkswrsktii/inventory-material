<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_qcs', function (Blueprint $table) {
            $table->id();
            $table->string('document_no')->unique();
            $table->date('qc_date');
            $table->foreignId('withdrawal_card_id')->constrained('withdrawal_cards')->onDelete('cascade');
            $table->string('gedung')->nullable();
            $table->decimal('qty_produksi', 10, 2)->default(0);
            $table->decimal('qty_sfg', 10, 2)->default(0);   // Barang Jadi
            $table->decimal('qty_ng', 10, 2)->default(0);    // Barang Cacat
            $table->text('ng_notes')->nullable();             // Keterangan cacat
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_qcs');
    }
};