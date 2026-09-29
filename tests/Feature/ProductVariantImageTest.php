<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\PerfumeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can create product with variant image upload', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $category = Category::create([
        'name' => 'Parfum',
        'slug' => 'parfum',
        'is_active' => true,
    ]);

    $fakeImage = UploadedFile::fake()->image('flacon_30ml.jpg', 600, 600);

    $response = $this->actingAs($admin)->post(route('admin.products.store'), [
        'category_id' => $category->id,
        'name' => 'LUMEN Oud Royal',
        'slug' => 'lumen-oud-royal',
        'base_price' => 250000,
        'weight' => 200,
        'variants' => [
            [
                'name' => 'Flacon 30ml',
                'sku' => 'LMN-OUD-30',
                'color_name' => 'Royal Amber',
                'color_code' => '#D4AF37',
                'size' => '30ml',
                'price' => 250000,
                'stock' => 15,
                'image' => $fakeImage,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.products.index'));

    $product = Product::where('slug', 'lumen-oud-royal')->first();
    expect($product)->not->toBeNull();

    $variant = $product->variants->first();
    expect($variant)->not->toBeNull()
        ->and($variant->image)->not->toBeNull();

    Storage::disk('public')->assertExists($variant->image);
});

test('admin can update product variant image and remove it', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $category = Category::create([
        'name' => 'Parfum',
        'slug' => 'parfum',
        'is_active' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'LUMEN Santal',
        'slug' => 'lumen-santal',
        'base_price' => 200000,
        'weight' => 200,
    ]);

    $variant = $product->variants()->create([
        'name' => 'Flacon 50ml',
        'sku' => 'LMN-SNT-50',
        'price' => 200000,
        'stock' => 10,
    ]);

    $newImage = UploadedFile::fake()->image('new_flacon.jpg', 600, 600);

    // Update with new image
    $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
        'category_id' => $category->id,
        'name' => 'LUMEN Santal Updated',
        'slug' => 'lumen-santal',
        'base_price' => 220000,
        'weight' => 200,
        'variants' => [
            [
                'id' => $variant->id,
                'name' => 'Flacon 50ml Updated',
                'sku' => 'LMN-SNT-50',
                'price' => 220000,
                'stock' => 12,
                'image' => $newImage,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.products.index'));

    $variant->refresh();
    expect($variant->image)->not->toBeNull();
    Storage::disk('public')->assertExists($variant->image);

    // Now update and remove the image
    $response2 = $this->actingAs($admin)->put(route('admin.products.update', $product), [
        'category_id' => $category->id,
        'name' => 'LUMEN Santal Updated',
        'slug' => 'lumen-santal',
        'base_price' => 220000,
        'weight' => 200,
        'variants' => [
            [
                'id' => $variant->id,
                'name' => 'Flacon 50ml Updated',
                'sku' => 'LMN-SNT-50',
                'price' => 220000,
                'stock' => 12,
                'remove_image' => '1',
            ],
        ],
    ]);

    $response2->assertRedirect(route('admin.products.index'));

    $variant->refresh();
    expect($variant->image)->toBeNull();
});

test('perfume products and variants are seeded correctly with images', function () {
    $this->seed(PerfumeSeeder::class);

    $category = Category::where('slug', 'parfum-fragrance')->first();
    expect($category)->not->toBeNull()
        ->and($category->products)->toHaveCount(3);

    $santal = Product::where('slug', 'lumen-extrait-de-parfum-santal-blanc-amber')->first();
    expect($santal)->not->toBeNull()
        ->and($santal->variants)->toHaveCount(3);

    foreach ($santal->variants as $variant) {
        expect($variant->image)->not->toBeNull();
        expect($variant->image_url)->not->toBeNull();
    }
});

test('shop show page displays variant image data correctly', function () {
    $this->seed(PerfumeSeeder::class);

    $santal = Product::where('slug', 'lumen-extrait-de-parfum-santal-blanc-amber')->first();

    $response = $this->get(route('shop.show', $santal->slug));
    $response->assertStatus(200);
    $response->assertSee('Travel Spray 30ml');
    $response->assertSee('Signature Flacon 50ml');
    $response->assertSee('Grand Flacon 100ml');
    $response->assertSee('data-image=', false);
});
