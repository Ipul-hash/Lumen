<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'primaryImage']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'spec_keys' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:100|distinct|unique:product_variants,sku',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.color_name' => 'nullable|string|max:100',
            'variants.*.color_code' => 'nullable|string|max:20',
            'variants.*.size' => 'nullable|string|max:100',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|integer|min:1',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $specifications = [];
        if (! empty($request->spec_keys) && ! empty($request->spec_values)) {
            foreach ($request->spec_keys as $index => $key) {
                if (! empty($key) && isset($request->spec_values[$index])) {
                    $specifications[$key] = $request->spec_values[$index];
                }
            }
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'care_instructions' => $validated['care_instructions'] ?? null,
            'base_price' => $validated['base_price'],
            'weight' => $validated['weight'],
            'length' => $validated['length'] ?? 0,
            'width' => $validated['width'] ?? 0,
            'height' => $validated['height'] ?? 0,
            'specifications' => count($specifications) > 0 ? $specifications : null,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        $firstVariantImagePath = null;

        foreach ($request->variants as $index => $variantData) {
            $variantImagePath = null;
            if ($request->hasFile("variants.{$index}.image")) {
                $variantImagePath = $request->file("variants.{$index}.image")->store('variants', 'public');
                if (! $firstVariantImagePath) {
                    $firstVariantImagePath = $variantImagePath;
                }
            }

            $product->variants()->create([
                'sku' => $variantData['sku'],
                'name' => $variantData['name'],
                'color_name' => $variantData['color_name'] ?? null,
                'color_code' => $variantData['color_code'] ?? null,
                'size' => $variantData['size'] ?? null,
                'price' => $variantData['price'],
                'discount_price' => ! empty($variantData['discount_price']) ? $variantData['discount_price'] : null,
                'stock' => $variantData['stock'],
                'weight' => ! empty($variantData['weight']) ? $variantData['weight'] : $product->weight,
                'image' => $variantImagePath,
                'is_active' => true,
            ]);
        }

        if ($firstVariantImagePath) {
            $product->images()->create([
                'image_path' => $firstVariantImagePath,
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk dan varian berhasil disimpan.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$product->id,
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'spec_keys' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.color_name' => 'nullable|string|max:100',
            'variants.*.color_code' => 'nullable|string|max:20',
            'variants.*.size' => 'nullable|string|max:100',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|integer|min:1',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $specifications = [];
        if (! empty($request->spec_keys) && ! empty($request->spec_values)) {
            foreach ($request->spec_keys as $index => $key) {
                if (! empty($key) && isset($request->spec_values[$index])) {
                    $specifications[$key] = $request->spec_values[$index];
                }
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'care_instructions' => $validated['care_instructions'] ?? null,
            'base_price' => $validated['base_price'],
            'weight' => $validated['weight'],
            'length' => $validated['length'] ?? 0,
            'width' => $validated['width'] ?? 0,
            'height' => $validated['height'] ?? 0,
            'specifications' => count($specifications) > 0 ? $specifications : null,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        $existingVariantIds = [];

        foreach ($request->variants as $index => $variantData) {
            $variantId = $variantData['id'] ?? null;

            $data = [
                'sku' => $variantData['sku'],
                'name' => $variantData['name'],
                'color_name' => $variantData['color_name'] ?? null,
                'color_code' => $variantData['color_code'] ?? null,
                'size' => $variantData['size'] ?? null,
                'price' => $variantData['price'],
                'discount_price' => ! empty($variantData['discount_price']) ? $variantData['discount_price'] : null,
                'stock' => $variantData['stock'],
                'weight' => ! empty($variantData['weight']) ? $variantData['weight'] : $product->weight,
                'is_active' => true,
            ];

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant && $variant->product_id == $product->id) {
                    if ($request->hasFile("variants.{$index}.image")) {
                        if ($variant->image && ! str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $data['image'] = $request->file("variants.{$index}.image")->store('variants', 'public');
                    } elseif (! empty($variantData['remove_image'])) {
                        if ($variant->image && ! str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $data['image'] = null;
                    }

                    $variant->update($data);
                    $existingVariantIds[] = $variant->id;
                }
            } else {
                if ($request->hasFile("variants.{$index}.image")) {
                    $data['image'] = $request->file("variants.{$index}.image")->store('variants', 'public');
                }
                $newVariant = $product->variants()->create($data);
                $existingVariantIds[] = $newVariant->id;
            }
        }

        $variantsToDelete = $product->variants()->whereNotIn('id', $existingVariantIds)->get();
        foreach ($variantsToDelete as $toDelete) {
            if ($toDelete->image && ! str_starts_with($toDelete->image, 'http') && Storage::disk('public')->exists($toDelete->image)) {
                Storage::disk('public')->delete($toDelete->image);
            }
            $toDelete->delete();
        }

        // If product has no primary image, associate the first variant image
        if ($product->images()->count() === 0) {
            $variantWithImage = $product->variants()->whereNotNull('image')->first();
            if ($variantWithImage) {
                $product->images()->create([
                    'image_path' => $variantWithImage->image,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk dan varian berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->variants as $variant) {
            if ($variant->image && ! str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        foreach ($product->images as $img) {
            if ($img->image_path && ! str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
