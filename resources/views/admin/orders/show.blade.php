@extends('admin.layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('breadcrumb', 'Detail Pesanan')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Invoice: {{ $order->order_number }}
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.orders.index') }}" class="text-muted text-hover-primary">Pesanan Masuk</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Rincian Transaksi</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            @if($order->isPaid())
            <a href="{{ route('admin.orders.shippingLabel', $order) }}" target="_blank" class="btn btn-sm fw-bold btn-light-primary">
                <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Cetak Label Resi Thermal
            </a>
            @else
            <button type="button" class="btn btn-sm fw-bold btn-light-danger opacity-75" disabled title="Pesanan belum lunas. Pembayaran harus diverifikasi terlebih dahulu.">
                <i class="ki-duotone ki-lock fs-5 me-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                Resi Terkunci (Belum Lunas)
            </button>
            @endif
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm fw-bold btn-light-info">
                <i class="ki-duotone ki-document fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Cetak Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm fw-bold btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-5">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-6">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning d-flex align-items-center p-5 mb-5">
            <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-6">{{ session('warning') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
            <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-6">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div class="card card-flush mb-5">
            <div class="card-body py-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="symbol symbol-40px bg-light-primary me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-duotone ki-time fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <div>
                            <span class="text-muted fs-8 d-block">Status Pesanan</span>
                            <span class="fs-5 fw-bold text-gray-900">{{ $order->status_label }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(!$order->isPaid() && $order->status !== 'cancelled')
                        <form action="{{ route('admin.orders.confirmPayment', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success fw-bold">
                                <i class="ki-duotone ki-check fs-5 me-1"></i> Verifikasi Lunas
                            </button>
                        </form>
                        @endif

                        @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan ini dan kembalikan stok produk?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light-danger fw-bold">
                                <i class="ki-duotone ki-cross fs-5 me-1"></i> Batalkan Pesanan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush mb-5">
                    <div class="card-header pt-7">
                        <h3 class="card-title fw-bold text-gray-900">Rincian Barang yang Dipesan</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                        <th class="min-w-200px">Produk Pewarna</th>
                                        <th class="min-w-100px">Harga</th>
                                        <th class="min-w-70px">Qty</th>
                                        <th class="min-w-90px">Berat</th>
                                        <th class="min-w-100px text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->variant && $item->variant->color_code)
                                                    <span class="w-20px h-20px rounded-circle me-3 border border-2 border-white shadow-sm flex-shrink-0" style="background-color: {{ $item->variant->color_code }};"></span>
                                                @endif
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-900 fw-bold fs-6">{{ $item->product_name }}</span>
                                                    <span class="text-muted fs-8">{{ $item->variant_name }} (SKU: {{ $item->sku }})</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fs-7">{{ $item->formatted_price }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light fw-bold fs-7">{{ $item->quantity }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-8">{{ $item->weight * $item->quantity }}g</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-gray-900 fw-bold fs-6">{{ $item->formatted_total_price }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="separator separator-dashed my-4"></div>

                        <div class="d-flex justify-content-end">
                            <div class="mw-300px w-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-gray-600 fs-7">Subtotal Barang:</span>
                                    <span class="text-gray-900 fw-semibold fs-7">{{ $order->formatted_subtotal }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-gray-600 fs-7">Biaya Kirim (KiriminAja):</span>
                                    <span class="text-gray-900 fw-semibold fs-7">{{ $order->formatted_shipping_cost }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-danger">
                                    <span class="fs-7">Potongan Diskon:</span>
                                    <span class="fw-semibold fs-7">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="separator separator-dashed my-2"></div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-gray-900 fw-bold fs-5">Total Pembayaran:</span>
                                    <span class="text-primary fw-bold fs-4">{{ $order->formatted_total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-5 border-start border-4 border-primary">
                    <div class="card-header pt-7">
                        <div class="card-title d-flex align-items-center">
                            <i class="ki-duotone ki-delivery-3 fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <h3 class="fw-bold text-gray-900 m-0">Logistik Pengiriman (KiriminAja)</h3>
                        </div>
                        @if($order->shipment && $order->shipment->hasWaybill())
                            <span class="badge badge-success fw-bold fs-7">Resi AWB Terbit</span>
                        @endif
                    </div>
                    <div class="card-body pt-2">
                        @if($order->shipment)
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <span class="text-muted fs-8 d-block">Ekspedisi & Layanan</span>
                                <span class="text-gray-900 fw-bold fs-6">{{ $order->shipment->courier_name }} ({{ $order->shipment->service_type }})</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted fs-8 d-block">Total Berat Paket</span>
                                <span class="text-gray-900 fw-bold fs-6">{{ $order->shipment->formatted_weight }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted fs-8 d-block">Nomor Resi (AWB)</span>
                                @if($order->shipment->hasWaybill())
                                    <span class="text-primary fw-bolder fs-5">{{ $order->shipment->waybill_number }}</span>
                                @else
                                    <span class="badge badge-light-warning fw-bold fs-8">Belum Diterbitkan</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted fs-8 d-block">Status Penjemputan</span>
                                <span class="badge badge-light-primary fw-bold fs-7">{{ $order->shipment->status_label }}</span>
                            </div>
                        </div>

                        @if(!$order->isPaid())
                        <div class="bg-light-danger border border-danger border-dashed rounded p-4 d-flex flex-column flex-md-row flex-stack gap-3">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-information fs-2hx text-danger me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div>
                                    <span class="fw-bold text-gray-900 d-block fs-7">Pengiriman Terkunci: Menunggu Pembayaran</span>
                                    <span class="text-muted fs-8">Pesanan ini belum lunas. Tombol request pickup KiriminAja dinonaktifkan sampai pembayaran terverifikasi.</span>
                                </div>
                            </div>
                            <form action="{{ route('admin.orders.confirmPayment', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success fw-bold text-nowrap">
                                    <i class="ki-duotone ki-check fs-5 me-1"></i> Verifikasi Lunas Dulu
                                </button>
                            </form>
                        </div>
                        @elseif(!$order->shipment->hasWaybill())
                        <div class="bg-light-warning border border-warning border-dashed rounded p-4 d-flex flex-stack">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-notification-bing fs-2hx text-warning me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div>
                                    <span class="fw-bold text-gray-900 d-block fs-7">Pesanan sudah siap dikirim?</span>
                                    <span class="text-muted fs-8">Klik tombol di samping untuk kirim data ke KiriminAja & generate resi kurir otomatis</span>
                                </div>
                            </div>
                            <form action="{{ route('admin.orders.requestPickup', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary fw-bold">
                                    <i class="ki-duotone ki-delivery-door fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    Request Pickup Kurir
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="bg-light-success border border-success border-dashed rounded p-4 d-flex flex-stack">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-verify fs-2hx text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                                <div>
                                    <span class="fw-bold text-gray-900 d-block fs-7">Resi Kurir Aktif: {{ $order->shipment->waybill_number }}</span>
                                    <span class="text-muted fs-8">Paket siap diserahkan ke kurir saat pickup tiba di gudang</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.orders.shippingLabel', $order) }}" target="_blank" class="btn btn-sm btn-success fw-bold">
                                <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                Cetak Label Stiker
                            </a>
                        </div>
                        @endif
                        @else
                        <div class="text-muted">Tidak ada data pengiriman terhubung.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-flush mb-5">
                    <div class="card-header pt-7">
                        <h3 class="card-title fw-bold text-gray-900">Perbarui Status Pesanan</h3>
                    </div>
                    <div class="card-body pt-2">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="fv-row mb-5">
                                <label class="fs-7 fw-semibold mb-2">Status Saat Ini</label>
                                <select name="status" class="form-select form-select-solid">
                                    <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Sudah Dibayar (Paid)</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Packaging)</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }} {{ !$order->isPaid() ? 'disabled' : '' }}>
                                        Sedang Dikirim {{ !$order->isPaid() ? '(Terkunci: Belum Lunas)' : '' }}
                                    </option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }} {{ !$order->isPaid() ? 'disabled' : '' }}>
                                        Pesanan Selesai {{ !$order->isPaid() ? '(Terkunci: Belum Lunas)' : '' }}
                                    </option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                @if(!$order->isPaid())
                                <div class="fs-8 text-danger mt-1">Status pengiriman terkunci sampai pesanan terverifikasi lunas.</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>

                <div class="card card-flush mb-5">
                    <div class="card-header pt-7">
                        <h3 class="card-title fw-bold text-gray-900">Data Pelanggan & Alamat</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-4">
                            <span class="text-muted fs-8 d-block">Nama Lengkap</span>
                            <span class="text-gray-900 fw-bold fs-7">{{ $order->customer_name }}</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-muted fs-8 d-block">Kontak WhatsApp</span>
                            <span class="text-gray-900 fw-semibold fs-7">{{ $order->customer_phone }}</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-muted fs-8 d-block">Email</span>
                            <span class="text-gray-900 fw-semibold fs-7">{{ $order->customer_email }}</span>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div>
                            <span class="text-muted fs-8 d-block">Alamat Pengiriman Tujuan</span>
                            <span class="text-gray-800 fs-7 d-block">
                                {{ $order->shipping_address['address_line'] ?? '' }}
                            </span>
                            <span class="text-gray-800 fs-7 fw-semibold d-block">
                                {{ $order->shipping_address['district_name'] ?? '' }}, {{ $order->shipping_address['city_name'] ?? '' }}
                            </span>
                            <span class="text-gray-800 fs-7 d-block">
                                {{ $order->shipping_address['province_name'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-5">
                    <div class="card-header pt-7">
                        <h3 class="card-title fw-bold text-gray-900">Pembayaran (QRIS & VA)</h3>
                    </div>
                    <div class="card-body pt-2">
                        @if($order->latestPayment)
                        <div class="mb-3">
                            <span class="text-muted fs-8 d-block">Metode Bayar</span>
                            <span class="text-gray-900 fw-bold fs-7">{{ $order->latestPayment->method_name }}</span>
                        </div>
                        @if($order->latestPayment->va_number)
                        <div class="mb-3">
                            <span class="text-muted fs-8 d-block">Nomor Virtual Account</span>
                            <span class="text-primary fw-bolder fs-6">{{ $order->latestPayment->va_number }}</span>
                        </div>
                        @endif
                        <div class="mb-3">
                            <span class="text-muted fs-8 d-block">Status Verifikasi</span>
                            @if($order->latestPayment->isSettled())
                                <span class="badge badge-success fw-bold fs-8">Settlement (Lunas)</span>
                            @elseif($order->latestPayment->isPending())
                                <span class="badge badge-warning fw-bold fs-8">Menunggu Transfer</span>
                            @else
                                <span class="badge badge-secondary fw-bold fs-8">{{ $order->latestPayment->status }}</span>
                            @endif
                        </div>
                        @if($order->latestPayment->paid_at)
                        <div>
                            <span class="text-muted fs-8 d-block">Waktu Pembayaran</span>
                            <span class="text-gray-800 fs-8">{{ $order->latestPayment->paid_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        @endif
                        @else
                        <span class="text-muted fs-8">Belum ada transaksi pembayaran.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
