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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_gateway')->default('midtrans'); // midtrans, xendit, tripay, dll
            $table->string('transaction_id')->nullable()->index(); // ID transaksi dari Payment Gateway
            $table->enum('payment_type', ['qris', 'virtual_account'])->index();
            $table->string('bank')->nullable(); // bca, mandiri, bni, bri, permata
            $table->string('va_number')->nullable(); // Nomor Virtual Account untuk ditransfer pembeli
            $table->longText('qr_string')->nullable(); // Data raw string atau URL QRIS
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->enum('status', [
                'pending',
                'settlement',
                'expired',
                'failed',
                'refunded'
            ])->default('pending')->index();
            $table->timestamp('expiry_time')->nullable(); // Batas waktu bayar
            $table->timestamp('paid_at')->nullable();
            $table->json('payload_response')->nullable(); // Response saat pembuatan invoice/charge
            $table->json('webhook_payload')->nullable(); // Response callback dari webhook PG
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
