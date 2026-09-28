@extends('shop.layouts.app')

@section('title', 'Pengajuan Pengembalian Dana Berhasil Dikirim | LUMEN Hair Color Atelier')

@section('content')
<div class="container py-5 my-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 text-center">
            <div class="card border rounded-4 shadow-sm p-4 p-md-5 bg-white">
                <div class="symbol symbol-70px bg-success bg-opacity-10 text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-shield-check fs-1 text-success"></i>
                </div>

                <div class="text-uppercase tracking-wider fs-8 text-success fw-bold mb-2">Tiket Pengajuan Berhasil Dibuat</div>
                <h2 class="font-serif fw-bold text-dark mb-3">Pengajuan Pengembalian Dana Diterima</h2>
                <p class="text-muted fs-7 mb-4 px-md-4">
                    Tim Quality Assurance dan Keuangan LUMEN Hair Color Atelier telah menerima permohonan Anda. Pengajuan ini akan diverifikasi dalam waktu maksimal 1x24 jam kerja.
                </p>

                <div class="p-3 bg-light rounded-3 text-start mb-4 border">
                    <div class="d-flex justify-content-between py-2 border-bottom fs-7">
                        <span class="text-muted">Nomor Tiket Refund:</span>
                        <span class="fw-bold text-dark">{{ $refund->refund_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom fs-7">
                        <span class="text-muted">Nomor Faktur Pesanan:</span>
                        <span class="fw-semibold text-dark">{{ $refund->order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom fs-7">
                        <span class="text-muted">Nominal Diajukan:</span>
                        <span class="fw-bold text-dark">{{ $refund->formatted_refund_amount }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom fs-7">
                        <span class="text-muted">Rekening Tujuan:</span>
                        <span class="text-dark fw-semibold">{{ $refund->bank_name }} - {{ $refund->bank_account_number }} (a.n {{ $refund->bank_account_name }})</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 fs-7">
                        <span class="text-muted">Status Saat Ini:</span>
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">{{ $refund->status_label }}</span>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('refund.track', ['q' => $refund->refund_number]) }}" class="btn btn-dark py-3 px-4 rounded-pill fw-bold fs-7 shadow-sm text-uppercase">
                        <i class="bi bi-geo-alt me-1 text-warning"></i> Lacak Status Pengajuan Ini
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-dark py-3 px-4 rounded-pill fw-bold fs-7 text-uppercase">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
