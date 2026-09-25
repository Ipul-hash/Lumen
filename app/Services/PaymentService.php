<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    protected PaymentGatewayService $gateway;

    public function __construct(PaymentGatewayService $gateway)
    {
        $this->gateway = $gateway;
    }

    public function getGateway(): PaymentGatewayService
    {
        return $this->gateway;
    }

    public function createPayment(Order $order, string $paymentType, ?string $bank = null): Payment
    {
        $existingPending = $order->payments()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPending && $existingPending->payment_type === $paymentType && $existingPending->bank === $bank && !$existingPending->isExpired()) {
            return $existingPending;
        }

        $amount = (float) $order->total_amount;
        $fee = 0;
        $vaNumber = null;
        $qrString = null;
        $qrUrl = null;
        $transactionId = 'TRX-' . strtoupper(Str::random(10));
        $expiryTime = $paymentType === 'qris' ? now()->addMinutes(30) : now()->addHours(24);
        $payloadResponse = [
            'merchant_name' => 'LUMEN Hair Color Atelier',
            'order_number' => $order->order_number,
            'created_at' => now()->toIso8601String(),
        ];

        $snapResult = $this->gateway->createSnapTransaction($order);
        if ($snapResult['success']) {
            $payloadResponse['snap_token'] = $snapResult['snap_token'];
            $payloadResponse['snap_redirect_url'] = $snapResult['redirect_url'];
            $payloadResponse['midtrans_order_id'] = $snapResult['midtrans_order_id'];
        }

        if ($paymentType === 'qris') {
            $fee = round($amount * 0.007);
            $qrisResult = $this->gateway->chargeQris($order);
            if ($qrisResult['success']) {
                $transactionId = $qrisResult['transaction_id'] ?? $transactionId;
                $qrString = $qrisResult['qr_string'] ?? null;
                $qrUrl = $qrisResult['qr_url'] ?? null;
                $payloadResponse['core_api'] = $qrisResult['payload'] ?? null;
                $payloadResponse['qr_url'] = $qrUrl;
            } else {
                $qrString = '00020101021226600016ID.CO.LUMENHAIR.WWW011893600911000' . $order->id . '52045999530336054' . str_pad((string)(int)$amount, 10, '0', STR_PAD_LEFT) . '5802ID5916LUMEN HAIR STORE6013JAKARTA SELATAN6304' . strtoupper(substr(md5($transactionId), 0, 4));
            }
        } elseif ($paymentType === 'virtual_account') {
            $fee = 4000;
            $bank = strtolower($bank ?? 'bca');
            $vaResult = $this->gateway->chargeBankTransfer($order, $bank);
            if ($vaResult['success']) {
                $transactionId = $vaResult['transaction_id'] ?? $transactionId;
                $vaNumber = $vaResult['va_number'] ?? null;
                $payloadResponse['core_api'] = $vaResult['payload'] ?? null;
                $payloadResponse['biller_code'] = $vaResult['biller_code'] ?? null;
                $payloadResponse['bill_key'] = $vaResult['bill_key'] ?? null;
            } else {
                $vaPrefix = match ($bank) {
                    'bca' => '88001',
                    'mandiri' => '89901',
                    'bni' => '98801',
                    'bri' => '12801',
                    default => '88001',
                };
                $vaNumber = $vaPrefix . str_pad((string)$order->id, 8, '0', STR_PAD_LEFT);
            }
        }

        return Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'midtrans',
            'transaction_id' => $transactionId,
            'payment_type' => $paymentType,
            'bank' => $bank ? strtoupper($bank) : null,
            'va_number' => $vaNumber,
            'qr_string' => $qrString,
            'amount' => $amount + $fee,
            'fee' => $fee,
            'status' => 'pending',
            'expiry_time' => $expiryTime,
            'payload_response' => $payloadResponse,
        ]);
    }

    public function settlePayment(Payment $payment, array $webhookPayload = []): bool
    {
        if ($payment->isSettled()) {
            return true;
        }

        $payment->update([
            'status' => 'settlement',
            'paid_at' => now(),
            'webhook_payload' => !empty($webhookPayload) ? $webhookPayload : [
                'settled_via' => 'simulated_or_admin',
                'settled_at' => now()->toIso8601String(),
            ],
        ]);

        $order = $payment->order;
        if ($order) {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            if ($order->shipment && $order->shipment->status === 'cancelled') {
                $order->shipment->update([
                    'status' => 'pending_pickup',
                ]);
            }
        }

        return true;
    }

    public function handleWebhook(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$this->gateway->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            return [
                'success' => false,
                'message' => 'Invalid signature key',
            ];
        }

        $payment = Payment::where('transaction_id', $orderId)
            ->orWhere('payload_response->midtrans_order_id', $orderId)
            ->latest()
            ->first();

        $order = null;
        if ($payment) {
            $order = $payment->order;
        }

        if (!$order) {
            $actualOrderNumber = $orderId;
            if (preg_match('/^(INV-\d{8}-[A-Za-z0-9]+)/', $orderId, $matches)) {
                $actualOrderNumber = $matches[1];
            }
            $order = Order::where('order_number', $actualOrderNumber)->first();
            if ($order && !$payment) {
                $payment = $order->latestPayment;
            }
        }

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Order not found',
            ];
        }

        if (!$payment) {
            return [
                'success' => false,
                'message' => 'Payment record not found',
            ];
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->settlePayment($payment, $payload);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->settlePayment($payment, $payload);
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            if ($order->status === 'pending_payment') {
                $order->update(['status' => 'cancelled']);
            }
        }

        return [
            'success' => true,
            'status' => $transactionStatus,
            'order_id' => $order->order_number,
        ];
    }

    public function getBankInstructions(string $bank, string $vaNumber): array
    {
        $bank = strtoupper($bank);

        return [
            'atm' => [
                "Masukkan Kartu ATM dan PIN Anda di mesin ATM {$bank}.",
                "Pilih menu 'Transaksi Lainnya' > 'Transfer' > 'Ke Rekening Virtual Account {$bank}'.",
                "Masukkan Nomor Virtual Account: <strong>{$vaNumber}</strong> lalu tekan Benar.",
                "Periksa konfirmasi pembayaran atas nama <strong>LUMEN HAIR STORE</strong>.",
                "Jika tagihan dan nama sudah sesuai, pilih 'Ya' untuk menyelesaikan transaksi.",
                "Simpan struk transaksi sebagai bukti pembayaran yang sah.",
            ],
            'mbanking' => [
                "Buka aplikasi Mobile Banking {$bank} di ponsel Anda dan lakukan Login.",
                "Pilih menu 'm-Transfer' atau 'Pembayaran' > 'Virtual Account'.",
                "Masukkan nomor Virtual Account: <strong>{$vaNumber}</strong>.",
                "Layar akan menampilkan rincian pesanan dan total pembayaran.",
                "Masukkan PIN / Password Transaksi Anda untuk konfirmasi.",
                "Transaksi selesai! Sistem otomatis memverifikasi pembayaran dalam beberapa detik.",
            ],
            'ibanking' => [
                "Login ke akun Internet Banking {$bank} melalui browser.",
                "Pilih menu 'Transfer Dana' atau 'Pembayaran Tagihan' > 'Virtual Account'.",
                "Pilih rekening sumber dana dan masukkan kode VA <strong>{$vaNumber}</strong>.",
                "Verifikasi rincian pembayaran di layar monitor Anda.",
                "Gunakan Token / App Authenticator untuk otorisasi transaksi.",
                "Cetak atau simpan bukti konfirmasi transfer.",
            ],
        ];
    }
}
