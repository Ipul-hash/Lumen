@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan Masuk')
@section('breadcrumb', 'Pesanan Masuk')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Manajemen Pesanan Masuk
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Penjualan</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Daftar Transaksi</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-sm fw-bold btn-primary">
                <i class="ki-duotone ki-plus fs-5 me-1"></i>
                Buat Pesanan Manual
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

        <div class="card card-flush mb-5">
            <div class="card-header pt-5">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            Semua <span class="badge badge-light ms-1">{{ $statusCounts['all'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'pending_payment' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}">
                            Menunggu Bayar <span class="badge badge-light-warning ms-1">{{ $statusCounts['pending_payment'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'processing' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">
                            Perlu Diproses <span class="badge badge-light-primary ms-1">{{ $statusCounts['processing'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'shipped' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">
                            Sedang Dikirim <span class="badge badge-light-info ms-1">{{ $statusCounts['shipped'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'completed' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
                            Selesai <span class="badge badge-light-success ms-1">{{ $statusCounts['completed'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body py-4">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}" />
                    @endif
                    <div class="col-md-9">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 text-gray-500">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" name="search" class="form-control form-control-solid ps-12" placeholder="Cari invoice INV-..., nama pembeli, atau email..." value="{{ request('search') }}" />
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                <th class="min-w-140px">Invoice & Tanggal</th>
                                <th class="min-w-160px">Pembeli & Tujuan</th>
                                <th class="min-w-200px">Produk Pewarna</th>
                                <th class="min-w-110px">Total Bayar</th>
                                <th class="min-w-120px">Pembayaran</th>
                                <th class="min-w-130px">Pengiriman (KiriminAja)</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-120px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-900 fw-bold fs-6 text-hover-primary">
                                            {{ $order->order_number }}
                                        </a>
                                        <span class="text-muted fs-8">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-semibold fs-7">{{ $order->customer_name }}</span>
                                        <span class="text-muted fs-8">{{ $order->customer_phone }}</span>
                                        <span class="text-gray-600 fs-8">{{ $order->shipping_address['city_name'] ?? '' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @foreach($order->items as $item)
                                    <div class="d-flex flex-column mb-1">
                                        <span class="text-gray-800 fw-semibold fs-7">{{ $item->product_name }}</span>
                                        <span class="text-muted fs-8">{{ $item->variant_name }} &times; {{ $item->quantity }}</span>
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
                                            <span class="badge badge-light-primary fw-bold fs-8 mb-1">VA {{ strtoupper($order->latestPayment->bank ?? 'Bank') }}</span>
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
                                            @elseif(!$order->isPaid())
                                                <span class="badge badge-light-danger fw-bold fs-9 py-0 px-2 w-fit mt-1">Belum Lunas (Terkunci)</span>
                                            @else
                                                <span class="badge badge-light-warning fw-bold fs-9 py-0 px-2 w-fit mt-1">Siap Request Pickup</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fs-8">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status === 'paid' || $order->status === 'processing')
                                        <span class="badge badge-light-primary fw-bold fs-8">{{ $order->status_label }}</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge badge-light-info fw-bold fs-8">{{ $order->status_label }}</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge badge-light-success fw-bold fs-8">{{ $order->status_label }}</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-light-danger fw-bold fs-8">{{ $order->status_label }}</span>
                                    @else
                                        <span class="badge badge-light-warning fw-bold fs-8">{{ $order->status_label }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if($order->shipment && !$order->shipment->hasWaybill())
                                            @if($order->isPaid())
                                            <form action="{{ route('admin.orders.requestPickup', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim pesanan ini ke KiriminAja untuk request pickup kurir?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light-warning px-3 py-1 fs-8 fw-bold" title="Request Pickup Kurir">
                                                    <i class="ki-duotone ki-delivery-door fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                    Pickup
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-light-danger px-2 py-1 fs-9 fw-bold" disabled title="Pesanan belum lunas, tidak dapat dikirim">
                                                <i class="ki-duotone ki-lock fs-7 text-danger me-1"><span class="path1"></span><span class="path2"></span></i>
                                                Terkunci
                                            </button>
                                            @endif
                                        @endif
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-icon btn-light-primary">
                                            <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-6">Tidak ada pesanan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
