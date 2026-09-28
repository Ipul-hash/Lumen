<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;

class KiriminAjaService
{
    protected string $apiKey;
    protected string $mode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) StoreSetting::get('kiriminaja_api_key', env('KIRIMINAJA_API_KEY', ''));
        $this->mode = (string) StoreSetting::get('kiriminaja_mode', env('KIRIMINAJA_MODE', 'staging'));
        $this->baseUrl = $this->mode === 'production' 
            ? 'https://client.kiriminaja.com/api/mitra' 
            : 'https://tdev.kiriminaja.com/api/mitra';
    }

    protected function client()
    {
        $client = Http::withoutVerifying();
        $proxy = env('FIXIE_URL') ?: env('HTTP_PROXY') ?: StoreSetting::get('kiriminaja_proxy');
        if (!empty($proxy)) {
            $client = $client->withOptions(['proxy' => $proxy]);
        }
        return $client;
    }

    public function calculateRates(int $originDistrictId, int $destinationDistrictId, int $weightInGrams, array $couriers = ['jnt', 'sicepat', 'jne']): array
    {
        if (!empty($this->apiKey)) {
            try {
                $response = $this->client()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(6)
                    ->post("{$this->baseUrl}/v6.1/shipping_price", [
                        'origin' => $originDistrictId,
                        'destination' => $destinationDistrictId,
                        'weight' => $weightInGrams,
                        'courier' => $couriers,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (($json['status'] ?? false) && isset($json['results'])) {
                        $parsed = [];
                        foreach ($json['results'] as $res) {
                            $parsed[] = [
                                'courier_code' => strtolower($res['service'] ?? 'express'),
                                'courier_name' => $res['courier'] ?? 'Ekspedisi',
                                'service_code' => $res['service'] ?? 'REG',
                                'service_name' => ($res['courier'] ?? '') . ' ' . ($res['service'] ?? ''),
                                'cost' => (float) ($res['cost'] ?? 15000),
                                'formatted_cost' => 'Rp ' . number_format((float) ($res['cost'] ?? 15000), 0, ',', '.'),
                                'etd' => $res['etd'] ?? '1-3 Hari',
                            ];
                        }
                        if (!empty($parsed)) {
                            return $parsed;
                        }
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return $this->getSimulatedRates($weightInGrams, $couriers);
    }

    public function requestPickup(Order $order): array
    {
        if (!$order->isPaid()) {
            throw new \Exception('Pesanan belum lunas. Pembayaran harus diverifikasi terlebih dahulu sebelum request pickup kurir KiriminAja.');
        }

        $shipment = $order->shipment;

        if (!$shipment) {
            throw new \Exception('Data pengiriman untuk pesanan ini tidak ditemukan.');
        }

        if ($shipment->hasWaybill()) {
            return [
                'success' => true,
                'waybill_number' => $shipment->waybill_number,
                'booking_id' => $shipment->booking_id,
                'message' => 'Resi AWB sudah diterbitkan sebelumnya: ' . $shipment->waybill_number,
            ];
        }

        if (empty($this->apiKey)) {
            throw new \Exception('API Key KiriminAja belum diatur. Silakan masukkan API Key di menu Logistik & Pengiriman.');
        }

        $storeAddress = (string) StoreSetting::get('origin_address', 'Jl. Radio Dalam Raya No. 42');
        $storePhone = (string) StoreSetting::get('store_phone', '081288990011');
        $storeName = (string) StoreSetting::get('store_name', 'LUMEN Hair Color');
        $originDistrictId = (int) ($shipment->origin_district_id ?: StoreSetting::get('origin_district_id', 2105));
        $originPostalCode = (string) StoreSetting::get('origin_postal_code', '12190');

        $shippingAddress = is_array($order->shipping_address) ? $order->shipping_address : [];
        $destAddress = (string) ($shippingAddress['address_line'] ?? ($order->customer_address ?? 'Alamat Pemesan'));
        if (strlen($destAddress) < 10) {
            $destAddress = $destAddress . ', ' . ($shippingAddress['district_name'] ?? '') . ', ' . ($shippingAddress['city_name'] ?? '');
        }

        $destPhone = (string) ($order->customer_phone ?: '081288889999');
        $destDistrictId = (int) ($shipment->destination_district_id ?: ($shippingAddress['district_id'] ?? 2108));
        $destPostalCode = (string) ($shippingAddress['postal_code'] ?? '12000');
        $courierCode = strtolower((string) ($shipment->courier_code ?: 'jne'));
        $serviceType = strtoupper((string) ($shipment->service_type ?: 'REG'));
        $totalWeight = max(100, (int) ($shipment->total_weight ?: 250));
        $itemValue = max(10000, (int) round((float) $order->total_amount));
        $shippingCost = (int) round((float) ($shipment->shipping_cost ?: 0));

        $payload = [
            'address' => $storeAddress,
            'phone' => $storePhone,
            'name' => $storeName,
            'zipcode' => $originPostalCode,
            'kecamatan_id' => $originDistrictId,
            'schedule' => 'earliest',
            'packages' => [
                [
                    'order_id' => (string) $order->order_number,
                    'destination_name' => (string) ($order->customer_name ?: 'Pelanggan'),
                    'destination_phone' => $destPhone,
                    'destination_address' => $destAddress,
                    'destination_kecamatan_id' => $destDistrictId,
                    'destination_zipcode' => $destPostalCode,
                    'weight' => $totalWeight,
                    'width' => 10,
                    'length' => 15,
                    'height' => 10,
                    'item_value' => $itemValue,
                    'shipping_cost' => $shippingCost,
                    'service' => $courierCode,
                    'service_type' => $serviceType,
                    'item_name' => 'Produk Pewarna Rambut LUMEN Atelier',
                    'package_type_id' => 1,
                    'cod' => 0,
                    'drop' => false,
                    'note' => 'Pesanan LUMEN Atelier #' . $order->order_number,
                ]
            ]
        ];

        try {
            $response = $this->client()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(15)
                ->post("{$this->baseUrl}/v6.1/request_pickup", $payload);
        } catch (\Throwable $e) {
            throw new \Exception('Gagal menghubungi server API KiriminAja: ' . $e->getMessage());
        }

        $json = $response->json() ?? [];

        if (!$response->successful() || !($json['status'] ?? false)) {
            $errMsg = $json['text'] ?? $json['message'] ?? null;
            if (empty($errMsg)) {
                $errMsg = 'Permintaan request pickup ditolak oleh KiriminAja (HTTP ' . $response->status() . ').';
            }
            if (!empty($json['your_ip'])) {
                $errMsg .= ' [IP Server: ' . $json['your_ip'] . ' belum di-whitelist di KiriminAja]';
            }
            throw new \Exception($errMsg);
        }

        $pickupNumber = $json['pickup_number'] ?? ($json['data']['pickup_number'] ?? null);
        $details = $json['details'] ?? ($json['data']['details'] ?? ($json['data'] ?? []));

        $awb = null;
        if (!empty($details) && is_array($details)) {
            if (isset($details[0]['awb']) && !empty($details[0]['awb'])) {
                $awb = $details[0]['awb'];
            } elseif (isset($details['awb']) && !empty($details['awb'])) {
                $awb = $details['awb'];
            }
        }

        if (empty($awb)) {
            $awb = $pickupNumber ?? ($json['awb'] ?? ($json['data']['awb'] ?? null));
        }

        $bookingId = $pickupNumber ?? ($details[0]['booking_id'] ?? ('KA-' . $order->order_number));
        $finalAwb = $awb ?: $bookingId;

        $shipment->update([
            'booking_id' => $bookingId,
            'waybill_number' => $finalAwb,
            'status' => 'picked_up',
            'pickup_scheduled_at' => now(),
            'shipped_at' => now(),
            'kiriminaja_response' => $json,
            'tracking_history' => $this->getInitialTrackingCheckpoints($finalAwb, $shipment->courier_name),
        ]);

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return [
            'success' => true,
            'waybill_number' => $finalAwb,
            'booking_id' => $bookingId,
            'message' => 'Sukses request pickup kurir KiriminAja (Pickup No: ' . $bookingId . ', Resi AWB: ' . $finalAwb . ').',
        ];
    }

    public function getTracking(string $waybillNumber, string $courierCode, ?string $orderNumber = null): array
    {
        $shipment = Shipment::where('waybill_number', $waybillNumber)->first();

        if ($shipment && !empty($shipment->tracking_history)) {
            return $shipment->tracking_history;
        }

        if (!empty($this->apiKey) && !empty($orderNumber)) {
            try {
                $response = $this->client()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(8)
                    ->post("{$this->baseUrl}/tracking", [
                        'order_id' => $orderNumber,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (($json['status'] ?? false) && !empty($json['histories'])) {
                        $checkpoints = [];
                        foreach ($json['histories'] as $history) {
                            $checkpoints[] = [
                                'time' => $history['created_at'] ?? now()->format('d M Y, H:i') . ' WIB',
                                'status' => $history['status'] ?? 'ON_PROCESS',
                                'note' => $history['note'] ?? 'Status terkini pengiriman.',
                                'location' => $history['city'] ?? '',
                            ];
                        }
                        if ($shipment && !empty($checkpoints)) {
                            $shipment->update(['tracking_history' => $checkpoints]);
                        }
                        return $checkpoints;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return $this->getInitialTrackingCheckpoints($waybillNumber, strtoupper($courierCode));
    }

    protected function getSimulatedRates(int $weightInGrams, array $couriers): array
    {
        $weightInKg = max(1, ceil($weightInGrams / 1000));
        $rates = [];

        $courierProfiles = [
            'jnt' => [
                'name' => 'J&T Express',
                'services' => [
                    ['code' => 'EZ', 'name' => 'J&T Regular (EZ)', 'base_rate' => 19000, 'etd' => '1-2 Hari'],
                    ['code' => 'GOKIL', 'name' => 'J&T Cargo (GOKIL)', 'base_rate' => 35000, 'etd' => '3-4 Hari'],
                ]
            ],
            'sicepat' => [
                'name' => 'SiCepat',
                'services' => [
                    ['code' => 'SIUNT', 'name' => 'SiCepat SIUNTUNG', 'base_rate' => 15000, 'etd' => '1-2 Hari'],
                    ['code' => 'GOKIL', 'name' => 'SiCepat Cargo', 'base_rate' => 30000, 'etd' => '2-3 Hari'],
                ]
            ],
            'jne' => [
                'name' => 'JNE',
                'services' => [
                    ['code' => 'REG', 'name' => 'JNE Reguler', 'base_rate' => 18000, 'etd' => '2-3 Hari'],
                    ['code' => 'YES', 'name' => 'JNE Yakin Esok Sampai', 'base_rate' => 28000, 'etd' => '1 Hari'],
                ]
            ]
        ];

        foreach ($couriers as $courier) {
            if (isset($courierProfiles[$courier])) {
                $profile = $courierProfiles[$courier];
                foreach ($profile['services'] as $svc) {
                    $cost = $svc['base_rate'] * $weightInKg;
                    $rates[] = [
                        'courier_code' => $courier,
                        'courier_name' => $profile['name'],
                        'service_code' => $svc['code'],
                        'service_name' => $svc['name'],
                        'cost' => $cost,
                        'formatted_cost' => 'Rp ' . number_format($cost, 0, ',', '.'),
                        'etd' => $svc['etd'],
                    ];
                }
            }
        }

        return $rates;
    }

    public function testConnection(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key KiriminAja belum diisi.',
                'your_ip' => null,
            ];
        }

        try {
            $response = $this->client()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(8)
                ->post("{$this->baseUrl}/v2/schedules");

            $json = $response->json() ?? [];

            if ($response->successful() && ($json['status'] ?? false)) {
                return [
                    'success' => true,
                    'message' => 'Koneksi KiriminAja Berhasil! Akun dan API Key terhubung lancar.',
                    'your_ip' => $json['your_ip'] ?? null,
                ];
            }

            if ($response->status() === 403) {
                $body = $response->body();
                if (str_contains(strtolower($body), 'region limit') || str_contains(strtolower($body), 'zoraxy')) {
                    return [
                        'success' => false,
                        'message' => 'Koneksi ditolak (403 Forbidden). Firewall KiriminAja memblokir VPN / WARP karena batasan wilayah (Region Limit). Harap matikan WARP/VPN Anda.',
                        'your_ip' => null,
                    ];
                }
            }

            $blockedText = $json['text'] ?? $json['message'] ?? null;
            $detectedIp = $json['your_ip'] ?? null;

            if (!empty($detectedIp) || ($blockedText && str_contains(strtolower($blockedText), 'ip'))) {
                return [
                    'success' => false,
                    'ip_blocked' => true,
                    'your_ip' => $detectedIp,
                    'message' => $blockedText ?: 'Alamat IP server belum terdaftar di whitelist KiriminAja.',
                ];
            }

            if ($response->status() === 401) {
                return [
                    'success' => false,
                    'message' => $blockedText ?: ('API Key ditolak (401 Unauthorized). Pastikan API Key valid dan sesuai dengan mode ' . strtoupper($this->mode) . ' (Production: app.kiriminaja.com, Sandbox: tdev.kiriminaja.com).'),
                    'your_ip' => null,
                ];
            }

            return [
                'success' => false,
                'ip_blocked' => false,
                'your_ip' => null,
                'message' => $blockedText ?: ('Gagal terhubung ke API KiriminAja (Status ' . $response->status() . ').'),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghubungi server KiriminAja: ' . $e->getMessage(),
                'your_ip' => null,
            ];
        }
    }

    protected function getInitialTrackingCheckpoints(string $awb, string $courierName): array
    {
        return [
            [
                'time' => now()->subMinutes(15)->format('d M Y, H:i') . ' WIB',
                'status' => 'MANIFESTED',
                'note' => "Paket pewarna rambut telah dibooking melalui sistem KiriminAja. Menunggu penjemputan oleh kurir {$courierName}.",
                'location' => StoreSetting::get('origin_city_name', 'Jakarta Selatan'),
            ],
            [
                'time' => now()->format('d M Y, H:i') . ' WIB',
                'status' => 'PICKED UP',
                'note' => "Paket telah diserahkan dan dipickup oleh kurir {$courierName} dengan no resi {$awb}.",
                'location' => StoreSetting::get('origin_district_name', 'Kebayoran Baru'),
            ],
        ];
    }
}
