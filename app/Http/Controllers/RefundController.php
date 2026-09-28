<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Refund;
use App\Models\RefundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function create(Request $request)
    {
        $prefilledOrder = null;
        if ($request->filled('order')) {
            $prefilledOrder = Order::with('items')
                ->where('order_number', trim($request->order))
                ->first();
        }

        return view('shop.refund.create', compact('prefilledOrder'));
    }

    public function lookupOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $orderNumber = trim($request->order_number);
        $order = Order::with(['items', 'shipment', 'latestPayment'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan dengan nomor ' . $orderNumber . ' tidak ditemukan di sistem kami.',
            ], 404);
        }

        if (in_array($order->status, ['pending_payment', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini berstatus ' . $order->status_label . '. Pengajuan pengembalian dana hanya berlaku untuk pesanan yang sudah dibayar atau dalam proses pengiriman.',
            ], 422);
        }

        $activeRefund = Refund::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($activeRefund) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah memiliki pengajuan pengembalian dana aktif dengan nomor tiket ' . $activeRefund->refund_number . ' (Status: ' . $activeRefund->status_label . ').',
            ], 422);
        }

        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'variant_name' => $item->variant_name,
                'price' => (float)$item->price,
                'formatted_price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                'quantity' => $item->quantity,
                'subtotal' => (float)$item->total_price,
                'formatted_subtotal' => 'Rp ' . number_format($item->total_price, 0, ',', '.'),
            ];
        });

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'total_amount' => (float)$order->total_amount,
                'formatted_total' => $order->formatted_total,
                'shipping_cost' => (float)$order->shipping_cost,
                'formatted_shipping_cost' => $order->formatted_shipping_cost,
                'items' => $items,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|exists:orders,order_number',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'refund_type' => 'required|in:full,partial,return_and_refund',
            'reason' => 'required|string|max:255',
            'reason_detail' => 'nullable|string|max:2000',
            'refund_amount' => 'required|numeric|min:1000',
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:100',
            'bank_account_name' => 'required|string|max:255',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'selected_items' => 'nullable|array',
            'selected_items.*' => 'exists:order_items,id',
            'quantities' => 'nullable|array',
        ]);

        $order = Order::with('items')->where('order_number', $validated['order_number'])->firstOrFail();

        $existingRefund = Refund::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRefund) {
            return back()->with('error', 'Pesanan ini sudah memiliki pengajuan pengembalian dana yang sedang diproses (' . $existingRefund->refund_number . ').')->withInput();
        }

        if ($validated['refund_amount'] > $order->total_amount) {
            return back()->with('error', 'Nominal pengembalian dana tidak boleh melebihi total pesanan (' . $order->formatted_total . ').')->withInput();
        }

        DB::beginTransaction();
        try {
            $proofPath = null;
            if ($request->hasFile('proof_image')) {
                $proofPath = $request->file('proof_image')->store('refunds/proofs', 'public');
            }

            $refundNumber = Refund::generateRefundNumber();

            $refund = Refund::create([
                'refund_number' => $refundNumber,
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'refund_type' => $validated['refund_type'],
                'reason' => $validated['reason'],
                'reason_detail' => $validated['reason_detail'] ?? null,
                'refund_amount' => $validated['refund_amount'],
                'bank_name' => $validated['bank_name'],
                'bank_account_number' => $validated['bank_account_number'],
                'bank_account_name' => $validated['bank_account_name'],
                'proof_image' => $proofPath,
                'status' => 'pending',
            ]);

            if (!empty($validated['selected_items'])) {
                foreach ($validated['selected_items'] as $itemId) {
                    $orderItem = $order->items->firstWhere('id', $itemId);
                    if ($orderItem) {
                        $qty = isset($validated['quantities'][$itemId]) ? (int)$validated['quantities'][$itemId] : $orderItem->quantity;
                        $qty = min($qty, $orderItem->quantity);
                        $qty = max($qty, 1);

                        RefundItem::create([
                            'refund_id' => $refund->id,
                            'order_item_id' => $orderItem->id,
                            'product_name' => $orderItem->product_name,
                            'variant_name' => $orderItem->variant_name,
                            'quantity' => $qty,
                            'price' => $orderItem->price,
                            'subtotal' => $orderItem->price * $qty,
                        ]);
                    }
                }
            } else {
                foreach ($order->items as $orderItem) {
                    RefundItem::create([
                        'refund_id' => $refund->id,
                        'order_item_id' => $orderItem->id,
                        'product_name' => $orderItem->product_name,
                        'variant_name' => $orderItem->variant_name,
                        'quantity' => $orderItem->quantity,
                        'price' => $orderItem->price,
                        'subtotal' => $orderItem->total_price,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('refund.success', $refund->refund_number);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pengajuan: ' . $e->getMessage())->withInput();
        }
    }

    public function success($refundNumber)
    {
        $refund = Refund::with(['order', 'items'])
            ->where('refund_number', $refundNumber)
            ->firstOrFail();

        return view('shop.refund.success', compact('refund'));
    }

    public function track(Request $request)
    {
        $refund = null;
        $q = trim($request->get('q', ''));

        if (!empty($q)) {
            $refund = Refund::with(['order.items', 'items', 'approvedByUser', 'rejectedByUser', 'disbursedByUser'])
                ->where('refund_number', $q)
                ->orWhereHas('order', function ($query) use ($q) {
                    $query->where('order_number', $q);
                })
                ->latest()
                ->first();
        }

        return view('shop.refund.track', compact('refund', 'q'));
    }
}
