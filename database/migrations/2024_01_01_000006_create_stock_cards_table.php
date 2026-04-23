<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->date('transaction_date');
            $table->enum('type', ['in', 'out']);
            $table->decimal('quantity_in', 10, 2)->default(0);
            $table->decimal('quantity_out', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('reference_no')->nullable();
            $table->string('source')->nullable(); // supplier or withdrawal
            $table->text('notes')->nullable();
            $table->foreignId('withdrawal_card_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};
