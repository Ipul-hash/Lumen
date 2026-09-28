@extends('admin.layouts.app')

@section('title', 'Detail Pengembalian Dana ' . $refund->refund_number)

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Tiket Pengembalian Dana: {{ $refund->refund_number }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.refunds.index') }}" class="text-muted text-hover-primary">Pengembalian Dana</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-900">{{ $refund->refund_number }}</li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.refunds.index') }}" class="btn btn-sm btn-light">
                    <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Kembali
                </a>
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

            <div class="card border-0 shadow-sm mb-6">
                <div class="card-body p-6">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                        <div>
                            <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nomor Tiket Refund</span>
                            <h2 class="fw-bold text-gray-900 mb-1">{{ $refund->refund_number }}</h2>
                            <span class="badge {{ $refund->status_badge_class }} fs-7 fw-bold px-3 py-1">
                                {{ $refund->status_label }}
                            </span>
                            <span class="text-muted fs-8 ms-2">Dibuat pada {{ $refund->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @if($refund->status === 'pending')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalApproveRefund">
                                    <i class="ki-duotone ki-check fs-4 me-1"></i> Setujui Pengajuan
                                </button>
                                <button type="button" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#modalRejectRefund">
                                    <i class="ki-duotone ki-cross fs-4 me-1"></i> Tolak
                                </button>
                            @elseif($refund->status === 'approved')
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalDisburseRefund">
                                    <i class="ki-duotone ki-wallet fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    Eksekusi Pengembalian Dana
                                </button>
                                <button type="button" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#modalRejectRefund">
                                    <i class="ki-duotone ki-cross fs-4 me-1"></i> Tolak
                                </button>
                            @elseif($refund->status === 'refunded')
                                <div class="badge badge-light-success p-3 fs-7 fw-bold">
                                    <i class="ki-duotone ki-verify fs-4 text-success me-1"><span class="path1"></span><span class="path2"></span></i>
                                    Dana Selesai Ditransfer
                                </div>
                            @elseif($refund->status === 'rejected')
                                <div class="badge badge-light-danger p-3 fs-7 fw-bold">
                                    <i class="ki-duotone ki-cross-circle fs-4 text-danger me-1"><span class="path1"></span><span class="path2"></span></i>
                                    Pengajuan Ditolak
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-6 g-xl-9">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm mb-6">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-5">Rincian Pengajuan Refund</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Detail alasan dan item produk yang diklaim customer</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-4 mb-5 pb-5 border-bottom">
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Tipe Pengajuan:</span>
                                    <span class="badge badge-light-dark fs-7 fw-bold">{{ $refund->refund_type_label }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Kategori Alasan:</span>
                                    <span class="fw-bold text-gray-900 fs-7">{{ $refund->reason }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nominal yang Diajukan:</span>
                                    <span class="fs-4 fw-bold text-gray-900">{{ $refund->formatted_refund_amount }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nominal Disetujui:</span>
                                    <span class="fs-4 fw-bold text-success">{{ $refund->formatted_approved_amount }}</span>
                                </div>
                            </div>

                            @if($refund->reason_detail)
                            <div class="mb-5 pb-5 border-bottom">
                                <label class="text-muted fs-8 d-block text-uppercase fw-bold mb-2">Penjelasan Detail Customer:</label>
                                <div class="p-4 bg-light rounded-3 fs-7 text-gray-800">
                                    {{ $refund->reason_detail }}
                                </div>
                            </div>
                            @endif

                            <div class="mb-5">
                                <label class="text-muted fs-8 d-block text-uppercase fw-bold mb-3">Item Produk yang Diajukan:</label>
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-7 gy-3">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase gs-0">
                                                <th>Produk</th>
                                                <th>Harga Satuan</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @forelse($refund->items as $rItem)
                                            <tr>
                                                <td>
                                                    <span class="text-gray-900 fw-bold d-block">{{ $rItem->product_name }}</span>
                                                    @if($rItem->variant_name)
                                                        <span class="fs-8 text-muted">{{ $rItem->variant_name }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $rItem->formatted_price }}</td>
                                                <td class="text-center">{{ $rItem->quantity }}x</td>
                                                <td class="text-end fw-bold text-gray-900">{{ $rItem->formatted_subtotal }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    Pengajuan berlaku untuk seluruh isi pesanan (Full Order).
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($refund->proof_image)
                            <div class="pt-4 border-top">
                                <label class="text-muted fs-8 d-block text-uppercase fw-bold mb-3">Bukti Foto / Unboxing dari Customer:</label>
                                <div class="d-inline-block border rounded-3 overflow-hidden" style="max-width: 320px;">
                                    <a href="{{ asset('storage/' . $refund->proof_image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $refund->proof_image) }}" alt="Bukti Foto Unboxing" class="img-fluid">
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $refund->proof_image) }}" target="_blank" class="btn btn-sm btn-light fs-8">
                                        <i class="ki-duotone ki-eye fs-5 me-1"></i> Buka Foto Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($refund->status === 'refunded')
                    <div class="card border-0 shadow-sm mb-6 bg-light-success border border-success border-dashed">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title text-success fw-bold fs-5">
                                <i class="ki-duotone ki-shield-tick fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                Data Transfer Pengembalian Dana
                            </h3>
                        </div>
                        <div class="card-body pt-0 fs-7">
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Metode Pencairan:</span>
                                    <span class="fw-bold text-gray-900">{{ $refund->disbursement_method }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nomor Referensi Transfer:</span>
                                    <span class="fw-bold text-gray-900">{{ $refund->disbursement_reference }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Waktu Transfer:</span>
                                    <span class="text-gray-900">{{ $refund->disbursed_at ? $refund->disbursed_at->format('d M Y, H:i') : '-' }} WIB</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nominal yang Ditransfer:</span>
                                    <span class="fw-bold text-success fs-6">{{ $refund->formatted_approved_amount }}</span>
                                </div>
                            </div>

                            @if($refund->disbursement_proof)
                            <div class="mt-3 pt-3 border-top border-success border-opacity-25">
                                <span class="text-muted fs-8 d-block text-uppercase fw-bold mb-2">Bukti Struk Transfer Bank:</span>
                                <a href="{{ asset('storage/' . $refund->disbursement_proof) }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="ki-duotone ki-document fs-4 me-1"></i> Unduh / Lihat Bukti Struk Transfer
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($refund->admin_notes)
                    <div class="card border-0 shadow-sm mb-6">
                        <div class="card-body p-6">
                            <h4 class="card-title text-gray-900 fw-bold fs-6 mb-2">Catatan Verifikasi Admin:</h4>
                            <p class="fs-7 text-gray-800 mb-0">{{ $refund->admin_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm mb-6">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-6">Rekening Tujuan Transfer</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0 fs-7">
                            <div class="p-4 bg-light rounded-3 d-flex flex-column gap-3">
                                <div>
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Bank / Dompet Digital:</span>
                                    <span class="fw-bold text-gray-900 fs-6">{{ $refund->bank_name }}</span>
                                </div>
                                <div>
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Nomor Rekening / HP:</span>
                                    <span class="fw-bolder text-gray-900 fs-5 ls-1">{{ $refund->bank_account_number }}</span>
                                </div>
                                <div>
                                    <span class="text-muted fs-8 d-block text-uppercase fw-bold">Atas Nama Rekening:</span>
                                    <span class="fw-bold text-gray-900 fs-6">{{ $refund->bank_account_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-6">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-6">Data Pesanan Asli</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.orders.show', $refund->order_id) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                    Buka Pesanan <i class="ki-duotone ki-exit-right-corner fs-6 ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0 fs-7">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between pb-2 border-bottom">
                                    <span class="text-muted">Nomor Faktur:</span>
                                    <span class="fw-bold text-gray-900">{{ $refund->order->order_number }}</span>
                                </div>
                                <div class="d-flex justify-content-between pb-2 border-bottom">
                                    <span class="text-muted">Status Pesanan:</span>
                                    <span class="badge badge-light-dark">{{ $refund->order->status_label }}</span>
                                </div>
                                <div class="d-flex justify-content-between pb-2 border-bottom">
                                    <span class="text-muted">Nama Pelanggan:</span>
                                    <span class="fw-semibold text-gray-900">{{ $refund->order->customer_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between pb-2 border-bottom">
                                    <span class="text-muted">WhatsApp / Telepon:</span>
                                    <span class="text-gray-900">{{ $refund->order->customer_phone }}</span>
                                </div>
                                <div class="d-flex justify-content-between pb-2 border-bottom">
                                    <span class="text-muted">Total Pembayaran:</span>
                                    <span class="fw-bold text-gray-900">{{ $refund->order->formatted_total }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Biaya Ongkir:</span>
                                    <span class="text-gray-900">{{ $refund->order->formatted_shipping_cost }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalApproveRefund" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="fw-bold text-gray-900 m-0">Setujui Pengajuan Refund</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
            </div>
            <form action="{{ route('admin.refunds.approve', $refund->id) }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted fs-7 mb-4">
                        Anda akan menyetujui pengajuan refund untuk tiket <strong>{{ $refund->refund_number }}</strong>. Pastikan nominal yang disetujui sudah tepat sebelum diajukan ke proses transfer pencairan dana.
                    </p>

                    <div class="mb-4">
                        <label class="form-label fs-7 fw-bold text-gray-800 required">Nominal Refund yang Disetujui (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">Rp</span>
                            <input type="number" 
                                   name="approved_amount" 
                                   class="form-control form-control-solid fw-bold fs-6" 
                                   value="{{ old('approved_amount', (int)$refund->refund_amount) }}" 
                                   min="1000" 
                                   max="{{ (int)$refund->order->total_amount }}" 
                                   required>
                        </div>
                        <span class="fs-8 text-muted mt-1 d-block">Maksimal: {{ $refund->order->formatted_total }} (Total Invoice).</span>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fs-7 fw-bold text-gray-800">Catatan Persetujuan (Opsional)</label>
                        <textarea name="admin_notes" rows="3" class="form-control form-control-solid fs-7" placeholder="Contoh: Pengajuan disetujui. Barang retur tidak perlu dikirim kembali. Dana akan dicairkan 1x24 jam.">{{ old('admin_notes', $refund->admin_notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="ki-duotone ki-check fs-4 me-1"></i> Simpan Persetujuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRejectRefund" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="fw-bold text-danger m-0">Tolak Pengajuan Refund</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
            </div>
            <form action="{{ route('admin.refunds.reject', $refund->id) }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted fs-7 mb-4">
                        Penolakan akan menghentikan proses refund untuk tiket <strong>{{ $refund->refund_number }}</strong>. Alasan penolakan akan ditampilkan kepada pelanggan.
                    </p>

                    <div class="mb-0">
                        <label class="form-label fs-7 fw-bold text-gray-800 required">Alasan Penolakan</label>
                        <textarea name="admin_notes" rows="4" class="form-control form-control-solid fs-7" placeholder="Jelaskan alasan penolakan secara jelas kepada customer (misal: Bukti unboxing tidak valid, batas klaim 48 jam terlewati, dll)..." required>{{ old('admin_notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="ki-duotone ki-cross fs-4 me-1"></i> Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDisburseRefund" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="fw-bold text-gray-900 m-0">Eksekusi Pengembalian Dana</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
            </div>
            <form action="{{ route('admin.refunds.disburse', $refund->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <div class="p-4 bg-light-primary rounded-3 mb-4 border border-primary border-dashed">
                        <div class="d-flex justify-content-between mb-1 fs-7">
                            <span class="text-muted">Nominal Ditransfer:</span>
                            <span class="fw-bold text-primary fs-5">{{ $refund->formatted_approved_amount }}</span>
                        </div>
                        <div class="d-flex justify-content-between fs-8">
                            <span class="text-muted">Rekening Penerima:</span>
                            <span class="fw-bold text-gray-800">{{ $refund->bank_name }} {{ $refund->bank_account_number }} (a.n {{ $refund->bank_account_name }})</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-bold text-gray-800 required">Metode Transfer Bank / E-Wallet</label>
                            <input type="text" name="disbursement_method" class="form-control form-control-solid fs-7" placeholder="Contoh: Transfer Antar Bank BCA" value="{{ old('disbursement_method', 'Transfer Bank ' . $refund->bank_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-bold text-gray-800 required">Nomor Referensi / No. Transaksi</label>
                            <input type="text" name="disbursement_reference" class="form-control form-control-solid fs-7" placeholder="Contoh: REF2026092800123" value="{{ old('disbursement_reference') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-7 fw-bold text-gray-800">Unggah Bukti Struk Transfer (Opsional)</label>
                        <input type="file" name="disbursement_proof" class="form-control form-control-solid fs-7" accept="image/png,image/jpeg,image/webp,application/pdf">
                        <span class="fs-8 text-muted mt-1 d-block">Screenshot bukti mutasi / slip transfer ATM / m-Banking (Max 5MB).</span>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fs-7 fw-bold text-gray-800">Catatan Penyelesaian (Opsional)</label>
                        <textarea name="admin_notes" rows="2" class="form-control form-control-solid fs-7" placeholder="Catatan tambahan internal...">{{ old('admin_notes', $refund->admin_notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="ki-duotone ki-check-circle fs-4 me-1"></i> Konfirmasi Dana Telah Dikembalikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
