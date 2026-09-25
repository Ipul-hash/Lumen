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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('courier_name'); // misal: J&T Express, SiCepat, JNE
            $table->string('courier_code'); // misal: jnt, sicepat, jne
            $table->string('service_type'); // misal: EZ, REG, GOKIL
            $table->string('booking_id')->nullable()->index(); // ID Booking dari KiriminAja
            $table->string('waybill_number')->nullable()->index(); // Nomor Resi (AWB)
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('insurance_cost', 12, 2)->default(0);
            $table->unsignedInteger('total_weight'); // Total berat dalam gram
            $table->unsignedBigInteger('origin_district_id'); // ID wilayah asal KiriminAja
            $table->unsignedBigInteger('destination_district_id'); // ID wilayah tujuan KiriminAja
            $table->enum('status', [
                'pending_pickup',
                'picked_up',
                'in_transit',
                'delivered',
                'returned',
                'cancelled'
            ])->default('pending_pickup')->index();
            $table->timestamp('pickup_scheduled_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('kiriminaja_response')->nullable(); // Response saat request booking KiriminAja
            $table->json('tracking_history')->nullable(); // Checkpoints history lacak paket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
