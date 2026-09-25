<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'latestPayment', 'shipment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('courier')) {
            $courier = $request->courier;
            $query->whereHas('shipment', function ($q) use ($courier) {
                $q->where('courier_code', $courier);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'pending_payment' => Order::where('status', 'pending_payment')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function create()
    {
        $products = Product::with(['variants' => function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        }])->where('is_active', true)->get();

        $originAddress = StoreSetting::get('origin_address');
        $originCity = StoreSetting::get('origin_city_name');
        $originDistrict = StoreSetting::get('origin_district_name');

        return view('admin.orders.create', compact('products', 'originAddress', 'originCity', 'originDistrict'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'address_line' => 'required|string',
            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'district_name' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'courier_code' => 'required|string|in:jnt,sicepat,jne',
            'service_type' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_type' => 'required|in:qris,virtual_account',
            'bank' => 'nullable|required_if:payment_type,virtual_account|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $totalWeight = 0;
        $orderItemsData = [];

        foreach ($request->items as $itemInput) {
            $variant = ProductVariant::with('product')->findOrFail($itemInput['variant_id']);
            $qty = (int) $itemInput['quantity'];

            if ($variant->stock < $qty) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['items' => "Stok varian {$variant->product->name} ({$variant->name}) tidak mencukupi. Sisa stok: {$variant->stock}"]);
            }

            $price = $variant->effective_price;
            $lineTotal = $price * $qty;
            $weight = $variant->effective_weight;

            $subtotal += $lineTotal;
            $totalWeight += ($weight * $qty);

            $orderItemsData[] = [
                'variant' => $variant,
                'product_name' => $variant->product->name,
                'variant_name' => $variant->name,
                'sku' => $variant->sku,
                'price' => $price,
                'quantity' => $qty,
                'weight' => $weight,
                'total_price' => $lineTotal,
            ];
        }

        $shippingCost = (float) $validated['shipping_cost'];
        $totalAmount = $subtotal + $shippingCost;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'shipping_address' => [
                'recipient_name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
                'address_line' => $validated['address_line'],
                'province_name' => $validated['province_name'],
                'city_name' => $validated['city_name'],
                'district_name' => $validated['district_name'],
                'postal_code' => $validated['postal_code'],
            ],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => 0,
            'total_amount' => $totalAmount,
            'status' => 'pending_payment',
        ]);

        foreach ($orderItemsData as $itemData) {
            $order->items()->create([
                'product_variant_id' => $itemData['variant']->id,
                'product_name' => $itemData['product_name'],
                'variant_name' => $itemData['variant_name'],
                'sku' => $itemData['sku'],
                'price' => $itemData['price'],
                'quantity' => $itemData['quantity'],
                'weight' => $itemData['weight'],
                'total_price' => $itemData['total_price'],
            ]);

            $itemData['variant']->decrement('stock', $itemData['quantity']);
        }

        $courierNames = [
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'jne' => 'JNE',
        ];

        Shipment::create([
            'order_id' => $order->id,
            'courier_name' => $courierNames[$validated['courier_code']] ?? strtoupper($validated['courier_code']),
            'courier_code' => $validated['courier_code'],
            'service_type' => $validated['service_type'],
            'shipping_cost' => $shippingCost,
            'total_weight' => $totalWeight,
            'origin_district_id' => (int) StoreSetting::get('origin_district_id', 2105),
            'destination_district_id' => 2108,
            'status' => 'pending_pickup',
        ]);

        $paymentData = [
            'order_id' => $order->id,
            'payment_gateway' => 'midtrans',
            'transaction_id' => 'TRX-' . strtoupper(uniqid()),
            'payment_type' => $validated['payment_type'],
            'amount' => $totalAmount,
            'status' => 'pending',
            'expiry_time' => now()->addHours(24),
        ];

        if ($validated['payment_type'] === 'qris') {
            $paymentData['qr_string'] = '00020101021226670014ID.LINKAJA.WWW011893600911002234000102150000000000000150303UMI51440014ID.GO.BI.QRIS0102195204481453033605802ID5914LUMEN COLOR STORE6015JAKARTA SELATAN61051219062070703A0163045E1B';
        } else {
            $paymentData['bank'] = $validated['bank'];
            $bankPrefixes = ['bca' => '8271', 'mandiri' => '8890', 'bni' => '8241', 'bri' => '8012'];
            $prefix = $bankPrefixes[$validated['bank']] ?? '8999';
            $paymentData['va_number'] = $prefix . substr(preg_replace('/[^0-9]/', '', $validated['customer_phone']), -8);
        }

        Payment::create($paymentData);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan baru berhasil dibuat dengan nomor: ' . $order->order_number);
    }

    public function show(Order $order)
    {
        $order->load(['items.variant.product', 'payments', 'shipment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending_payment,paid,processing,shipped,completed,cancelled',
        ]);

        if (in_array($validated['status'], ['shipped', 'completed']) && !$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Pesanan belum lunas! Pembayaran harus diverifikasi terlebih dahulu sebelum pesanan dapat dikirim.');
        }

        $previousStatus = $order->status;
        $order->status = $validated['status'];

        if ($validated['status'] === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
        } elseif ($validated['status'] === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        } elseif ($validated['status'] === 'completed' && !$order->completed_at) {
            $order->completed_at = now();
        } elseif ($validated['status'] === 'cancelled') {
            $order->cancelled_at = now();
            if ($previousStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            }
        }

        $order->save();

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $order->status_label);
    }

    public function confirmPayment(Order $order)
    {
        if ($order->isPaid()) {
            return redirect()->back()->with('warning', 'Pesanan ini sudah berstatus lunas.');
        }

        $order->update([
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        if ($order->latestPayment) {
            $order->latestPayment->update([
                'status' => 'settlement',
                'paid_at' => now(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Pembayaran pesanan ' . $order->order_number . ' telah diverifikasi Lunas.');
    }

    public function cancelOrder(Order $order)
    {
        if ($order->status === 'cancelled') {
            return redirect()->back()->with('warning', 'Pesanan ini sudah dibatalkan.');
        }

        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        if ($order->latestPayment && $order->latestPayment->status === 'pending') {
            $order->latestPayment->update(['status' => 'expired']);
        }

        if ($order->shipment) {
            $order->shipment->update(['status' => 'cancelled']);
        }

        return redirect()->back()
            ->with('success', 'Pesanan berhasil dibatalkan dan seluruh stok produk telah dikembalikan.');
    }

    public function requestPickup(Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->back()->with('error', 'Pesanan belum lunas! Pembayaran harus diverifikasi terlebih dahulu sebelum paket dapat dikirim ke KiriminAja.');
        }

        if (!$order->shipment) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki data pengiriman.');
        }

        $shipment = $order->shipment;

        if ($shipment->hasWaybill()) {
            return redirect()->back()->with('warning', 'Pesanan ini sudah memiliki nomor resi AWB: ' . $shipment->waybill_number);
        }

        $prefix = strtoupper($shipment->courier_code);
        $generatedAwb = $prefix . date('ymd') . rand(100000, 999999) . 'ID';
        $bookingId = 'KA-BKG-' . rand(100000, 999999);

        $shipment->update([
            'booking_id' => $bookingId,
            'waybill_number' => $generatedAwb,
            'status' => 'picked_up',
            'pickup_scheduled_at' => now(),
            'shipped_at' => now(),
        ]);

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Sukses request pickup KiriminAja! Resi AWB terbit: ' . $generatedAwb);
    }

    public function printShippingLabel(Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Label resi terkunci! Pesanan belum lunas.');
        }

        $order->load(['items.variant', 'shipment']);
        $storeSetting = [
            'name' => StoreSetting::get('store_name', 'LUMEN Hair Color'),
            'phone' => StoreSetting::get('store_phone', '081288990011'),
            'address' => StoreSetting::get('origin_address', 'Jakarta Selatan'),
            'city' => StoreSetting::get('origin_city_name', 'Jakarta Selatan'),
            'postal_code' => StoreSetting::get('origin_postal_code', '12190'),
        ];

        return view('admin.orders.shipping-label', compact('order', 'storeSetting'));
    }

    public function printInvoice(Order $order)
    {
        $order->load(['items.variant', 'latestPayment', 'shipment']);
        $storeSetting = [
            'name' => StoreSetting::get('store_name', 'LUMEN Hair Color'),
            'phone' => StoreSetting::get('store_phone', '081288990011'),
            'email' => StoreSetting::get('store_email', 'support@lumenhair.id'),
            'address' => StoreSetting::get('origin_address', 'Jakarta Selatan'),
        ];

        return view('admin.orders.invoice', compact('order', 'storeSetting'));
    }
}
