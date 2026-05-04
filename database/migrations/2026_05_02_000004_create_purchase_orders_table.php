<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('document_no')->unique();
            $table->foreignId('purchase_request_id')->constrained()->cascadeOnDelete();
            $table->date('order_date');
            $table->date('expected_date')->nullable();           // estimasi tanggal terima
            $table->string('supplier_name');                    // nama supplier (free text sesuai sketsa)
            $table->string('supplier_contact')->nullable();     // kontak supplier
            $table->text('delivery_address')->nullable();       // alamat pengiriman
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'sent', 'partial', 'received', 'cancelled'])
                  ->default('draft');
            $table->decimal('total_amount', 15, 2)->nullable(); // total harga PO
            $table->string('payment_terms')->nullable();        // termin pembayaran
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};