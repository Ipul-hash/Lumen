<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\StoreSetting;
use App\Services\KiriminAjaService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with(['order.items', 'order.payments'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('courier')) {
            $query->where('courier_code', $request->courier);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('waybill_number', 'like', "%{$search}%")
                  ->orWhere('booking_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%")
                         ->orWhere('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        $shipments = $query->paginate(10)->withQueryString();

        $statusCounts = [
            'all' => Shipment::count(),
            'pending_pickup' => Shipment::where('status', 'pending_pickup')->count(),
            'picked_up' => Shipment::where('status', 'picked_up')->count(),
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        return view('admin.shipments.index', compact('shipments', 'statusCounts'));
    }

    public function track(Shipment $shipment, KiriminAjaService $service)
    {
        $tracking = $service->getTracking(
            $shipment->waybill_number ?? 'AWB-PENDING',
            $shipment->courier_code
        );

        return response()->json([
            'success' => true,
            'shipment' => [
                'waybill_number' => $shipment->waybill_number,
                'courier_name' => $shipment->courier_name,
                'service_type' => $shipment->service_type,
                'status_label' => $shipment->status_label,
                'order_number' => $shipment->order->order_number ?? '-',
                'customer_name' => $shipment->order->customer_name ?? '-',
                'destination' => ($shipment->order->shipping_address['district_name'] ?? '') . ', ' . ($shipment->order->shipping_address['city_name'] ?? ''),
            ],
            'checkpoints' => $tracking,
        ]);
    }

    public function calculator()
    {
        $originDistrict = StoreSetting::get('origin_district_name', 'Kebayoran Baru');
        $originCity = StoreSetting::get('origin_city_name', 'Jakarta Selatan');
        $originDistrictId = StoreSetting::get('origin_district_id', 2105);

        return view('admin.shipments.calculator', compact('originDistrict', 'originCity', 'originDistrictId'));
    }

    public function calculateRatesAjax(Request $request, KiriminAjaService $service)
    {
        $validated = $request->validate([
            'destination_district_id' => 'nullable|integer',
            'weight' => 'required|integer|min:1',
            'couriers' => 'nullable|array',
        ]);

        $originDistrictId = (int) StoreSetting::get('origin_district_id', 2105);
        $destDistrictId = (int) ($validated['destination_district_id'] ?? 2108);
        $couriers = $validated['couriers'] ?? ['jnt', 'sicepat', 'jne'];

        $rates = $service->calculateRates($originDistrictId, $destDistrictId, (int)$validated['weight'], $couriers);

        return response()->json([
            'success' => true,
            'rates' => $rates,
        ]);
    }

    public function batchPickup(Request $request, KiriminAjaService $service)
    {
        $validated = $request->validate([
            'shipment_ids' => 'required|array|min:1',
            'shipment_ids.*' => 'exists:shipments,id',
        ]);

        $successCount = 0;
        $unpaidCount = 0;
        $errors = [];

        foreach ($validated['shipment_ids'] as $shipmentId) {
            $shipment = Shipment::with('order')->find($shipmentId);
            if ($shipment && $shipment->order && !$shipment->hasWaybill()) {
                if (!$shipment->order->isPaid()) {
                    $unpaidCount++;
                    $errors[] = "Order {$shipment->order->order_number}: Pesanan belum lunas, tidak dapat di-pickup.";
                    continue;
                }

                try {
                    $service->requestPickup($shipment->order);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Order {$shipment->order->order_number}: {$e->getMessage()}";
                }
            }
        }

        if ($successCount > 0) {
            $msg = "Berhasil me-request pickup untuk {$successCount} paket ke KiriminAja.";
            if ($unpaidCount > 0) {
                $msg .= " Catatan: {$unpaidCount} paket dilewati karena belum lunas.";
            }
            return redirect()->back()->with('success', $msg);
        }

        if ($unpaidCount > 0) {
            return redirect()->back()
                ->with('error', "Gagal! {$unpaidCount} paket yang dipilih belum lunas. Pembayaran harus diverifikasi terlebih dahulu sebelum paket dapat dikirim.");
        }

        return redirect()->back()
            ->with('warning', 'Tidak ada paket yang diproses. Paket mungkin sudah memiliki nomor resi.');
    }

    public function settings()
    {
        $settings = [
            'store_name' => StoreSetting::get('store_name', 'LUMEN Hair Color'),
            'store_phone' => StoreSetting::get('store_phone', '081288990011'),
            'store_email' => StoreSetting::get('store_email', 'support@lumenhair.id'),
            'origin_province_id' => StoreSetting::get('origin_province_id', '6'),
            'origin_province_name' => StoreSetting::get('origin_province_name', 'DKI Jakarta'),
            'origin_city_id' => StoreSetting::get('origin_city_id', '153'),
            'origin_city_name' => StoreSetting::get('origin_city_name', 'Kota Jakarta Selatan'),
            'origin_district_id' => StoreSetting::get('origin_district_id', '2105'),
            'origin_district_name' => StoreSetting::get('origin_district_name', 'Kebayoran Baru'),
            'origin_postal_code' => StoreSetting::get('origin_postal_code', '12190'),
            'origin_address' => StoreSetting::get('origin_address', 'Jl. Radio Dalam Raya No. 42'),
            'kiriminaja_mode' => StoreSetting::get('kiriminaja_mode', 'sandbox'),
            'kiriminaja_api_key' => StoreSetting::get('kiriminaja_api_key', env('KIRIMINAJA_API_KEY', '')),
            'kiriminaja_pin' => StoreSetting::get('kiriminaja_pin', env('KIRIMINAJA_PIN', '123456')),
            'midtrans_merchant_id' => StoreSetting::get('midtrans_merchant_id', config('services.midtrans.merchant_id', '')),
            'midtrans_client_key' => StoreSetting::get('midtrans_client_key', config('services.midtrans.client_key', '')),
            'midtrans_server_key' => StoreSetting::get('midtrans_server_key', config('services.midtrans.server_key', '')),
            'midtrans_is_production' => StoreSetting::get('midtrans_is_production', '0'),
        ];

        return view('admin.shipments.settings', compact('settings'));
    }

    public function testConnection(KiriminAjaService $service)
    {
        $result = $service->testConnection();
        return response()->json($result);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_phone' => 'required|string|max:20',
            'store_email' => 'required|email|max:255',
            'origin_address' => 'required|string',
            'origin_province_name' => 'required|string',
            'origin_city_name' => 'required|string',
            'origin_district_name' => 'required|string',
            'origin_district_id' => 'required|integer',
            'origin_postal_code' => 'required|string|max:10',
            'kiriminaja_mode' => 'required|in:sandbox,production',
            'kiriminaja_api_key' => 'nullable|string',
            'kiriminaja_pin' => 'nullable|string',
            'midtrans_merchant_id' => 'nullable|string',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_server_key' => 'nullable|string',
            'midtrans_is_production' => 'nullable|in:0,1',
        ]);

        foreach ($validated as $key => $value) {
            StoreSetting::set($key, $value);
        }

        return redirect()->back()
            ->with('success', 'Pengaturan logistik KiriminAja dan Midtrans Payment Gateway berhasil disimpan.');
    }
}
