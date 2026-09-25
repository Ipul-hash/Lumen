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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('summary')->nullable(); // Ringkasan singkat produk
            $table->longText('description')->nullable(); // Deskripsi lengkap produk
            $table->json('specifications')->nullable(); // Spesifikasi teknis (material, ketebalan, dll)
            $table->text('care_instructions')->nullable(); // Panduan perawatan
            $table->decimal('base_price', 12, 2);
            $table->unsignedInteger('weight')->default(1000); // Berat default dalam gram (Wajib untuk KiriminAja)
            $table->decimal('length', 8, 2)->default(0); // cm
            $table->decimal('width', 8, 2)->default(0); // cm
            $table->decimal('height', 8, 2)->default(0); // cm
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name'); // misal: "Black Sage - Standard (180cm)"
            $table->string('color_name')->nullable(); // misal: "Black Sage"
            $table->string('color_code', 20)->nullable(); // kode hex misal: "#1A252C" untuk swatches
            $table->string('size', 100)->nullable(); // misal: "Standard (6mm / 180cm)"
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('weight')->nullable(); // Berat spesifik varian (jika beda dari base product)
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
