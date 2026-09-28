@extends('shop.layouts.app')

@section('title', 'Lacak Status Pengajuan Pengembalian Dana | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-dark text-white py-4 py-lg-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%);">
    <div class="container px-lg-5 position-relative" style="z-index: 1;">
        <div class="text-center mw-800px mx-auto">
            <div class="text-uppercase tracking-wider fs-8 text-white-50 fw-bold mb-2">
                Pusat Transparansi Layanan Pelanggan
            </div>
            <h1 class="display-6 display-lg-5 font-serif fw-bold text-white mb-2">Lacak Status Pengembalian Dana</h1>
            <p class="text-light opacity-75 fs-7 fs-md-6 mx-auto mb-0" style="max-width: 650px; line-height: 1.6;">
                Pantau proses verifikasi, persetujuan mutu salon, hingga bukti transfer pencairan dana pengembalian pesanan Anda secara transparan dan real-time.
            </p>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4 py-lg-5 pb-5 mb-5" style="background: #fdfdfd;">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                <form action="{{ route('refund.track') }}" method="GET" class="mb-0">
                    <label class="form-label fs-8 fw-bold text-uppercase text-muted">Cari Berdasarkan Nomor Tiket Refund atau Nomor Invoice</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="q" 
                               class="form-control form-control-lg border-start-0 fs-7" 
                               placeholder="Contoh: RFD-2026... atau INV-2026..." 
                               value="{{ $q ?? '' }}" 
                               required>
                        <button type="submit" class="btn btn-dark px-4 fw-semibold fs-7">
                            Lacak Tiket
                        </button>
                    </div>
                </form>
            </div>

            @if($q && !$refund)
            <div class="card border rounded-4 shadow-sm p-5 text-center bg-white">
                <div class="symbol symbol-60px bg-light text-muted rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-search fs-2"></i>
                </div>
                <h5 class="font-serif fw-bold text-dark mb-1">Tiket Tidak Ditemukan</h5>
                <p class="text-muted fs-7 mb-4">
                    Tidak ditemukan riwayat pengajuan pengembalian dana dengan kata kunci <strong>"{{ $q }}"</strong>. Pastikan nomor tiket atau nomor pesanan Anda sudah benar.
                </p>
                <div>
                    <a href="{{ route('refund.create') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fs-7 fw-semibold">
                        Buat Pengajuan Baru
                    </a>
                </div>
            </div>
            @endif

            @if($refund)
            <div class="card border rounded-4 shadow-sm p-4 p-md-5 bg-white mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div>
                        <span class="fs-8 text-muted d-block">Nomor Tiket Pengajuan</span>
                        <h4 class="font-serif fw-bold text-dark mb-0">{{ $refund->refund_number }}</h4>
                        <span class="fs-8 text-muted">Diajukan pada {{ $refund->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="text-md-end">
                        <span class="fs-8 text-muted d-block">Status Terkini</span>
                        @if($refund->status === 'pending')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-7 fw-bold">
                                <i class="bi bi-clock-history me-1"></i> Menunggu Persetujuan
                            </span>
                        @elseif($refund->status === 'approved')
                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill fs-7 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Disetujui (Menunggu Transfer)
                            </span>
                        @elseif($refund->status === 'refunded')
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-7 fw-bold">
                                <i class="bi bi-check2-all me-1"></i> Dana Berhasil Dikembalikan
                            </span>
                        @elseif($refund->status === 'rejected')
                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-7 fw-bold">
                                <i class="bi bi-x-circle me-1"></i> Pengajuan Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-light rounded-4 mb-4 border">
                    <div class="row text-center position-relative">
                        <div class="col-4">
                            <div class="symbol rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2 {{ $refund->status !== 'rejected' ? 'bg-success text-white' : 'bg-success text-white' }}" style="width: 44px; height: 44px;">
                                <i class="bi bi-file-earmark-text fs-5"></i>
                            </div>
                            <span class="fs-8 fw-bold text-dark d-block">1. Pengajuan Dikirim</span>
                            <span class="fs-9 text-muted">{{ $refund->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="col-4">
                            @if($refund->status === 'pending')
                                <div class="symbol bg-warning text-dark rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-hourglass-split fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-dark d-block">2. Verifikasi Admin</span>
                                <span class="fs-9 text-warning fw-semibold">Sedang Ditinjau</span>
                            @elseif($refund->status === 'rejected')
                                <div class="symbol bg-danger text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-x-lg fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-danger d-block">2. Verifikasi Ditolak</span>
                                <span class="fs-9 text-muted">{{ $refund->rejected_at ? $refund->rejected_at->format('d/m/Y') : '' }}</span>
                            @else
                                <div class="symbol bg-success text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-check-lg fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-dark d-block">2. Disetujui</span>
                                <span class="fs-9 text-muted">{{ $refund->approved_at ? $refund->approved_at->format('d/m/Y') : '' }}</span>
                            @endif
                        </div>

                        <div class="col-4">
                            @if($refund->status === 'refunded')
                                <div class="symbol bg-success text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-cash-coin fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-success d-block">3. Dana Dikembalikan</span>
                                <span class="fs-9 text-muted">{{ $refund->disbursed_at ? $refund->disbursed_at->format('d/m/Y') : '' }}</span>
                            @elseif($refund->status === 'approved')
                                <div class="symbol bg-primary bg-opacity-25 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-arrow-repeat fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-dark d-block">3. Proses Transfer</span>
                                <span class="fs-9 text-primary fw-semibold">Antrean Pembayaran</span>
                            @elseif($refund->status === 'rejected')
                                <div class="symbol bg-light text-muted rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-dash fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-muted d-block">3. Selesai</span>
                                <span class="fs-9 text-muted">Tidak Dilanjutkan</span>
                            @else
                                <div class="symbol bg-light text-muted rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
                                    <i class="bi bi-clock fs-5"></i>
                                </div>
                                <span class="fs-8 fw-bold text-muted d-block">3. Pengembalian Dana</span>
                                <span class="fs-9 text-muted">Menunggu Approval</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($refund->status === 'rejected' && $refund->admin_notes)
                <div class="alert alert-danger border-0 rounded-3 mb-4">
                    <div class="fw-bold fs-7 mb-1">Catatan Penolakan dari Admin:</div>
                    <p class="fs-8 mb-0">{{ $refund->admin_notes }}</p>
                </div>
                @endif

                @if($refund->status === 'approved' && $refund->admin_notes)
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <div class="fw-bold fs-7 mb-1">Catatan Persetujuan:</div>
                    <p class="fs-8 mb-0">{{ $refund->admin_notes }}</p>
                </div>
                @endif

                @if($refund->status === 'refunded')
                <div class="alert alert-success border-0 rounded-3 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                        <span class="fw-bold fs-7">Dana Telah Berhasil Ditransfer!</span>
                    </div>
                    <div class="fs-8 text-dark mb-2">
                        Pengembalian dana sebesar <strong>Rp {{ number_format($refund->approved_amount ?? $refund->refund_amount, 0, ',', '.') }}</strong> telah ditransfer melalui <strong>{{ $refund->disbursement_method }}</strong> dengan Nomor Referensi: <strong>{{ $refund->disbursement_reference }}</strong> pada tanggal {{ $refund->disbursed_at ? $refund->disbursed_at->format('d M Y, H:i') : '' }} WIB.
                    </div>
                    @if($refund->disbursement_proof)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $refund->disbursement_proof) }}" target="_blank" class="btn btn-sm btn-dark rounded-pill fs-8">
                            <i class="bi bi-receipt me-1 text-warning"></i> Lihat Bukti Transfer Bank
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fs-8 text-uppercase fw-bold text-muted mb-3 tracking-wider">Rincian Pengajuan</h6>
                        <div class="p-3 bg-light rounded-3 fs-8 d-flex flex-column gap-2 border">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Nomor Faktur:</span>
                                <span class="fw-semibold text-dark">{{ $refund->order->order_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tipe Pengajuan:</span>
                                <span class="fw-semibold text-dark">{{ $refund->refund_type_label }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kategori Alasan:</span>
                                <span class="fw-semibold text-dark">{{ $refund->reason }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Nominal Diajukan:</span>
                                <span class="fw-bold text-dark">{{ $refund->formatted_refund_amount }}</span>
                            </div>
                            @if($refund->approved_amount)
                            <div class="d-flex justify-content-between text-success">
                                <span class="fw-bold">Nominal Disetujui:</span>
                                <span class="fw-bold fs-7">{{ $refund->formatted_approved_amount }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fs-8 text-uppercase fw-bold text-muted mb-3 tracking-wider">Rekening Tujuan Refund</h6>
                        <div class="p-3 bg-light rounded-3 fs-8 d-flex flex-column gap-2 border">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Bank / E-Wallet:</span>
                                <span class="fw-semibold text-dark">{{ $refund->bank_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Nomor Rekening:</span>
                                <span class="fw-bold text-dark">{{ $refund->bank_account_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Atas Nama:</span>
                                <span class="fw-semibold text-dark">{{ $refund->bank_account_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kontak Pemohon:</span>
                                <span class="text-dark">{{ $refund->customer_phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($refund->reason_detail)
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fs-8 text-uppercase fw-bold text-muted mb-2 tracking-wider">Keterangan Customer:</h6>
                    <p class="fs-8 text-muted mb-0 bg-light p-3 rounded-3">{{ $refund->reason_detail }}</p>
                </div>
                @endif

                @if($refund->proof_image)
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fs-8 text-uppercase fw-bold text-muted mb-2 tracking-wider">Bukti Foto Kemasan / Kerusakan:</h6>
                    <div class="rounded-3 border overflow-hidden d-inline-block" style="max-width: 240px;">
                        <a href="{{ asset('storage/' . $refund->proof_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $refund->proof_image) }}" alt="Bukti Foto" class="img-fluid">
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
