<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Label Pengiriman - {{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #f0f0f0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .shipping-label {
            width: 100mm;
            min-height: 150mm;
            background: #ffffff;
            border: 2px solid #000000;
            padding: 10px;
            position: relative;
        }
        .header-section {
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .courier-badge {
            font-size: 22px;
            font-weight: 900;
            border: 2px solid #000000;
            padding: 4px 10px;
            text-transform: uppercase;
        }
        .service-badge {
            font-size: 14px;
            font-weight: bold;
            display: block;
            text-align: right;
            margin-top: 2px;
        }
        .barcode-section {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px dashed #000000;
        }
        .barcode-bars {
            height: 48px;
            width: 90%;
            margin: 0 auto;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                #fff 2px,
                #fff 4px,
                #000 4px,
                #000 7px,
                #fff 7px,
                #fff 9px
            );
        }
        .waybill-code {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 4px;
        }
        .invoice-code {
            font-size: 11px;
            color: #444;
        }
        .address-section {
            display: flex;
            border-bottom: 2px solid #000000;
            min-height: 120px;
        }
        .receiver-col {
            flex: 1.2;
            padding: 8px 6px;
            border-right: 1px solid #000000;
        }
        .sender-col {
            flex: 0.8;
            padding: 8px 6px;
            font-size: 11px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 4px;
        }
        .receiver-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .receiver-phone {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .receiver-address {
            font-size: 12px;
            line-height: 1.3;
        }
        .destination-highlight {
            margin-top: 6px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            background: #eee;
            padding: 2px 4px;
            display: inline-block;
        }
        .meta-row {
            display: flex;
            border-bottom: 1px solid #000000;
            padding: 6px 0;
            font-size: 11px;
        }
        .meta-col {
            flex: 1;
            padding: 0 6px;
        }
        .meta-col:first-child {
            border-right: 1px solid #000000;
        }
        .items-section {
            padding-top: 8px;
            font-size: 11px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .items-table th, .items-table td {
            text-align: left;
            padding: 3px 2px;
            font-size: 10px;
        }
        .items-table th {
            border-bottom: 1px solid #000;
        }
        .footer-note {
            font-size: 9px;
            text-align: center;
            margin-top: 10px;
            border-top: 1px dotted #888;
            padding-top: 4px;
        }
        .print-btn-bar {
            position: fixed;
            bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .print-btn {
            background: #000000;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .print-btn-bar {
                display: none;
            }
            .shipping-label {
                border: 2px solid #000000;
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>
<body>

<div class="shipping-label">
    <div class="header-section">
        <div>
            <div class="courier-badge">{{ $order->shipment->courier_name ?? 'EXPEDITION' }}</div>
            <span class="service-badge">{{ $order->shipment->service_type ?? 'STANDARD' }}</span>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: 900; font-size: 14px;">KIRIMINAJA</div>
            <div style="font-size: 10px; color: #555;">Drop Off / Pickup</div>
        </div>
    </div>

    <div class="barcode-section">
        <div class="barcode-bars"></div>
        <div class="waybill-code">{{ $order->shipment->waybill_number ?? 'AWB-PENDING' }}</div>
        <div class="invoice-code">Order: {{ $order->order_number }} | Tgl: {{ $order->created_at->format('d/m/Y') }}</div>
    </div>

    <div class="address-section">
        <div class="receiver-col">
            <div class="section-title">Penerima:</div>
            <div class="receiver-name">{{ $order->customer_name }}</div>
            <div class="receiver-phone">{{ $order->customer_phone }}</div>
            <div class="receiver-address">
                {{ $order->shipping_address['address_line'] ?? '' }}
            </div>
            <div class="destination-highlight">
                {{ $order->shipping_address['district_name'] ?? '' }}, {{ $order->shipping_address['city_name'] ?? '' }}
            </div>
            <div style="font-size: 12px; margin-top: 2px; font-weight: bold;">
                {{ $order->shipping_address['province_name'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
            </div>
        </div>

        <div class="sender-col">
            <div class="section-title">Pengirim:</div>
            <div style="font-weight: bold; font-size: 12px;">{{ $storeSetting['name'] }}</div>
            <div style="margin-bottom: 2px;">{{ $storeSetting['phone'] }}</div>
            <div style="line-height: 1.2; color: #333;">
                {{ $storeSetting['address'] }}
            </div>
            <div style="margin-top: 4px; font-weight: bold;">
                {{ $storeSetting['city'] }} {{ $storeSetting['postal_code'] }}
            </div>
        </div>
    </div>

    <div class="meta-row">
        <div class="meta-col">
            <span>Berat: </span><strong>{{ $order->shipment->formatted_weight ?? '500g' }}</strong>
        </div>
        <div class="meta-col">
            <span>Metode: </span><strong>NON-COD (Lunas)</strong>
        </div>
    </div>

    <div class="items-section">
        <div class="section-title">Isi Paket Cat Rambut:</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Produk & Varian</th>
                    <th style="width: 15%; text-align: center;">Qty</th>
                    <th style="width: 15%; text-align: right;">Cek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }} - {{ $item->variant_name }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->quantity }}x</td>
                    <td style="text-align: right;">[ &nbsp; ]</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer-note">
        Paket Pewarna Rambut Fragile - Harap Jangan Ditumpuk Beban Berat
    </div>
</div>

<div class="print-btn-bar">
    <button class="print-btn" onclick="window.print()">Cetak Label Resi</button>
    <button class="print-btn" style="background:#555;" onclick="window.close()">Tutup</button>
</div>

</body>
</html>
