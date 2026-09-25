@extends('admin.layouts.app')

@section('title', 'Logistik Pengiriman KiriminAja')
@section('breadcrumb', 'Pengiriman KiriminAja')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Logistik & Ekspedisi KiriminAja
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Logistik</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Daftar Paket & Status Resi AWB</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.shipments.calculator') }}" class="btn btn-sm fw-bold btn-light-primary">
                <i class="ki-duotone ki-calculator fs-5 me-1"></i>
                Kalkulator Ongkir
            </a>
            <a href="{{ route('admin.shipments.settings') }}" class="btn btn-sm fw-bold btn-secondary">
                <i class="ki-duotone ki-setting-2 fs-5 me-1"></i>
                Pengaturan Gudang
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="card card-flush mb-5">
            <div class="card-header pt-5">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.shipments.index') }}">
                            Semua Paket <span class="badge badge-light ms-1">{{ $statusCounts['all'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'pending_pickup' ? 'active' : '' }}" href="{{ route('admin.shipments.index', ['status' => 'pending_pickup']) }}">
                            Menunggu Pickup <span class="badge badge-light-warning ms-1">{{ $statusCounts['pending_pickup'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'picked_up' ? 'active' : '' }}" href="{{ route('admin.shipments.index', ['status' => 'picked_up']) }}">
                            Dipickup Kurir <span class="badge badge-light-primary ms-1">{{ $statusCounts['picked_up'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'in_transit' ? 'active' : '' }}" href="{{ route('admin.shipments.index', ['status' => 'in_transit']) }}">
                            Dalam Pengiriman <span class="badge badge-light-info ms-1">{{ $statusCounts['in_transit'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 {{ request('status') === 'delivered' ? 'active' : '' }}" href="{{ route('admin.shipments.index', ['status' => 'delivered']) }}">
                            Terkirim <span class="badge badge-light-success ms-1">{{ $statusCounts['delivered'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body py-4">
                <form action="{{ route('admin.shipments.index') }}" method="GET" class="row g-3 align-items-center">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}" />
                    @endif
                    <div class="col-md-5">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 text-gray-500">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" name="search" class="form-control form-control-solid ps-12" placeholder="Cari Resi AWB, Booking ID, atau invoice..." value="{{ request('search') }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="courier" class="form-select form-select-solid">
                            <option value="">Semua Ekspedisi</option>
                            <option value="jnt" {{ request('courier') === 'jnt' ? 'selected' : '' }}>J&T Express</option>
                            <option value="sicepat" {{ request('courier') === 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                            <option value="jne" {{ request('courier') === 'jne' ? 'selected' : '' }}>JNE</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        @if(request()->hasAny(['search', 'courier', 'status']))
                            <a href="{{ route('admin.shipments.index') }}" class="btn btn-light">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <form action="{{ route('admin.shipments.batchPickup') }}" method="POST" id="form-batch-pickup">
            @csrf

            <div class="card card-flush">
                <div class="card-header pt-7 d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold text-gray-900 m-0">Daftar Paket KiriminAja</h3>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold" id="btn-batch-pickup" style="display: none;" data-confirm="Request pickup massal untuk paket yang dicentang?" data-confirm-title="Pickup Massal">
                        <i class="ki-duotone ki-delivery-door fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        Request Pickup Terpilih
                    </button>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                    <th class="w-30px">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" id="check-all" />
                                        </div>
                                    </th>
                                    <th class="min-w-160px">Resi AWB & Booking</th>
                                    <th class="min-w-160px">No. Invoice & Pembeli</th>
                                    <th class="min-w-130px">Ekspedisi</th>
                                    <th class="min-w-150px">Tujuan Pengiriman</th>
                                    <th class="min-w-80px">Berat</th>
                                    <th class="min-w-100px">Ongkir</th>
                                    <th class="min-w-120px">Status Pickup</th>
                                    <th class="min-w-130px text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input row-checkbox" type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}" {{ $shipment->hasWaybill() || !$shipment->order || !$shipment->order->isPaid() ? 'disabled' : '' }} />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($shipment->hasWaybill())
                                                <span class="text-primary fw-bolder fs-6">{{ $shipment->waybill_number }}</span>
                                                <span class="text-muted fs-8">Booking: {{ $shipment->booking_id }}</span>
                                            @elseif(!$shipment->order || !$shipment->order->isPaid())
                                                <span class="badge badge-light-danger fw-bold fs-8 w-fit mb-1">Menunggu Bayar</span>
                                                <span class="text-muted fs-8">Pengiriman Terkunci</span>
                                            @else
                                                <span class="badge badge-light-warning fw-bold fs-8 w-fit mb-1">Resi Belum Terbit</span>
                                                <span class="text-muted fs-8">Perlu Request Pickup</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.orders.show', $shipment->order) }}" class="text-gray-900 fw-bold fs-6 text-hover-primary">
                                                {{ $shipment->order->order_number ?? '-' }}
                                            </a>
                                            <span class="text-gray-700 fs-7">{{ $shipment->order->customer_name ?? '-' }}</span>
                                            <span class="text-muted fs-8">{{ $shipment->order->customer_phone ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-gray-800 fs-6">{{ $shipment->courier_name }}</span>
                                            <span class="badge badge-light-secondary fs-8 w-fit">{{ $shipment->service_type }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fs-7 fw-semibold">
                                                {{ $shipment->order->shipping_address['district_name'] ?? '' }}
                                            </span>
                                            <span class="text-muted fs-8">
                                                {{ $shipment->order->shipping_address['city_name'] ?? '' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fs-7 fw-bold">{{ $shipment->formatted_weight }}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-900 fs-7 fw-bold">{{ $shipment->formatted_shipping_cost }}</span>
                                    </td>
                                    <td>
                                        @if(!$shipment->order || !$shipment->order->isPaid())
                                            <span class="badge badge-light-danger fw-bold fs-8">
                                                <i class="ki-duotone ki-lock fs-8 me-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                                Belum Lunas (Terkunci)
                                            </span>
                                        @elseif($shipment->status === 'delivered')
                                            <span class="badge badge-light-success fw-bold fs-8">{{ $shipment->status_label }}</span>
                                        @elseif($shipment->status === 'in_transit' || $shipment->status === 'picked_up')
                                            <span class="badge badge-light-primary fw-bold fs-8">{{ $shipment->status_label }}</span>
                                        @else
                                            <span class="badge badge-light-warning fw-bold fs-8">{{ $shipment->status_label }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            @if($shipment->hasWaybill())
                                            <button type="button" class="btn btn-sm btn-icon btn-light-info btn-track" data-id="{{ $shipment->id }}" title="Lacak Paket">
                                                <i class="ki-duotone ki-geolocation fs-5"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                            <a href="{{ route('admin.orders.shippingLabel', $shipment->order) }}" target="_blank" class="btn btn-sm btn-icon btn-light-success" title="Cetak Label Resi">
                                                <i class="ki-duotone ki-printer fs-5"><span class="path1"></span><span class="path2"></span></i>
                                            </a>
                                            @elseif(!$shipment->order || !$shipment->order->isPaid())
                                            <a href="{{ route('admin.orders.show', $shipment->order) }}" class="btn btn-sm btn-light-danger px-3 py-1 fs-8 fw-bold" title="Pesanan belum lunas, buka untuk verifikasi pembayaran">
                                                <i class="ki-duotone ki-lock fs-7 text-danger me-1"><span class="path1"></span><span class="path2"></span></i>
                                                Terkunci
                                            </a>
                                            @else
                                            <button type="submit" form="form-pickup-{{ $shipment->id }}" class="btn btn-sm btn-warning px-3 py-1 fs-8 fw-bold" data-confirm="Request pickup ekspedisi untuk order {{ $shipment->order->order_number ?? '' }}?" data-confirm-title="Request Pickup">
                                                Pickup
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-6">Tidak ada data pengiriman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {{ $shipments->links() }}
                    </div>
                </div>
            </div>
        </form>

        @foreach($shipments as $shipment)
            @if(!$shipment->hasWaybill() && $shipment->order && $shipment->order->isPaid())
            <form id="form-pickup-{{ $shipment->id }}" action="{{ route('admin.orders.requestPickup', $shipment->order) }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endif
        @endforeach

    </div>
</div>

<div class="modal fade" id="modal-tracking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1" id="tracking-title">Lacak Paket KiriminAja</h3>
                    <span class="text-muted fs-7" id="tracking-subtitle">Memuat status...</span>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-10 pt-4 pb-8">
                <div class="bg-light-primary rounded p-4 mb-5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-8 text-muted d-block">Nomor Resi AWB:</span>
                        <span class="fs-5 fw-bolder text-primary" id="tracking-awb">-</span>
                    </div>
                    <span class="badge badge-primary fw-bold" id="tracking-courier">-</span>
                </div>

                <div class="timeline timeline-border-dashed" id="tracking-timeline-container">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox:not(:disabled)');
    const btnBatch = document.getElementById('btn-batch-pickup');

    function toggleBatchBtn() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            btnBatch.style.display = 'inline-block';
            btnBatch.innerHTML = `<i class="ki-duotone ki-delivery-door fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Request Pickup (${checkedCount} Paket)`;
        } else {
            btnBatch.style.display = 'none';
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleBatchBtn();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            toggleBatchBtn();
        });
    });

    document.querySelectorAll('.btn-track').forEach(btn => {
        btn.addEventListener('click', function () {
            const shipmentId = this.getAttribute('data-id');
            const modalEl = document.getElementById('modal-tracking');
            const modal = new bootstrap.Modal(modalEl);

            document.getElementById('tracking-awb').textContent = 'Memuat...';
            document.getElementById('tracking-timeline-container').innerHTML = '<div class="text-center py-5 text-muted">Mengambil data pelacakan dari KiriminAja...</div>';
            modal.show();

            fetch(`/admin/shipments/${shipmentId}/track`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('tracking-awb').textContent = data.shipment.waybill_number;
                        document.getElementById('tracking-courier').textContent = data.shipment.courier_name + ' (' + data.shipment.service_type + ')';
                        document.getElementById('tracking-subtitle').textContent = 'Penerima: ' + data.shipment.customer_name + ' - ' + data.shipment.destination;

                        let timelineHtml = '';
                        data.checkpoints.forEach((cp, idx) => {
                            const isLatest = idx === data.checkpoints.length - 1;
                            timelineHtml += `
                                <div class="timeline-item mb-4">
                                    <div class="timeline-line"></div>
                                    <div class="timeline-icon me-4">
                                        <i class="ki-duotone ki-circle fs-2 ${isLatest ? 'text-success' : 'text-primary'}"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fs-7 fw-bolder ${isLatest ? 'text-success' : 'text-gray-900'}">${cp.status}</span>
                                            <span class="text-muted fs-8">${cp.time}</span>
                                        </div>
                                        <p class="fs-7 text-gray-700 mb-0">${cp.note}</p>
                                        <span class="badge badge-light fs-8 mt-1">${cp.location}</span>
                                    </div>
                                </div>
                            `;
                        });
                        document.getElementById('tracking-timeline-container').innerHTML = timelineHtml;
                    }
                })
                .catch(() => {
                    document.getElementById('tracking-timeline-container').innerHTML = '<div class="text-center py-5 text-danger">Gagal mengambil riwayat pelacakan.</div>';
                });
        });
    });
});
</script>
@endpush
@endsection
