<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartService
{
    public function getOrCreateCart(Request $request): Cart
    {
        $sessionId = $request->session()->get('cart_session_id');

        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            $request->session()->put('cart_session_id', $sessionId);
        }

        $userId = auth()->id();

        if ($userId) {
            $cart = Cart::where('user_id', $userId)->first();
            if ($cart) {
                if ($cart->session_id !== $sessionId) {
                    $cart->update(['session_id' => $sessionId]);
                }
                return $cart;
            }
        }

        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'discount_amount' => 0,
            ]);
        } elseif ($userId && !$cart->user_id) {
            $cart->update(['user_id' => $userId]);
        }

        return $cart;
    }

    public function addItem(Cart $cart, int $variantId, int $quantity = 1): CartItem
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        $cartItem = $cart->items()->where('product_variant_id', $variantId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $variant->effective_price,
            ]);
            return $cartItem;
        }

        return $cart->items()->create([
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'price' => $variant->effective_price,
        ]);
    }

    public function updateQuantity(Cart $cart, int $itemId, int $quantity): bool
    {
        $item = $cart->items()->where('id', $itemId)->first();

        if (!$item) {
            return false;
        }

        if ($quantity <= 0) {
            return (bool) $item->delete();
        }

        return (bool) $item->update(['quantity' => $quantity]);
    }

    public function removeItem(Cart $cart, int $itemId): bool
    {
        $item = $cart->items()->where('id', $itemId)->first();

        if ($item) {
            return (bool) $item->delete();
        }

        return false;
    }

    public function applyCoupon(Cart $cart, string $code): array
    {
        $code = strtoupper(trim($code));

        if ($code === 'LUMENNEW') {
            $discount = min($cart->subtotal * 0.10, 50000);
            $cart->update([
                'coupon_code' => $code,
                'discount_amount' => $discount,
            ]);
            return [
                'success' => true,
                'message' => 'Kupon LUMENNEW berhasil dipasang (Diskon 10%)',
                'discount' => $discount,
            ];
        }

        if ($code === 'KIRIMINAJA') {
            $discount = 15000;
            $cart->update([
                'coupon_code' => $code,
                'discount_amount' => $discount,
            ]);
            return [
                'success' => true,
                'message' => 'Potongan ongkir KiriminAja Rp 15.000 berhasil dipasang',
                'discount' => $discount,
            ];
        }

        return [
            'success' => false,
            'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa',
        ];
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update([
            'coupon_code' => null,
            'discount_amount' => 0,
        ]);
    }
}
