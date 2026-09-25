@extends('shop.layouts.app')

@section('title', 'Pesanan Berhasil - ' . $order->order_number . ' | LUMEN Hair Color')

@section('content')
<div class="container px-lg-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="symbol symbol-70px mb-3 d-inline-block">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 text-uppercase fs-8 fw-bold mb-2">
                    Transaksi Sukses
                </span>
                <h2 class="display-6 font-serif fw-bold text-dark mb-2">Terima Kasih Atas Pesanan Anda!</h2>
                <p class="text-muted fs-7 mb-0">Nomor Pesanan: <strong class="text-dark">{{ $order->order_number }}</strong></p>
            </div>

            <div class="card border rounded-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fs-7 text-uppercase fw-bold text-dark">Informasi Pengiriman KiriminAja</span>
                    <span class="badge bg-light text-dark border fs-8">Status: {{ $order->status_label }}</span>
                </div>
                <div class="card-body p-4">
                    @if($order->shipment)
                    <div class="p-3 bg-light border mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <span class="fs-8 text-muted d-block text-uppercase fw-bold">Ekspedisi Pilihan</span>
                                <h6 class="fw-bold text-dark m-0">{{ $order->shipment->courier_name }} ({{ $order->shipment->service_type }})</h6>
                            </div>
                            <div class="col-md-5">
                                <span class="fs-8 text-muted d-block text-uppercase fw-bold">Nomor Resi (AWB)</span>
                                @if($order->shipment->waybill_number)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bolder fs-6 text-primary">{{ $order->shipment->waybill_number }}</span>
                                        <button class="btn btn-sm btn-link p-0 text-muted" type="button" onclick="navigator.clipboard.writeText('{{ $order->shipment->waybill_number }}'); window.showToast('success', 'Nomor resi disalin!');">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="badge bg-warning text-dark fs-8">Sedang Diterbitkan Sistem</span>
                                @endif
                            </div>
                            <div class="col-md-3 text-md-end">
                                <a href="{{ route('shop.track', ['q' => $order->order_number]) }}" class="btn btn-sm btn-dark rounded-0 px-3">
                                    <i class="bi bi-geo-alt me-1"></i> Lacak Paket
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <h6 class="fs-8 text-uppercase fw-bold text-muted mb-2">Tujuan Pengiriman:</h6>
                            <div class="fs-7 text-dark fw-semibold">{{ $order->customer_name }}</div>
                            <div class="fs-8 text-muted">{{ $order->customer_phone }}</div>
                            <div class="fs-8 text-muted mt-1">{{ $order->shipping_address['address_line'] ?? '' }}</div>
                            <div class="fs-8 text-muted">{{ $order->shipping_address['district_name'] ?? '' }}, {{ $order->shipping_address['city_name'] ?? '' }}, {{ $order->shipping_address['province_name'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fs-8 text-uppercase fw-bold text-muted mb-2">Rincian Pembayaran:</h6>
                            <div class="fs-7 text-dark fw-semibold">{{ $order->latestPayment ? $order->latestPayment->method_name : 'Pembayaran Online' }}</div>
                            <div class="fs-8 text-success fw-bold mt-1">Status: LUNAS TERVERIFIKASI</div>
                            <div class="fs-8 text-muted mt-1">Waktu: {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }} WIB</div>
                        </div>
                    </div>

                    <div class="border-top pt-4">
                        <h6 class="fs-8 text-uppercase fw-bold text-dark mb-3">Produk yang Dipesan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle fs-7 mb-0">
                                <thead>
                                    <tr class="text-muted border-bottom fs-8 text-uppercase">
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                            <div class="fs-8 text-muted">{{ $item->variant_name }}</div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-semibold">{{ $item->formatted_subtotal }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-muted pt-3">Subtotal Produk:</td>
                                        <td class="text-end fw-semibold pt-3">{{ $order->formatted_subtotal }}</td>
                                    </tr>
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="2" class="text-success">Diskon Voucher:</td>
                                        <td class="text-end text-success fw-semibold">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" class="text-muted">Ongkos Kirim:</td>
                                        <td class="text-end fw-semibold">{{ $order->formatted_shipping_cost }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td colspan="2" class="fw-bold fs-6 pt-2">Total Tagihan:</td>
                                        <td class="text-end fw-bolder fs-6 pt-2">{{ $order->formatted_total }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-4 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-0">
                        <i class="bi bi-printer me-1"></i> Cetak Invoice
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-dark btn-sm">
                        Lanjut Belanja di LUMEN
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
