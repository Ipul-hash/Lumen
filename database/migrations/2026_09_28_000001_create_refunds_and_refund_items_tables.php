<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_number')->unique()->index();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('refund_type', ['full', 'partial', 'return_and_refund'])->default('full');
            $table->string('reason');
            $table->text('reason_detail')->nullable();
            $table->decimal('refund_amount', 12, 2);
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');
            $table->string('proof_image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded'])->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disbursed_at')->nullable();
            $table->foreignId('disbursed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('disbursement_method')->nullable();
            $table->string('disbursement_reference')->nullable();
            $table->string('disbursement_proof')->nullable();
            $table->timestamps();
        });

        Schema::create('refund_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_items');
        Schema::dropIfExists('refunds');
    }
};
