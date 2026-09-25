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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Rumah'); // Rumah, Kantor, Toko, dll
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address_line'); // Alamat lengkap
            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('province_name');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('city_name');
            $table->unsignedBigInteger('district_id')->nullable(); // ID Kecamatan (KiriminAja)
            $table->string('district_name');
            $table->unsignedBigInteger('subdistrict_id')->nullable(); // ID Kelurahan (KiriminAja)
            $table->string('subdistrict_name')->nullable();
            $table->string('postal_code', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
