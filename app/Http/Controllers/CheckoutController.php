<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\StoreSetting;
use App\Services\CartService;
use App\Services\KiriminAjaService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request, CartService $cartService)
    {
        $cart = $cartService->getOrCreateCart($request);
        $cart->load(['items.variant.product.primaryImage']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('warning', 'Keranjang belanja Anda masih kosong.');
        }

        $destinations = [
            ['id' => 2108, 'name' => 'Tebet, Kota Jakarta Selatan, DKI Jakarta'],
            ['id' => 2101, 'name' => 'Cilandak, Kota Jakarta Selatan, DKI Jakarta'],
            ['id' => 2105, 'name' => 'Kebayoran Baru, Kota Jakarta Selatan, DKI Jakarta'],
            ['id' => 2110, 'name' => 'Gambir, Kota Jakarta Pusat, DKI Jakarta'],
            ['id' => 2189, 'name' => 'Bekasi Barat, Kota Bekasi, Jawa Barat'],
            ['id' => 2195, 'name' => 'Bogor Tengah, Kota Bogor, Jawa Barat'],
            ['id' => 2200, 'name' => 'Depok, Kota Depok, Jawa Barat'],
            ['id' => 2210, 'name' => 'Coblong, Kota Bandung, Jawa Barat'],
            ['id' => 2305, 'name' => 'Banyumanik, Kota Semarang, Jawa Tengah'],
            ['id' => 2380, 'name' => 'Depok, Kab. Sleman, DI Yogyakarta'],
            ['id' => 2385, 'name' => 'Danurejan, Kota Yogyakarta, DI Yogyakarta'],
            ['id' => 2450, 'name' => 'Gubeng, Kota Surabaya, Jawa Timur'],
            ['id' => 2600, 'name' => 'Denpasar Selatan, Kota Denpasar, Bali'],
            ['id' => 1200, 'name' => 'Medan Kota, Kota Medan, Sumatera Utara'],
        ];

        return view('shop.checkout', compact('cart', 'destinations'));
    }

    public function getShippingRates(Request $request, CartService $cartService, KiriminAjaService $kiriminAja)
    {
        $validated = $request->validate([
            'destination_district_id' => 'required|integer',
        ]);

        $cart = $cartService->getOrCreateCart($request);
        $originDistrictId = (int) StoreSetting::get('origin_district_id', 2105);
        $destDistrictId = (int) $validated['destination_district_id'];
        $weight = max(250, $cart->total_weight);

        $rates = $kiriminAja->calculateRates($originDistrictId, $destDistrictId, $weight);

        return response()->json([
            'success' => true,
            'weight' => $weight,
            'rates' => $rates,
        ]);
    }

    public function process(
        Request $request,
        CartService $cartService,
        PaymentService $paymentService
    ) {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'province_name' => 'required|string|max:100',
            'city_name' => 'required|string|max:100',
            'district_name' => 'required|string|max:100',
            'district_id' => 'required|integer',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'courier_code' => 'required|string',
            'courier_name' => 'required|string',
            'service_type' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_type' => 'required|in:qris,virtual_account',
            'bank' => 'nullable|required_if:payment_type,virtual_account|in:bca,mandiri,bni,bri',
        ]);

        $cart = $cartService->getOrCreateCart($request);
        $cart->load(['items.variant.product']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('warning', 'Keranjang belanja Anda kosong.');
        }

        $order = DB::transaction(function () use ($validated, $cart, $paymentService) {
            $subtotal = $cart->subtotal;
            $discountAmount = $cart->discount_amount;
            $shippingCost = (float) $validated['shipping_cost'];
            $totalAmount = max(0, ($subtotal - $discountAmount) + $shippingCost);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => [
                    'recipient_name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'address_line' => $validated['address_line'],
                    'district_name' => $validated['district_name'],
                    'district_id' => (int) $validated['district_id'],
                    'city_name' => $validated['city_name'],
                    'province_name' => $validated['province_name'],
                    'postal_code' => $validated['postal_code'],
                ],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart->items as $cartItem) {
                $variant = $cartItem->variant;
                $product = $variant->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant->name,
                    'sku' => $variant->sku,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'weight' => $variant->effective_weight,
                    'total_price' => $cartItem->subtotal,
                ]);

                if ($variant->stock >= $cartItem->quantity) {
                    $variant->decrement('stock', $cartItem->quantity);
                }
            }

            $originDistrictId = (int) StoreSetting::get('origin_district_id', 2105);

            Shipment::create([
                'order_id' => $order->id,
                'courier_name' => $validated['courier_name'],
                'courier_code' => strtolower($validated['courier_code']),
                'service_type' => $validated['service_type'],
                'shipping_cost' => $shippingCost,
                'insurance_cost' => 0,
                'total_weight' => max(250, $cart->total_weight),
                'origin_district_id' => $originDistrictId,
                'destination_district_id' => (int) $validated['district_id'],
                'status' => 'pending_pickup',
            ]);

            $paymentService->createPayment(
                $order,
                $validated['payment_type'],
                $validated['bank'] ?? null
            );

            return $order;
        });

        $cartService->clear($cart);

        return redirect()->route('checkout.payment', $order->order_number);
    }

    public function payment(string $orderNumber, PaymentService $paymentService)
    {
        $order = Order::with(['items', 'shipment', 'latestPayment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $payment = $order->latestPayment;

        if (!$payment) {
            $payment = $paymentService->createPayment($order, 'qris');
        }

        if ($payment->isSettled() || $order->isPaid()) {
            return redirect()->route('checkout.success', $order->order_number);
        }

        $instructions = [];
        if ($payment->payment_type === 'virtual_account') {
            $instructions = $paymentService->getBankInstructions($payment->bank ?? 'BCA', $payment->va_number ?? '');
        }

        $gateway = $paymentService->getGateway();
        $clientKey = $gateway->getClientKey();
        $snapJsUrl = $gateway->getSnapJsUrl();
        $snapToken = $payment->payload_response['snap_token'] ?? null;

        return view('shop.payment', compact('order', 'payment', 'instructions', 'clientKey', 'snapJsUrl', 'snapToken'));
    }

    public function simulateSuccess(Payment $payment, PaymentService $paymentService, KiriminAjaService $kiriminAja)
    {
        $paymentService->settlePayment($payment);

        $order = $payment->order;
        if ($order && $order->shipment && !$order->shipment->hasWaybill()) {
            try {
                $kiriminAja->requestPickup($order);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Pembayaran berhasil diverifikasi secara real-time! Paket Anda telah diteruskan ke logistik KiriminAja.');
    }

    public function midtransWebhook(Request $request, PaymentService $paymentService)
    {
        $payload = $request->all();
        $result = $paymentService->handleWebhook($payload);

        if (!($result['success'] ?? false)) {
            return response()->json($result, 400);
        }

        return response()->json($result, 200);
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items', 'shipment', 'latestPayment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('shop.success', compact('order'));
    }
}
