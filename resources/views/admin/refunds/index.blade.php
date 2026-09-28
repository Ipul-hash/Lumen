@extends('admin.layouts.app')

@section('title', 'Daftar Pengajuan Pengembalian Dana')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Pengajuan Pengembalian Dana
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Transaksi & Penjualan</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-900">Pengembalian Dana</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-5 mb-5 rounded-3">
                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">Berhasil</h4>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning d-flex align-items-center p-5 mb-5 rounded-3">
                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">Perhatian</h4>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center p-5 mb-5 rounded-3">
                <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">Terjadi Kesalahan</h4>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-flush h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between p-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-light-warning badge-circle w-35px h-35px me-3">
                                    <i class="ki-duotone ki-timer text-warning fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                                <span class="text-gray-500 fw-bold fs-7 text-uppercase">Menunggu Review</span>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $stats['pending'] }}</span>
                                <span class="fs-7 text-muted">tiket refund</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-flush h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between p-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-light-primary badge-circle w-35px h-35px me-3">
                                    <i class="ki-duotone ki-check text-primary fs-3"></i>
                                </span>
                                <span class="text-gray-500 fw-bold fs-7 text-uppercase">Disetujui</span>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $stats['approved'] }}</span>
                                <span class="fs-7 text-muted">siap dicairkan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-flush h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between p-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-light-success badge-circle w-35px h-35px me-3">
                                    <i class="ki-duotone ki-wallet text-success fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                </span>
                                <span class="text-gray-500 fw-bold fs-7 text-uppercase">Dana Dikembalikan</span>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $stats['refunded'] }}</span>
                                <span class="fs-7 text-muted">berhasil selesai</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-flush h-100 border-0 shadow-sm bg-gray-900 text-white">
                        <div class="card-body d-flex flex-column justify-content-between p-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-white-50 fw-bold fs-7 text-uppercase">Total Nilai Refund Selesai</span>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2 fw-bolder text-white">Rp {{ number_format($stats['total_refunded_amount'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <form action="{{ route('admin.refunds.index') }}" method="GET" class="d-flex align-items-center m-0">
                                <input type="hidden" name="status" value="{{ $currentStatus }}">
                                <input type="text" name="q" value="{{ $searchQuery }}" class="form-control form-control-solid w-250px w-md-350px ps-13 fs-7" placeholder="Cari tiket, invoice, nama, HP...">
                            </form>
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary {{ $currentStatus === 'all' ? 'active' : '' }}" href="{{ route('admin.refunds.index', ['status' => 'all', 'q' => $searchQuery]) }}">
                                    Semua ({{ $stats['all'] }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary {{ $currentStatus === 'pending' ? 'active' : '' }}" href="{{ route('admin.refunds.index', ['status' => 'pending', 'q' => $searchQuery]) }}">
                                    Menunggu ({{ $stats['pending'] }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary {{ $currentStatus === 'approved' ? 'active' : '' }}" href="{{ route('admin.refunds.index', ['status' => 'approved', 'q' => $searchQuery]) }}">
                                    Disetujui ({{ $stats['approved'] }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary {{ $currentStatus === 'refunded' ? 'active' : '' }}" href="{{ route('admin.refunds.index', ['status' => 'refunded', 'q' => $searchQuery]) }}">
                                    Dikembalikan ({{ $stats['refunded'] }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary {{ $currentStatus === 'rejected' ? 'active' : '' }}" href="{{ route('admin.refunds.index', ['status' => 'rejected', 'q' => $searchQuery]) }}">
                                    Ditolak ({{ $stats['rejected'] }})
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-4">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase gs-0">
                                    <th>Tiket & Tanggal</th>
                                    <th>Data Pesanan</th>
                                    <th>Tipe & Alasan</th>
                                    <th>Nominal</th>
                                    <th>Rekening Tujuan</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($refunds as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.refunds.show', $item->id) }}" class="text-gray-900 fw-bold text-hover-primary d-block">
                                            {{ $item->refund_number }}
                                        </a>
                                        <span class="fs-8 text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $item->order_id) }}" class="text-gray-900 fw-semibold text-hover-primary d-block" target="_blank">
                                            {{ $item->order->order_number }}
                                        </a>
                                        <span class="fs-8 text-dark d-block">{{ $item->customer_name }}</span>
                                        <span class="fs-8 text-muted">{{ $item->customer_phone }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-dark fs-8 mb-1">{{ $item->refund_type_label }}</span>
                                        <span class="text-gray-800 d-block text-truncate" style="max-width: 180px;">{{ $item->reason }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-gray-900 d-block">{{ $item->formatted_refund_amount }}</span>
                                        @if($item->approved_amount && $item->approved_amount != $item->refund_amount)
                                            <span class="fs-8 text-success d-block">Disetujui: {{ $item->formatted_approved_amount }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-gray-800 d-block">{{ $item->bank_name }} - {{ $item->bank_account_number }}</span>
                                        <span class="fs-8 text-muted">a.n {{ $item->bank_account_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->status_badge_class }} fs-8 fw-bold px-3 py-1">
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.refunds.show', $item->id) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                            Tinjau & Eksekusi
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-muted">
                                        Belum ada pengajuan pengembalian dana pada kategori ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $refunds->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
