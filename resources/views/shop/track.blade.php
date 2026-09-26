@extends('shop.layouts.app')

@section('title', 'Lacak Pengiriman KiriminAja | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-dark text-white py-5 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%);">
    <div class="container px-lg-5 position-relative" style="z-index: 1;">
        <span class="oxva-badge mb-3">
            <span style="width: 7px; height: 7px; border-radius: 50%; background: #38bdf8; display: inline-block; box-shadow: 0 0 10px #38bdf8;"></span>
            Real-Time Logistics Tracking
        </span>
        <h1 class="display-6 font-serif fw-bold text-white mb-2">Lacak Status Paket & Resi Pengiriman</h1>
        <p class="text-light opacity-75 fs-7 mx-auto mb-4" style="max-width: 600px;">
            Masukkan Nomor Invoice Pesanan (contoh: <code>INV-2026...</code>) atau Nomor Resi AWB KiriminAja untuk melihat posisi kurir saat ini.
        </p>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <form action="{{ route('shop.track') }}" method="GET" class="input-group input-group-lg bg-white rounded-pill p-1 border shadow-sm">
                    <input type="text" name="q" class="form-control border-0 rounded-pill fs-7 px-4 shadow-none" placeholder="Nomor Pesanan / Nomor Resi..." value="{{ request('q') }}" required>
                    <button class="btn btn-dark rounded-pill px-4 fs-7 fw-bold" type="submit">
                        <i class="bi bi-search me-1"></i> Lacak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container px-lg-5 py-5">
    @if(request()->filled('q'))
        @if($order && $shipment)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border rounded-4 shadow-sm mb-4 overflow-hidden oxva-reveal">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-8 text-muted text-uppercase fw-bold">Nomor Pesanan</span>
                                <h5 class="font-serif fw-bold text-dark m-0">{{ $order->order_number }}</h5>
                            </div>
                            <span class="badge bg-primary text-white rounded-pill fs-7 px-3 py-2 fw-bold">
                                {{ $shipment->status_label }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            <div class="p-3 bg-light border rounded-3 mb-4">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <span class="fs-8 text-muted d-block text-uppercase fw-bold">Ekspedisi Kurir</span>
                                        <span class="fs-7 fw-bold text-dark">{{ $shipment->courier_name }} ({{ $shipment->service_type }})</span>
                                    </div>
                                    <div class="col-sm-5">
                                        <span class="fs-8 text-muted d-block text-uppercase fw-bold">Nomor Resi (AWB)</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fs-6 fw-bold text-primary">{{ $shipment->waybill_number ?? 'Menunggu Penjemputan Kurir' }}</span>
                                            @if($shipment->waybill_number)
                                                <button class="btn btn-link p-0 text-muted" type="button" onclick="navigator.clipboard.writeText('{{ $shipment->waybill_number }}'); window.showToast('success', 'Nomor resi berhasil disalin!');">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <span class="fs-8 text-muted d-block text-uppercase fw-bold">Tujuan</span>
                                        <span class="fs-7 text-dark">{{ $order->shipping_address['district_name'] ?? '-' }}, {{ $order->shipping_address['city_name'] ?? '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fs-7 fw-bold text-uppercase text-dark mb-4 pb-2 border-bottom">Riwayat Perjalanan Paket (KiriminAja Live)</h6>

                            @if(!empty($trackingCheckpoints))
                                <div class="position-relative ps-4 ms-2" style="border-left: 2px solid #e5e7eb;">
                                    @foreach($trackingCheckpoints as $index => $cp)
                                        <div class="position-relative mb-4">
                                            <div class="position-absolute" style="left: -25px; top: 0;">
                                                <span class="badge {{ $index === 0 ? 'bg-primary' : 'bg-secondary' }} rounded-circle p-2" style="width: 14px; height: 14px; display: inline-block;"></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge {{ $index === 0 ? 'bg-light-primary text-primary border border-primary' : 'bg-light text-secondary border' }} rounded-pill fs-8 fw-bold">
                                                    {{ $cp['status'] ?? 'UPDATE' }}
                                                </span>
                                                <span class="fs-8 text-muted">{{ $cp['time'] ?? '-' }}</span>
                                            </div>
                                            <p class="fs-7 text-dark mb-1 fw-semibold">{{ $cp['note'] ?? '' }}</p>
                                            @if(!empty($cp['location']))
                                                <div class="fs-8 text-muted">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> Lokasi: {{ $cp['location'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-clock-history fs-2 mb-2 d-block"></i>
                                    <p class="fs-7 mb-0">Paket sedang dipersiapkan di warehouse Kebayoran Baru, Jakarta Selatan. Nomor resi KiriminAja akan segera aktif setelah kurir melakukan scanning pickup.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center py-5">
                    <i class="bi bi-question-circle fs-1 text-muted"></i>
                    <h5 class="font-serif fw-bold text-dark mt-3">Data Pengiriman Tidak Ditemukan</h5>
                    <p class="text-muted fs-7">Nomor pesanan atau nomor resi "<strong>{{ request('q') }}</strong>" tidak cocok dengan data transaksi kami. Pastikan nomor yang dimasukkan sudah benar.</p>
                    <a href="{{ route('shop.track') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4">Coba Lagi</a>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4 text-center">
                    <div class="col-md-4 oxva-reveal delay-1">
                        <div class="atelier-feature-card flex-column text-center p-4">
                            <div class="atelier-feature-icon mx-auto mb-3">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h6 class="fw-bold fs-7 text-uppercase text-dark mb-1">1. Otomasi Booking</h6>
                            <p class="fs-8 text-muted mb-0">Setiap order yang lunas langsung membuat booking ID pada sistem KiriminAja secara otomatis.</p>
                        </div>
                    </div>
                    <div class="col-md-4 oxva-reveal delay-2">
                        <div class="atelier-feature-card flex-column text-center p-4">
                            <div class="atelier-feature-icon mx-auto mb-3">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <h6 class="fw-bold fs-7 text-uppercase text-dark mb-1">2. Auto-AWB Resi</h6>
                            <p class="fs-8 text-muted mb-0">Nomor resi kurir diterbitkan otomatis dan langsung ditempel pada label thermal protektif.</p>
                        </div>
                    </div>
                    <div class="col-md-4 oxva-reveal delay-3">
                        <div class="atelier-feature-card flex-column text-center p-4">
                            <div class="atelier-feature-icon mx-auto mb-3">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h6 class="fw-bold fs-7 text-uppercase text-dark mb-1">3. Kurir Pickup</h6>
                            <p class="fs-8 text-muted mb-0">Kurir J&T, SiCepat, atau JNE menjemput paket langsung dari warehouse harian.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
