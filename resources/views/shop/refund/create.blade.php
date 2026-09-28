@extends('shop.layouts.app')

@section('title', 'Form Pengajuan Pengembalian Dana | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-dark text-white py-4 py-lg-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%);">
    <div class="container px-lg-5 position-relative" style="z-index: 1;">
        <div class="text-center mw-800px mx-auto">
            <div class="text-uppercase tracking-wider fs-8 text-white-50 fw-bold mb-2">
                Garansi Kepuasan Pelanggan & Jaminan Salon-Grade
            </div>
            <h1 class="display-6 display-lg-5 font-serif fw-bold text-white mb-2">Pengajuan Pengembalian Dana & Retur</h1>
            <p class="text-light opacity-75 fs-7 fs-md-6 mx-auto mb-0" style="max-width: 650px; line-height: 1.6;">
                Kami menjamin mutu setiap formulasi salon LUMEN. Jika paket Anda mengalami kerusakan dalam pengiriman ekspedisi atau ketidaksesuaian varian, silakan lengkapi form berikut untuk proses verifikasi dan pencairan dana.
            </p>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4 py-lg-5 pb-5 mb-5" style="background: #fdfdfd;">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                <div class="fs-7 fw-semibold">{{ session('error') }}</div>
            </div>
            @endif

            <form action="{{ route('refund.store') }}" method="POST" enctype="multipart/form-data" id="refundForm">
                @csrf

                <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                        <div>
                            <h5 class="font-serif fw-bold text-dark mb-0">Verifikasi Nomor Pesanan</h5>
                            <span class="fs-8 text-muted">Masukkan nomor faktur pesanan Anda (contoh: INV-2026...)</span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-receipt text-muted"></i>
                        </span>
                        <input type="text" 
                               name="order_number" 
                               id="orderNumberInput" 
                               class="form-control form-control-lg border-start-0 fs-7 @error('order_number') is-invalid @enderror" 
                               placeholder="Masukkan Nomor Invoice Pesanan..." 
                               value="{{ old('order_number', $prefilledOrder->order_number ?? '') }}" 
                               required>
                        <button class="btn btn-dark px-4 fw-semibold fs-7" type="button" id="btnLookupOrder" onclick="performOrderLookup()">
                            <i class="bi bi-search me-1"></i> Cek Pesanan
                        </button>
                    </div>
                    @error('order_number')
                        <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                    @enderror

                    <div id="lookupAlert" class="d-none"></div>

                    <div id="orderCardContainer" class="{{ $prefilledOrder ? '' : 'd-none' }} mt-3">
                        <div class="p-3 p-md-4 rounded-3 bg-light border">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
                                <div>
                                    <span class="fs-8 text-muted d-block">Nomor Faktur</span>
                                    <span class="fw-bold fs-6 text-dark" id="displayOrderNumber">{{ $prefilledOrder->order_number ?? '' }}</span>
                                </div>
                                <div class="text-end">
                                    <span class="fs-8 text-muted d-block">Status Pesanan</span>
                                    <span class="badge bg-dark text-white rounded-pill px-3 py-1 fs-8" id="displayOrderStatus">{{ $prefilledOrder->status_label ?? '' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fs-8 fw-bold text-uppercase text-muted mb-2 d-block">Pilih Item Produk yang Diajukan:</label>
                                <div class="d-flex flex-column gap-2" id="orderItemsList">
                                    @if($prefilledOrder)
                                        @foreach($prefilledOrder->items as $item)
                                        <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-2 border fs-8">
                                            <div class="form-check d-flex align-items-center gap-2 m-0">
                                                <input class="form-check-input item-select-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="chk_item_{{ $item->id }}" data-price="{{ $item->price }}" data-max-qty="{{ $item->quantity }}" checked onchange="calculateRefundTotal()">
                                                <label class="form-check-label text-dark fw-semibold" for="chk_item_{{ $item->id }}">
                                                    {{ $item->product_name }} @if($item->variant_name) ({{ $item->variant_name }}) @endif
                                                </label>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="text-muted fs-9">{{ $item->quantity }}x @ {{ $item->formatted_price }}</span>
                                                <span class="fw-bold text-dark">{{ $item->formatted_subtotal }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top fs-7">
                                <span class="text-muted">Total Transaksi Asli:</span>
                                <span class="fw-bolder text-dark fs-6" id="displayOrderTotal">{{ $prefilledOrder ? $prefilledOrder->formatted_total : 'Rp 0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                        <div>
                            <h5 class="font-serif fw-bold text-dark mb-0">Informasi Pemohon</h5>
                            <span class="fs-8 text-muted">Pastikan nomor kontak aktif untuk konfirmasi tim layanan pelanggan</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nama Lengkap</label>
                            <input type="text" name="customer_name" id="customerNameInput" class="form-control rounded-3 fs-7" value="{{ old('customer_name', $prefilledOrder->customer_name ?? '') }}" placeholder="Nama Anda" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Alamat Email</label>
                            <input type="email" name="customer_email" id="customerEmailInput" class="form-control rounded-3 fs-7" value="{{ old('customer_email', $prefilledOrder->customer_email ?? '') }}" placeholder="email@domain.com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nomor WhatsApp</label>
                            <input type="tel" name="customer_phone" id="customerPhoneInput" class="form-control rounded-3 fs-7" value="{{ old('customer_phone', $prefilledOrder->customer_phone ?? '') }}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">3</span>
                        <div>
                            <h5 class="font-serif fw-bold text-dark mb-0">Alasan & Tipe Pengembalian</h5>
                            <span class="fs-8 text-muted">Pilih alasan yang sesuai dengan kendala paket Anda</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-8 fw-bold text-uppercase text-muted">Tipe Pengajuan</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 w-100 cursor-pointer type-option-card active" style="cursor: pointer;">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="refund_type" value="full" checked onchange="handleTypeChange(this)">
                                        <span class="fw-bold fs-7 text-dark d-block">Full Refund</span>
                                        <span class="fs-9 text-muted">Pengembalian total seluruh pesanan</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 w-100 cursor-pointer type-option-card" style="cursor: pointer;">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="refund_type" value="partial" onchange="handleTypeChange(this)">
                                        <span class="fw-bold fs-7 text-dark d-block">Partial Refund</span>
                                        <span class="fs-9 text-muted">Pengembalian item produk tertentu</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 w-100 cursor-pointer type-option-card" style="cursor: pointer;">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="refund_type" value="return_and_refund" onchange="handleTypeChange(this)">
                                        <span class="fw-bold fs-7 text-dark d-block">Retur & Refund</span>
                                        <span class="fs-9 text-muted">Kirim kembali barang + refund dana</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-8 fw-bold text-uppercase text-muted">Kategori Alasan Pengajuan</label>
                        <select name="reason" class="form-select rounded-3 fs-7" required>
                            <option value="">Pilih Alasan Pengembalian...</option>
                            <option value="Barang Rusak / Pecah dalam Pengiriman" {{ old('reason') == 'Barang Rusak / Pecah dalam Pengiriman' ? 'selected' : '' }}>Barang Rusak / Pecah dalam Pengiriman Ekspedisi</option>
                            <option value="Salah Kirim Shade / Varian Produk" {{ old('reason') == 'Salah Kirim Shade / Varian Produk' ? 'selected' : '' }}>Salah Kirim Shade / Varian Produk oleh Penjual</option>
                            <option value="Kemasan Bocor / Segel Terbuka" {{ old('reason') == 'Kemasan Bocor / Segel Terbuka' ? 'selected' : '' }}>Kemasan Bocor / Segel Keamanan Rusak</option>
                            <option value="Reaksi Sensitivitas Kulit / Formula Tidak Sesuai" {{ old('reason') == 'Reaksi Sensitivitas Kulit / Formula Tidak Sesuai' ? 'selected' : '' }}>Reaksi Sensitivitas Kulit / Formula Tidak Sesuai Kanvas</option>
                            <option value="Pesanan Dibatalkan Sebelum Dikirim" {{ old('reason') == 'Pesanan Dibatalkan Sebelum Dikirim' ? 'selected' : '' }}>Pesanan Dibatalkan Sebelum Dikirim</option>
                            <option value="Lainnya" {{ old('reason') == 'Lainnya' ? 'selected' : '' }}>Lainnya (Jelaskan di catatan bawah)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fs-8 fw-bold text-uppercase text-muted">Penjelasan Rinci Kendala</label>
                        <textarea name="reason_detail" rows="3" class="form-control rounded-3 fs-7" placeholder="Ceritakan kondisi produk saat diterima dan alasan pengajuan secara detail...">{{ old('reason_detail') }}</textarea>
                    </div>
                </div>

                <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">4</span>
                        <div>
                            <h5 class="font-serif fw-bold text-dark mb-0">Nominal & Rekening Tujuan Pengembalian Dana</h5>
                            <span class="fs-8 text-muted">Rekening bank atau dompet digital tempat dana akan ditransfer kembali</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nominal Pengembalian Dana yang Diajukan (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold fs-7">Rp</span>
                            <input type="number" 
                                   name="refund_amount" 
                                   id="refundAmountInput" 
                                   class="form-control form-control-lg fs-6 fw-bold" 
                                   value="{{ old('refund_amount', $prefilledOrder ? (int)$prefilledOrder->total_amount : '') }}" 
                                   min="1000" 
                                   required>
                        </div>
                        <span class="fs-9 text-muted mt-1 d-block" id="refundAmountHelp">Nominal maksimal sesuai total belanja pesanan terverifikasi.</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nama Bank / E-Wallet</label>
                            <select name="bank_name" class="form-select rounded-3 fs-7" required>
                                <option value="">Pilih Bank / Dompet Digital...</option>
                                <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                                <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                                <option value="Permata" {{ old('bank_name') == 'Permata' ? 'selected' : '' }}>Bank Permata</option>
                                <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="BSI" {{ old('bank_name') == 'BSI' ? 'selected' : '' }}>Bank Syariah Indonesia (BSI)</option>
                                <option value="GoPay" {{ old('bank_name') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                                <option value="OVO" {{ old('bank_name') == 'OVO' ? 'selected' : '' }}>OVO</option>
                                <option value="Dana" {{ old('bank_name') == 'Dana' ? 'selected' : '' }}>DANA</option>
                                <option value="ShopeePay" {{ old('bank_name') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nomor Rekening / No. HP E-Wallet</label>
                            <input type="text" name="bank_account_number" class="form-control rounded-3 fs-7" value="{{ old('bank_account_number') }}" placeholder="Contoh: 8271039821" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-bold text-uppercase text-muted">Nama Pemilik Rekening (A.N)</label>
                            <input type="text" name="bank_account_name" class="form-control rounded-3 fs-7" value="{{ old('bank_account_name') }}" placeholder="Sesuai buku tabungan" required>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">5</span>
                        <div>
                            <h5 class="font-serif fw-bold text-dark mb-0">Unggah Bukti Foto / Video Unboxing</h5>
                            <span class="fs-8 text-muted">Lampirkan foto kemasan, label pengiriman, atau kondisi barang yang rusak/salah</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="file" name="proof_image" class="form-control fs-7 rounded-3" accept="image/png,image/jpeg,image/webp" required>
                        <span class="fs-9 text-muted mt-1 d-block">Format didukung: JPG, PNG, WEBP (Maksimal 5 MB).</span>
                    </div>

                    <div class="form-check mt-3 pt-2 border-top">
                        <input class="form-check-input" type="checkbox" id="termsAgreement" required>
                        <label class="form-check-label fs-8 text-muted" for="termsAgreement">
                            Saya menyatakan bahwa data yang saya berikan adalah benar, dan barang yang diajukan sesuai dengan kondisi faktual saat paket diterima.
                        </label>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="{{ route('refund.track') }}" class="btn btn-link text-decoration-none text-muted fs-7">
                        <i class="bi bi-clock-history me-1"></i> Cek Status Pengajuan Sebelumnya
                    </a>
                    <button type="submit" class="btn btn-dark py-3 px-5 rounded-pill fw-bold fs-7 shadow-sm text-uppercase" id="btnSubmitRefund">
                        <i class="bi bi-send-check me-2 text-warning"></i> Kirim Pengajuan Pengembalian Dana
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.cursor-pointer {
    cursor: pointer;
}
.type-option-card {
    transition: all 0.2s ease;
    background: #ffffff;
}
.type-option-card:hover {
    border-color: #111111 !important;
    background: #fafafa;
}
.type-option-card.active {
    border-color: #111111 !important;
    background: #f7f7fa;
    box-shadow: 0 0 0 1px #111111;
}
.fs-9 {
    font-size: 0.72rem;
}
</style>
@endpush

@push('scripts')
<script>
let currentLoadedOrder = @json($prefilledOrder ?? null);

function performOrderLookup() {
    const input = document.getElementById('orderNumberInput');
    const orderNumber = input.value.trim();
    const btn = document.getElementById('btnLookupOrder');
    const alertDiv = document.getElementById('lookupAlert');
    const container = document.getElementById('orderCardContainer');

    if (!orderNumber) {
        alertDiv.className = 'alert alert-warning border-0 rounded-3 fs-8 py-2 mt-2';
        alertDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Silakan masukkan nomor pesanan terlebih dahulu.';
        alertDiv.classList.remove('d-none');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa...';
    alertDiv.classList.add('d-none');

    fetch("{{ route('refund.lookupOrder') }}?order_number=" + encodeURIComponent(orderNumber), {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-search me-1"></i> Cek Pesanan';

        if (data.success && data.order) {
            currentLoadedOrder = data.order;
            document.getElementById('displayOrderNumber').innerText = data.order.order_number;
            document.getElementById('displayOrderStatus').innerText = data.order.status_label;
            document.getElementById('displayOrderTotal').innerText = data.order.formatted_total;
            document.getElementById('refundAmountInput').value = data.order.total_amount;
            document.getElementById('refundAmountInput').max = data.order.total_amount;

            if (!document.getElementById('customerNameInput').value) {
                document.getElementById('customerNameInput').value = data.order.customer_name;
            }
            if (!document.getElementById('customerEmailInput').value) {
                document.getElementById('customerEmailInput').value = data.order.customer_email;
            }
            if (!document.getElementById('customerPhoneInput').value) {
                document.getElementById('customerPhoneInput').value = data.order.customer_phone;
            }

            let itemsHtml = '';
            data.order.items.forEach(item => {
                itemsHtml += `
                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-2 border fs-8">
                        <div class="form-check d-flex align-items-center gap-2 m-0">
                            <input class="form-check-input item-select-checkbox" type="checkbox" name="selected_items[]" value="${item.id}" id="chk_item_${item.id}" data-price="${item.price}" data-max-qty="${item.quantity}" checked onchange="calculateRefundTotal()">
                            <label class="form-check-label text-dark fw-semibold" for="chk_item_${item.id}">
                                ${item.product_name} ${item.variant_name ? '(' + item.variant_name + ')' : ''}
                            </label>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted fs-9">${item.quantity}x @ ${item.formatted_price}</span>
                            <span class="fw-bold text-dark">${item.formatted_subtotal}</span>
                        </div>
                    </div>
                `;
            });
            document.getElementById('orderItemsList').innerHTML = itemsHtml;
            container.classList.remove('d-none');

            alertDiv.className = 'alert alert-success border-0 rounded-3 fs-8 py-2 mt-2';
            alertDiv.innerHTML = '<i class="bi bi-check-circle me-1"></i> Pesanan terverifikasi valid dan siap diajukan pengembalian dana.';
            alertDiv.classList.remove('d-none');
        } else {
            alertDiv.className = 'alert alert-danger border-0 rounded-3 fs-8 py-2 mt-2';
            alertDiv.innerHTML = '<i class="bi bi-x-circle me-1"></i> ' + (data.message || 'Pesanan tidak ditemukan.');
            alertDiv.classList.remove('d-none');
            container.classList.add('d-none');
            currentLoadedOrder = null;
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-search me-1"></i> Cek Pesanan';
        alertDiv.className = 'alert alert-danger border-0 rounded-3 fs-8 py-2 mt-2';
        alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Gagal menghubungkan ke server verifikasi pesanan.';
        alertDiv.classList.remove('d-none');
    });
}

function handleTypeChange(radio) {
    document.querySelectorAll('.type-option-card').forEach(c => c.classList.remove('active'));
    radio.closest('.type-option-card').classList.add('active');

    const checkboxes = document.querySelectorAll('.item-select-checkbox');
    if (radio.value === 'full') {
        checkboxes.forEach(c => c.checked = true);
        if (currentLoadedOrder) {
            document.getElementById('refundAmountInput').value = currentLoadedOrder.total_amount;
        }
    } else {
        calculateRefundTotal();
    }
}

function calculateRefundTotal() {
    const selectedType = document.querySelector('input[name="refund_type"]:checked').value;
    if (selectedType === 'full' && currentLoadedOrder) {
        document.getElementById('refundAmountInput').value = currentLoadedOrder.total_amount;
        return;
    }

    let sum = 0;
    document.querySelectorAll('.item-select-checkbox:checked').forEach(chk => {
        const price = parseFloat(chk.getAttribute('data-price') || 0);
        const qty = parseInt(chk.getAttribute('data-max-qty') || 1);
        sum += price * qty;
    });

    if (sum > 0) {
        document.getElementById('refundAmountInput').value = sum;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const prefillOrder = "{{ $prefilledOrder->order_number ?? '' }}";
    if (prefillOrder && !currentLoadedOrder) {
        performOrderLookup();
    }
});
</script>
@endpush
