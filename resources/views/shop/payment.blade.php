@extends('shop.layouts.app')

@section('title', 'Menunggu Pembayaran - ' . $order->order_number . ' | LUMEN Hair Color')

@section('content')
<div class="bg-light py-4 border-bottom">
    <div class="container-fluid px-lg-5">
        <div class="text-center mw-600px mx-auto">
            <span class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning px-3 py-2 text-uppercase fs-8 fw-bold mb-2">
                Menunggu Pembayaran
            </span>
            <h2 class="font-serif fw-bold text-dark mb-1">Selesaikan Pembayaran Anda</h2>
            <p class="text-muted fs-7 mb-0">Nomor Pesanan: <strong>{{ $order->order_number }}</strong></p>
        </div>
    </div>
</div>

<div class="container px-lg-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border rounded-4 shadow-sm mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fs-7 text-uppercase fw-bold text-muted">Batas Waktu Pembayaran</span>
                    <span class="badge bg-danger text-white rounded-pill fs-7 px-3 py-2 fw-bold" id="countdownTimer">29:59</span>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if(!empty($snapToken))
                    <div class="p-4 bg-light border border-dark border-opacity-10 text-center mb-4 rounded-4 shadow-sm">
                        <span class="fs-8 text-muted text-uppercase fw-bold d-block mb-1">Metode Cepat & Otomatis</span>
                        <h5 class="fw-bold text-dark mb-2">Bayar Langsung via Midtrans Gateway</h5>
                        <p class="fs-8 text-muted mb-3">Mendukung GoPay, ShopeePay, OVO, DANA, QRIS, BCA/Mandiri/BNI/BRI VA, serta Kartu Kredit.</p>
                        <button type="button" id="pay-button" class="btn btn-dark w-100 py-3 rounded-pill fw-bold fs-7 shadow-sm text-uppercase">
                            <i class="bi bi-wallet2 me-2 text-warning"></i> Buka Pembayaran Midtrans Snap
                        </button>
                    </div>

                    <div class="position-relative text-center my-4">
                        <hr class="text-muted">
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 fs-8 text-muted fw-bold text-uppercase">
                            Atau Bayar Manual di Bawah
                        </span>
                    </div>
                    @endif

                    @if($payment->payment_type === 'qris')
                        <div class="text-center">
                            <h4 class="font-serif fw-bold text-dark mb-1">Scan Kode QRIS</h4>
                            <p class="fs-8 text-muted mb-4">Dapat dipindai melalui BCA Mobile, Livin Mandiri, GoPay, OVO, DANA, ShopeePay, LinkAja, atau aplikasi m-Banking apa pun.</p>

                            <div class="d-inline-block p-3 border bg-white shadow-sm mb-3">
                                @if(!empty($payment->payload_response['qr_url']))
                                    <img src="{{ $payment->payload_response['qr_url'] }}" alt="QRIS Midtrans Code" style="width: 230px; height: 230px; object-fit: contain;">
                                @else
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($payment->qr_string ?? $payment->transaction_id) }}" alt="QRIS Code" style="width: 220px; height: 220px;">
                                @endif
                                <div class="fs-8 text-center text-muted mt-2 fw-semibold">NMID: ID102003891823 | Midtrans Sandbox</div>
                            </div>

                            <div class="p-3 bg-light border text-center mb-4">
                                <span class="fs-8 text-muted d-block text-uppercase fw-bold">Total Pembayaran (Termasuk Biaya Transaksi)</span>
                                <h2 class="fw-bolder text-dark m-0">{{ $payment->formatted_amount }}</h2>
                            </div>
                        </div>
                    @else
                        <div>
                            <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-4">
                                <div>
                                    <span class="fs-8 text-muted text-uppercase fw-bold">Metode Pembayaran</span>
                                    <h4 class="font-serif fw-bold text-dark m-0">Virtual Account {{ strtoupper($payment->bank) }}</h4>
                                </div>
                                <span class="badge bg-dark text-white fs-6 px-3 py-2 fw-bold">{{ strtoupper($payment->bank) }}</span>
                            </div>

                            <div class="mb-4">
                                <label class="fs-8 text-uppercase fw-bold text-muted mb-1">Nomor Virtual Account</label>
                                <div class="input-group">
                                    <input type="text" id="vaNumberInput" class="form-control form-control-lg rounded-0 fw-bold fs-4 bg-light" value="{{ $payment->va_number }}" readonly>
                                    <button class="btn btn-outline-dark rounded-0 px-4" type="button" onclick="navigator.clipboard.writeText('{{ $payment->va_number }}'); window.showToast('success', 'Nomor VA berhasil disalin!');">
                                        <i class="bi bi-clipboard me-1"></i> Salin
                                    </button>
                                </div>
                            </div>

                            <div class="p-3 bg-light border mb-4 d-flex align-items-center justify-content-between">
                                <span class="fs-7 text-muted">Total Pembayaran</span>
                                <span class="fs-4 fw-bold text-dark">{{ $payment->formatted_amount }}</span>
                            </div>

                            @if(!empty($instructions))
                            <div class="mb-4">
                                <h6 class="fs-7 fw-bold text-uppercase text-dark mb-3">Petunjuk Pembayaran Transfer</h6>
                                <ul class="nav nav-tabs nav-fill mb-3" id="paymentTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active rounded-0 text-dark fw-bold fs-8" id="atm-tab" data-bs-toggle="tab" data-bs-target="#atm-pane" type="button">ATM</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link rounded-0 text-dark fw-bold fs-8" id="mbanking-tab" data-bs-toggle="tab" data-bs-target="#mbanking-pane" type="button">Mobile Banking</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link rounded-0 text-dark fw-bold fs-8" id="ibanking-tab" data-bs-toggle="tab" data-bs-target="#ibanking-pane" type="button">Internet Banking</button>
                                    </li>
                                </ul>

                                <div class="tab-content border-bottom pb-3 fs-8 text-secondary" id="paymentTabContent">
                                    <div class="tab-pane fade show active" id="atm-pane">
                                        <ol class="ps-3 mb-0 d-flex flex-column gap-2">
                                            @foreach($instructions['atm'] as $step)
                                                <li>{!! $step !!}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="tab-pane fade" id="mbanking-pane">
                                        <ol class="ps-3 mb-0 d-flex flex-column gap-2">
                                            @foreach($instructions['mbanking'] as $step)
                                                <li>{!! $step !!}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="tab-pane fade" id="ibanking-pane">
                                        <ol class="ps-3 mb-0 d-flex flex-column gap-2">
                                            @foreach($instructions['ibanking'] as $step)
                                                <li>{!! $step !!}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif

                    <div class="p-3 bg-light border border-info border-opacity-50 text-center mb-4 rounded-3">
                        <div class="fs-8 text-muted mb-2">Simulasi Pengujian Client: Klik tombol di bawah untuk menyimulasikan pembayaran yang berhasil terverifikasi otomatis.</div>
                        <form action="{{ route('payments.simulateSuccess', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 py-2 fw-bold text-uppercase fs-8">
                                <i class="bi bi-check2-circle me-1"></i> Simulasikan Pembayaran Lunas (Instant Settlement)
                            </button>
                        </form>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('shop.index') }}" class="text-muted text-decoration-none fs-8">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda Toko
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!empty($snapToken) && !empty($snapJsUrl))
<script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey ?? '' }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const payBtn = document.getElementById('pay-button');
    if (payBtn) {
        payBtn.addEventListener('click', function () {
            if (typeof window.snap !== 'undefined') {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        window.showToast('success', 'Pembayaran berhasil diverifikasi!');
                        setTimeout(function () {
                            window.location.href = '{{ route('checkout.success', $order->order_number) }}';
                        }, 1200);
                    },
                    onPending: function (result) {
                        window.showToast('info', 'Pembayaran sedang diproses oleh sistem bank.');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    },
                    onError: function (result) {
                        window.showToast('error', 'Pembayaran gagal diproses.');
                    },
                    onClose: function () {
                        window.showToast('warning', 'Jendela pembayaran Midtrans ditutup.');
                    }
                });
            } else {
                window.showToast('error', 'Gagal memuat widget pembayaran Midtrans.');
            }
        });
    }
});
</script>
@endif

<script>
let remainingSeconds = 1800;
const timerEl = document.getElementById('countdownTimer');

function updateTimer() {
    const mins = Math.floor(remainingSeconds / 60);
    const secs = remainingSeconds % 60;
    if (timerEl) {
        timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    if (remainingSeconds > 0) {
        remainingSeconds--;
    }
}

setInterval(updateTimer, 1000);
updateTimer();
</script>
@endpush
