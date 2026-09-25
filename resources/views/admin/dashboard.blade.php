@extends('admin.layouts.app')

@section('title', 'Dashboard eCommerce')
@section('breadcrumb', 'Overview Penjualan & Pengiriman')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Dashboard Toko Online Pewarna Rambut
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">LUMEN Hair Color Store</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Integrasi KiriminAja & Payment Gateway</li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <span class="badge badge-light-primary fw-bold px-3 py-2 fs-7">
                <i class="ki-duotone ki-calendar fs-6 text-primary me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                {{ date('d M Y') }}
            </span>
            <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_check_rates">
                <i class="ki-duotone ki-delivery-3 fs-5 me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                Cek Ongkir KiriminAja
            </button>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-6 col-xl-3">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                Rp {{ number_format($totalEarnings, 0, ',', '.') }}
                            </span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Omzet Penjualan</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column justify-content-end">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-light-success fs-base me-2">
                                <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Lunas
                            </span>
                            <span class="text-gray-600 fs-7">Bulan ini: Rp {{ number_format($thisMonthEarnings, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress h-6px w-100 bg-light-success">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ $totalOrdersCount }}</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pesanan Masuk</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column justify-content-end">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-light-primary fs-base me-2">{{ $ordersThisMonth }} Baru</span>
                            <span class="text-gray-600 fs-7">Tercatat di sistem</span>
                        </div>
                        <div class="progress h-6px w-100 bg-light-primary">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card card-flush h-md-100 border-start border-warning border-4">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-warning me-2 lh-1 ls-n2">{{ $pendingPickupCount }}</span>
                                <span class="badge badge-light-warning fw-bold fs-7">Perlu Dipickup</span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">KiriminAja Pickup Kurir</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column justify-content-end">
                        <div class="d-flex justify-content-between align-items-center fs-7 text-gray-600 mb-1">
                            <span>Dalam Pengiriman: <strong>{{ $inTransitCount }}</strong></span>
                            <span>Sampai: <strong>{{ $deliveredCount }}</strong></span>
                        </div>
                        <span class="text-muted fs-8">Pesanan lunas siap di-booking kurir otomatis</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-5 fw-bold text-gray-900">Metode Pembayaran</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-7">QRIS & Virtual Account</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-7 fw-semibold text-gray-700">
                                <span class="bullet bullet-dot bg-success me-1"></span> QRIS
                            </span>
                            <span class="fs-7 fw-bold text-gray-900">Rp {{ number_format($qrisSettledAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-7 fw-semibold text-gray-700">
                                <span class="bullet bullet-dot bg-primary me-1"></span> Virtual Account
                            </span>
                            <span class="fs-7 fw-bold text-gray-900">Rp {{ number_format($vaSettledAmount, 0, ',', '.') }}</span>
                        </div>
                        @if($pendingPaymentsCount > 0)
                        <div class="d-flex align-items-center justify-content-between text-warning fs-8">
                            <span><i class="ki-duotone ki-time fs-8 text-warning me-1"><span class="path1"></span><span class="path2"></span></i> Menunggu bayar:</span>
                            <span class="fw-bold">{{ $pendingPaymentsCount }} pesanan</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light-primary border border-primary border-dashed mb-5 mb-xl-10">
            <div class="card-body py-5 px-6 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <div class="symbol symbol-50px bg-primary text-white me-4 d-flex align-items-center justify-content-center">
                        <i class="ki-duotone ki-delivery fs-2 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </div>
                    <div>
                        <h4 class="text-gray-900 fw-bold mb-1">Integrasi Pengiriman KiriminAja Siap Digunakan</h4>
                        <p class="text-gray-600 fs-7 mb-0">
                            Titik Penjemputan Gudang: <strong>{{ \App\Models\StoreSetting::get('origin_address') }}</strong> 
                            ({{ \App\Models\StoreSetting::get('origin_district_name') }}, {{ \App\Models\StoreSetting::get('origin_city_name') }} - ID: {{ \App\Models\StoreSetting::get('origin_district_id') }})
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge badge-light-success fw-bold px-3 py-2 fs-7 align-self-center">
                        <i class="ki-duotone ki-verify fs-6 text-success me-1"><span class="path1"></span><span class="path2"></span></i>
                        Auto AWB Resi
                    </span>
                    <span class="badge badge-light-info fw-bold px-3 py-2 fs-7 align-self-center">
                        <i class="ki-duotone ki-delivery-door fs-6 text-info me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        Pickup Otomatis
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Pesanan Masuk Terbaru</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Transaksi terhubung ke Payment Gateway & KiriminAja</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light-primary fw-bold">Lihat Semua Pesanan</a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                        <th class="min-w-140px">No. Invoice & Pembeli</th>
                                        <th class="min-w-160px">Produk Pesanan</th>
                                        <th class="min-w-100px">Total Bayar</th>
                                        <th class="min-w-120px">Pembayaran</th>
                                        <th class="min-w-120px">Ekspedisi (Resi)</th>
                                        <th class="min-w-90px text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-900 fw-bold fs-6 text-hover-primary">{{ $order->order_number }}</a>
                                                <span class="text-gray-600 fs-7">{{ $order->customer_name }}</span>
                                                <span class="text-muted fs-8">{{ $order->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($order->items as $item)
                                            <div class="d-flex flex-column mb-1">
                                                <span class="text-gray-800 fw-semibold fs-7">{{ $item->product_name }}</span>
                                                <span class="text-muted fs-8">{{ $item->variant_name }} ({{ $item->quantity }}x)</span>
                                            </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="text-gray-900 fw-bold fs-6">{{ $order->formatted_total }}</span>
                                            <div class="text-muted fs-8">Ongkir: {{ $order->formatted_shipping_cost }}</div>
                                        </td>
                                        <td>
                                            @if($order->latestPayment)
                                                @if($order->latestPayment->payment_type === 'qris')
                                                    <span class="badge badge-light-success fw-bold fs-8 mb-1">QRIS</span>
                                                @else
                                                    <span class="badge badge-light-primary fw-bold fs-8 mb-1">
                                                        VA {{ strtoupper($order->latestPayment->bank ?? 'Bank') }}
                                                    </span>
                                                @endif
                                                <br>
                                                @if($order->latestPayment->isSettled())
                                                    <span class="badge badge-success fs-9 py-0 px-2">Lunas</span>
                                                @elseif($order->latestPayment->isPending())
                                                    <span class="badge badge-warning fs-9 py-0 px-2">Menunggu</span>
                                                @else
                                                    <span class="badge badge-secondary fs-9 py-0 px-2">{{ $order->latestPayment->status }}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-light-secondary fs-8">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->shipment)
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-gray-800 fs-7">
                                                        {{ $order->shipment->courier_name }} ({{ $order->shipment->service_type }})
                                                    </span>
                                                    @if($order->shipment->hasWaybill())
                                                        <span class="text-primary fw-bold fs-8">
                                                            {{ $order->shipment->waybill_number }}
                                                        </span>
                                                        <span class="badge badge-light-success fs-9 py-0 px-2 mt-1">
                                                            {{ $order->shipment->status_label }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-light-warning fs-9 py-0 px-2 mt-1">
                                                            {{ $order->shipment->status_label }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted fs-8">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($order->shipment && !$order->shipment->hasWaybill() && $order->isPaid())
                                            <form action="{{ route('admin.orders.requestPickup', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-icon btn-light-warning" title="Request Pickup KiriminAja" data-confirm="Request pickup ekspedisi untuk order {{ $order->order_number }}?">
                                                    <i class="ki-duotone ki-delivery-door fs-5 text-warning">
                                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                                    </i>
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-icon btn-light-primary" title="Lihat Detail Pesanan">
                                                <i class="ki-duotone ki-eye fs-5 text-primary">
                                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                                </i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-6">Belum ada pesanan masuk.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Peringatan Stok Varian</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Varian cat rambut yang hampir habis</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @forelse($lowStockVariants as $variant)
                        <div class="d-flex align-items-center mb-5 pb-3 border-bottom border-gray-200">
                            <div class="w-25px h-25px rounded-circle me-3 border border-2 border-white shadow-sm flex-shrink-0" style="background-color: {{ $variant->color_code ?? '#222' }};"></div>
                            
                            <div class="d-flex flex-column flex-grow-1 me-2">
                                <span class="text-gray-800 fw-bold fs-7">{{ $variant->product->name ?? 'Produk' }}</span>
                                <span class="text-muted fs-8">{{ $variant->name }} (SKU: {{ $variant->sku }})</span>
                            </div>

                            <div class="text-end">
                                <span class="badge {{ $variant->stock <= 5 ? 'badge-light-danger text-danger' : 'badge-light-warning text-warning' }} fw-bold fs-7">
                                    Sisa {{ $variant->stock }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-6">Semua stok aman!</div>
                        @endforelse

                        <div class="mt-4 p-4 bg-light rounded text-center">
                            <span class="fs-7 text-gray-600">Total Katalog: <strong>{{ $totalProducts }} Produk</strong> | <strong>{{ $totalVariants }} Varian</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="kt_modal_check_rates" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="fw-bold">Cek Tarif Pengiriman (KiriminAja)</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <div class="alert alert-primary d-flex align-items-center p-5 mb-5">
                    <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Asal Pengiriman Gudang:</span>
                        <span class="fs-7">{{ \App\Models\StoreSetting::get('origin_address') }}</span>
                    </div>
                </div>
                <div class="fv-row mb-5">
                    <label class="fs-6 fw-semibold form-label mb-2">Kota / Kecamatan Tujuan</label>
                    <input type="text" id="demo_dest" class="form-control form-control-solid" value="Cilandak, Jakarta Selatan" />
                </div>
                <div class="fv-row mb-5">
                    <label class="fs-6 fw-semibold form-label mb-2">Total Berat Barang (Gram)</label>
                    <input type="number" id="demo_weight" class="form-control form-control-solid" value="550" />
                    <span class="text-muted fs-8">Contoh berat 2 botol pewarna rambut + packaging</span>
                </div>
                <div class="text-center pt-5">
                    <button type="button" class="btn btn-primary" id="btn-calc-rates">
                        Hitung Tarif Ongkir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnCalc = document.getElementById('btn-calc-rates');
    if (btnCalc) {
        btnCalc.addEventListener('click', function () {
            const dest = document.getElementById('demo_dest').value;
            const weight = document.getElementById('demo_weight').value;
            
            Swal.fire({
                title: 'Hasil Cek Tarif KiriminAja',
                html: `
                    <div class="text-start p-3 bg-light rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>J&T Express (EZ)</strong><br><small class="text-muted">Estimasi 1-2 hari</small></span>
                            <span class="text-primary fw-bold">Rp 19.000</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>SiCepat (REG)</strong><br><small class="text-muted">Estimasi 1-2 hari</small></span>
                            <span class="text-primary fw-bold">Rp 15.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>JNE (REG)</strong><br><small class="text-muted">Estimasi 2-3 hari</small></span>
                            <span class="text-primary fw-bold">Rp 18.000</span>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary fw-bold px-6',
                    popup: 'rounded-4'
                }
            });
        });
    }
});
</script>
@endpush
@endsection
