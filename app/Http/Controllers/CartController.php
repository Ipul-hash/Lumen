<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService)
    {
        $cart = $cartService->getOrCreateCart($request);
        $cart->load(['items.variant.product.primaryImage']);

        return view('shop.cart', compact('cart'));
    }

    public function drawerData(Request $request, CartService $cartService)
    {
        $cart = $cartService->getOrCreateCart($request);
        $cart->load(['items.variant.product.primaryImage']);

        $items = $cart->items->map(function (CartItem $item) {
            $variant = $item->variant;
            $product = $variant->product ?? null;
            $image = $variant->image ?? ($product && $product->primaryImage ? $product->primaryImage->image_path : null);

            return [
                'id' => $item->id,
                'product_id' => $product ? $product->id : null,
                'product_name' => $product ? $product->name : 'Produk',
                'product_slug' => $product ? $product->slug : '#',
                'variant_id' => $variant->id,
                'variant_name' => $variant->name,
                'color_name' => $variant->color_name,
                'color_code' => $variant->color_code,
                'size' => $variant->size,
                'price' => (float)$item->price,
                'formatted_price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                'subtotal' => (float)$item->subtotal,
                'formatted_subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                'quantity' => $item->quantity,
                'stock' => $variant->stock,
                'image' => $image ? (str_starts_with($image, 'http') ? $image : asset('storage/' . $image)) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $items,
            'total_items' => $cart->total_items,
            'subtotal' => $cart->subtotal,
            'formatted_subtotal' => 'Rp ' . number_format($cart->subtotal, 0, ',', '.'),
            'discount_amount' => (float)$cart->discount_amount,
            'coupon_code' => $cart->coupon_code,
            'total_amount' => $cart->total_amount,
            'formatted_total' => 'Rp ' . number_format($cart->total_amount, 0, ',', '.'),
            'total_weight' => $cart->total_weight,
            'formatted_weight' => $cart->total_weight >= 1000 ? round($cart->total_weight / 1000, 2) . ' kg' : $cart->total_weight . ' gram',
        ]);
    }

    public function add(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variant = ProductVariant::with('product')->findOrFail($validated['variant_id']);

        if ($variant->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi.',
            ], 422);
        }

        $cart = $cartService->getOrCreateCart($request);
        $cartService->addItem($cart, $variant->id, $quantity);

        return response()->json([
            'success' => true,
            'message' => "{$variant->name} berhasil ditambahkan ke keranjang.",
            'total_items' => $cart->fresh()->total_items,
        ]);
    }

    public function addBundle(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1|max:20',
        ]);

        $cart = $cartService->getOrCreateCart($request);
        $addedCount = 0;

        foreach ($validated['items'] as $item) {
            $variant = ProductVariant::find($item['variant_id']);
            if ($variant && $variant->stock >= $item['quantity']) {
                $cartService->addItem($cart, $variant->id, (int)$item['quantity']);
                $addedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Paket racikan formula salon berhasil ditambahkan ke keranjang!',
            'added_count' => $addedCount,
            'total_items' => $cart->fresh()->total_items,
        ]);
    }

    public function update(Request $request, $itemId, CartService $cartService)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $cart = $cartService->getOrCreateCart($request);
        $success = $cartService->updateQuantity($cart, (int)$itemId, $validated['quantity']);

        if (!$success) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk diperbarui',
            'total_items' => $cart->fresh()->total_items,
            'subtotal' => $cart->fresh()->subtotal,
            'formatted_subtotal' => 'Rp ' . number_format($cart->fresh()->subtotal, 0, ',', '.'),
        ]);
    }

    public function remove(Request $request, $itemId, CartService $cartService)
    {
        $cart = $cartService->getOrCreateCart($request);
        $success = $cartService->removeItem($cart, (int)$itemId);

        if (!$success) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang',
            'total_items' => $cart->fresh()->total_items,
        ]);
    }

    public function applyCoupon(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = $cartService->getOrCreateCart($request);
        $result = $cartService->applyCoupon($cart, $validated['coupon_code']);

        return response()->json($result);
    }
}
