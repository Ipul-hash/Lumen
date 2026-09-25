<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{
    protected string $merchantId;
    protected string $clientKey;
    protected string $serverKey;
    protected bool $isProduction;
    protected string $snapApiUrl;
    protected string $snapJsUrl;
    protected string $coreApiUrl;

    public function __construct()
    {
        $this->merchantId = (string) StoreSetting::get('midtrans_merchant_id', config('services.midtrans.merchant_id', ''));
        $this->clientKey = (string) StoreSetting::get('midtrans_client_key', config('services.midtrans.client_key', ''));
        $this->serverKey = (string) StoreSetting::get('midtrans_server_key', config('services.midtrans.server_key', ''));
        
        $isProdSetting = StoreSetting::get('midtrans_is_production', config('services.midtrans.is_production', false));
        $this->isProduction = filter_var($isProdSetting, FILTER_VALIDATE_BOOLEAN);

        $this->snapApiUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $this->snapJsUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';

        $this->coreApiUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    public function getSnapJsUrl(): string
    {
        return $this->snapJsUrl;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    public function createSnapTransaction(Order $order, ?string $customOrderId = null): array
    {
        $midtransOrderId = $customOrderId ?? ($order->order_number . '-' . time());
        $grossAmount = (int) round((float) $order->total_amount);

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) $item->product_variant_id,
                'price' => (int) round((float) $item->price),
                'quantity' => (int) $item->quantity,
                'name' => mb_strimwidth($item->product_name . ' (' . $item->variant_name . ')', 0, 45, '...'),
            ];
        }

        if ((float) $order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round((float) $order->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . ($order->shipment ? $order->shipment->courier_name : 'Ekspedisi') . ')',
            ];
        }

        if ((float) $order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round((float) $order->discount_amount),
                'quantity' => 1,
                'name' => 'Diskon Promo',
            ];
        }

        $itemsTotal = array_reduce($itemDetails, function ($carry, $it) {
            return $carry + ($it['price'] * $it['quantity']);
        }, 0);

        if ($itemsTotal !== $grossAmount) {
            $itemDetails = [
                [
                    'id' => $order->order_number,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Pesanan ' . $order->order_number,
                ]
            ];
        }

        $address = $order->shipping_address ?? [];

        $payload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'address' => $address['address_line'] ?? 'Indonesia',
                    'city' => $address['city_name'] ?? 'Jakarta',
                    'postal_code' => $address['postal_code'] ?? '10000',
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'address' => $address['address_line'] ?? 'Indonesia',
                    'city' => $address['city_name'] ?? 'Jakarta',
                    'postal_code' => $address['postal_code'] ?? '10000',
                    'country_code' => 'IDN',
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.success', $order->order_number),
            ],
        ];

        $response = Http::withoutVerifying()
            ->withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post($this->snapApiUrl, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'snap_token' => $data['token'] ?? null,
                'redirect_url' => $data['redirect_url'] ?? null,
                'midtrans_order_id' => $midtransOrderId,
                'payload' => $data,
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function chargeQris(Order $order, ?string $customOrderId = null): array
    {
        $midtransOrderId = $customOrderId ?? ($order->order_number . '-QRIS-' . time());
        $grossAmount = (int) round((float) $order->total_amount);

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
        ];

        $response = Http::withoutVerifying()
            ->withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post("{$this->coreApiUrl}/charge", $payload);

        if ($response->successful()) {
            $data = $response->json();
            $qrString = $data['qr_string'] ?? null;
            $qrUrl = null;

            if (isset($data['actions']) && is_array($data['actions'])) {
                foreach ($data['actions'] as $action) {
                    if (($action['name'] ?? '') === 'generate-qr-code') {
                        $qrUrl = $action['url'] ?? null;
                    }
                }
            }

            return [
                'success' => true,
                'transaction_id' => $data['transaction_id'] ?? null,
                'midtrans_order_id' => $midtransOrderId,
                'qr_string' => $qrString,
                'qr_url' => $qrUrl,
                'expiry_time' => $data['expiry_time'] ?? null,
                'payload' => $data,
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function chargeBankTransfer(Order $order, string $bank, ?string $customOrderId = null): array
    {
        $bank = strtolower($bank);
        $midtransOrderId = $customOrderId ?? ($order->order_number . '-VA-' . strtoupper($bank) . '-' . time());
        $grossAmount = (int) round((float) $order->total_amount);

        $payload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
        ];

        if ($bank === 'mandiri') {
            $payload['payment_type'] = 'echannel';
            $payload['echannel'] = [
                'bill_info1' => 'Pembayaran Pesanan',
                'bill_info2' => $order->order_number,
            ];
        } elseif ($bank === 'permata') {
            $payload['payment_type'] = 'permata';
        } else {
            $payload['payment_type'] = 'bank_transfer';
            $payload['bank_transfer'] = [
                'bank' => $bank,
            ];
        }

        $response = Http::withoutVerifying()
            ->withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post("{$this->coreApiUrl}/charge", $payload);

        if ($response->successful()) {
            $data = $response->json();
            $vaNumber = null;

            if (isset($data['va_numbers'][0]['va_number'])) {
                $vaNumber = $data['va_numbers'][0]['va_number'];
            } elseif (isset($data['permata_va_number'])) {
                $vaNumber = $data['permata_va_number'];
            } elseif (isset($data['bill_key'])) {
                $vaNumber = $data['biller_code'] . ' - ' . $data['bill_key'];
            }

            return [
                'success' => true,
                'transaction_id' => $data['transaction_id'] ?? null,
                'midtrans_order_id' => $midtransOrderId,
                'bank' => strtoupper($bank),
                'va_number' => $vaNumber,
                'biller_code' => $data['biller_code'] ?? null,
                'bill_key' => $data['bill_key'] ?? null,
                'expiry_time' => $data['expiry_time'] ?? null,
                'payload' => $data,
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($expected, $signatureKey);
    }

    public function checkStatus(string $orderIdOrTransactionId): ?array
    {
        $response = Http::withoutVerifying()
            ->withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->get("{$this->coreApiUrl}/{$orderIdOrTransactionId}/status");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function cancelTransaction(string $orderIdOrTransactionId): bool
    {
        $response = Http::withoutVerifying()
            ->withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post("{$this->coreApiUrl}/{$orderIdOrTransactionId}/cancel");

        return $response->successful();
    }
}
