<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #f7f9fc;
            padding: 30px;
            color: #333333;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eef2f5;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #111827;
        }
        .brand-sub {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: 800;
            color: #2563eb;
        }
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 3px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .block-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .block-content {
            font-size: 13px;
            line-height: 1.5;
            color: #374151;
        }
        .table-wrap {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f9fafb;
            text-align: left;
            padding: 12px;
            font-size: 12px;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 14px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }
        .summary-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 320px;
        }
        .summary-table tr td {
            padding: 8px 12px;
            border: none;
        }
        .total-row td {
            border-top: 2px solid #111827;
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            padding-top: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-warning {
            background: #fef9c3;
            color: #a16207;
        }
        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
        }
        .print-btn-bar {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .btn-secondary {
            background: #6b7280;
            color: #fff;
            margin-left: 8px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
            }
            .print-btn-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="invoice-card">
    <div class="header">
        <div>
            <div class="brand-title">{{ $storeSetting['name'] }}</div>
            <div class="brand-sub">{{ $storeSetting['address'] }}</div>
            <div class="brand-sub">Telp/WA: {{ $storeSetting['phone'] }} | {{ $storeSetting['email'] }}</div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $order->order_number }}</div>
            <div class="brand-sub" style="margin-top: 4px;">Tanggal: {{ $order->created_at->format('d M Y, H:i') }} WIB</div>
            <div style="margin-top: 6px;">
                @if($order->isPaid())
                    <span class="badge badge-success">LUNAS</span>
                @else
                    <span class="badge badge-warning">MENUNGGU PEMBAYARAN</span>
                @endif
            </div>
        </div>
    </div>

    <div class="details-grid">
        <div>
            <div class="block-title">Ditujukan Kepada:</div>
            <div class="block-content">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_phone }}<br>
                {{ $order->customer_email }}<br>
                {{ $order->shipping_address['address_line'] ?? '' }}<br>
                {{ $order->shipping_address['district_name'] ?? '' }}, {{ $order->shipping_address['city_name'] ?? '' }}<br>
                {{ $order->shipping_address['province_name'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
            </div>
        </div>
        <div>
            <div class="block-title">Informasi Pengiriman & Pembayaran:</div>
            <div class="block-content">
                <strong>Ekspedisi:</strong> {{ $order->shipment->courier_name ?? 'Kurir' }} ({{ $order->shipment->service_type ?? 'REG' }})<br>
                <strong>No. Resi AWB:</strong> {{ $order->shipment->waybill_number ?? 'Menunggu Pickup' }}<br>
                <strong>Metode Bayar:</strong> {{ $order->latestPayment->method_name ?? 'Online Payment' }}<br>
                @if($order->latestPayment && $order->latestPayment->va_number)
                    <strong>No. VA:</strong> {{ $order->latestPayment->va_number }}<br>
                @endif
                <strong>Waktu Lunas:</strong> {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') . ' WIB' : '-' }}
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Produk Pewarna Rambut</th>
                    <th style="width: 15%; text-align: right;">Harga</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 25%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <span style="font-size: 11px; color: #6b7280;">Varian: {{ $item->variant_name }} | SKU: {{ $item->sku }}</span>
                    </td>
                    <td style="text-align: right;">{{ $item->formatted_price }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $item->formatted_total_price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>Subtotal Produk:</td>
                <td style="text-align: right; font-weight: bold;">{{ $order->formatted_subtotal }}</td>
            </tr>
            <tr>
                <td>Ongkos Kirim (KiriminAja):</td>
                <td style="text-align: right; font-weight: bold;">{{ $order->formatted_shipping_cost }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr style="color: #dc2626;">
                <td>Potongan Diskon:</td>
                <td style="text-align: right; font-weight: bold;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total Pembayaran:</td>
                <td style="text-align: right; color: #2563eb;">{{ $order->formatted_total }}</td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px solid #e5e7eb; padding-top: 15px; font-size: 11px; color: #9ca3af; text-align: center;">
        Terima kasih telah berbelanja produk pewarna rambut di {{ $storeSetting['name'] }}. Simpan invoice ini sebagai bukti transaksi resmi.
    </div>
</div>

<div class="print-btn-bar">
    <button class="btn btn-primary" onclick="window.print()">Cetak Invoice</button>
    <button class="btn btn-secondary" onclick="window.close()">Tutup Halaman</button>
</div>

</body>
</html>
