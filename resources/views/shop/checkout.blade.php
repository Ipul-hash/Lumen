@extends('shop.layouts.app')

@section('title', 'Checkout Pesanan | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-light py-3 border-bottom">
    <div class="container-fluid px-lg-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-8 text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-dark text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid px-lg-5 py-5">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="d-flex flex-column gap-5">
                    <div class="p-4 border bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge bg-dark text-white rounded-circle p-2" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                            <h5 class="font-serif fw-bold text-dark m-0">Informasi Kontak Pemesan</h5>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" name="customer_name" class="form-control rounded-0" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="Contoh: Jessica Olivia" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Email Konfirmasi</label>
                                <input type="email" name="customer_email" class="form-control rounded-0" value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="nama@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Nomor WhatsApp / HP</label>
                                <input type="tel" name="customer_phone" class="form-control rounded-0" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="0812xxxxxxxx" required>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge bg-dark text-white rounded-circle p-2" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                            <h5 class="font-serif fw-bold text-dark m-0">Alamat Tujuan Pengiriman</h5>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Kecamatan & Kota Tujuan (Koneksi KiriminAja)</label>
                                <select id="destinationDistrictSelect" class="form-select rounded-0" required>
                                    @foreach($destinations as $dest)
                                        <option value="{{ $dest['id'] }}" data-full="{{ $dest['name'] }}" {{ $dest['id'] == 2108 ? 'selected' : '' }}>
                                            {{ $dest['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="district_id" id="hiddenDistrictId" value="2108">
                                <input type="hidden" name="district_name" id="hiddenDistrictName" value="Tebet">
                                <input type="hidden" name="city_name" id="hiddenCityName" value="Kota Jakarta Selatan">
                                <input type="hidden" name="province_name" id="hiddenProvinceName" value="DKI Jakarta">
                            </div>

                            <div class="col-12">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Alamat Lengkap</label>
                                <textarea name="address_line" class="form-control rounded-0" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Patokan Tempat" required>{{ old('address_line') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Kode Pos</label>
                                <input type="text" name="postal_code" id="inputPostalCode" class="form-control rounded-0" value="{{ old('postal_code', '12810') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-8 text-uppercase fw-bold text-muted">Catatan Pesanan (Opsional)</label>
                                <input type="text" name="notes" class="form-control rounded-0" placeholder="Pesan untuk kurir atau penjual">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark text-white rounded-circle p-2" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;">3</span>
                                <h5 class="font-serif fw-bold text-dark m-0">Layanan Ekspedisi KiriminAja</h5>
                            </div>
                            <span class="fs-8 text-muted">Total Berat: <strong id="checkoutWeightBadge">{{ $cart->total_weight }} gram</strong></span>
                        </div>

                        <div id="shippingLoadingNotice" class="text-center py-4" style="display:none;">
                            <div class="spinner-border spinner-border-sm text-dark me-2"></div>
                            <span class="fs-7 text-muted">Menghitung tarif kurir otomatis via KiriminAja...</span>
                        </div>

                        <div id="shippingRatesContainer" class="d-flex flex-column gap-2">
                        </div>

                        <input type="hidden" name="courier_code" id="inputCourierCode" required>
                        <input type="hidden" name="courier_name" id="inputCourierName" required>
                        <input type="hidden" name="service_type" id="inputServiceType" required>
                        <input type="hidden" name="shipping_cost" id="inputShippingCost" value="0" required>
                    </div>

                    <div class="p-4 border bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge bg-dark text-white rounded-circle p-2" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;">4</span>
                            <h5 class="font-serif fw-bold text-dark m-0">Metode Pembayaran</h5>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <label class="p-3 border d-flex align-items-start gap-3 cursor-pointer payment-method-card active">
                                <input type="radio" name="payment_type" value="qris" class="mt-1" checked>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold fs-7 text-dark text-uppercase">QRIS (Gopay / OVO / Dana / ShopeePay / BCA)</span>
                                        <span class="badge bg-dark text-white fs-8">Otomatis Terverifikasi</span>
                                    </div>
                                    <p class="fs-8 text-muted mt-1 mb-0">Bayar instan dengan memindai kode QRIS melalui e-wallet atau aplikasi mobile banking favorit Anda.</p>
                                </div>
                            </label>

                            <label class="p-3 border d-flex align-items-start gap-3 cursor-pointer payment-method-card">
                                <input type="radio" name="payment_type" value="virtual_account" class="mt-1">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold fs-7 text-dark text-uppercase">Virtual Account Bank</span>
                                        <span class="badge bg-light text-dark border fs-8">24 Jam</span>
                                    </div>
                                    <p class="fs-8 text-muted mt-1 mb-2">Transfer mudah dari rekening bank tanpa perlu upload bukti pembayaran.</p>
                                    
                                    <div id="vaBankSelection" style="display:none;" class="mt-2 pt-2 border-top">
                                        <label class="form-label fs-8 text-uppercase fw-bold text-muted">Pilih Bank Virtual Account:</label>
                                        <div class="row g-2">
                                            <div class="col-6 col-sm-3">
                                                <label class="btn btn-outline-dark btn-sm rounded-0 w-100 p-2 text-center active">
                                                    <input type="radio" name="bank" value="bca" class="btn-check" checked>
                                                    <strong>BCA</strong>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <label class="btn btn-outline-dark btn-sm rounded-0 w-100 p-2 text-center">
                                                    <input type="radio" name="bank" value="mandiri" class="btn-check">
                                                    <strong>Mandiri</strong>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <label class="btn btn-outline-dark btn-sm rounded-0 w-100 p-2 text-center">
                                                    <input type="radio" name="bank" value="bni" class="btn-check">
                                                    <strong>BNI</strong>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <label class="btn btn-outline-dark btn-sm rounded-0 w-100 p-2 text-center">
                                                    <input type="radio" name="bank" value="bri" class="btn-check">
                                                    <strong>BRI</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 border bg-white sticky-top" style="top: 100px;">
                    <h5 class="font-serif fw-bold text-dark mb-3 pb-2 border-bottom">Ringkasan Pesanan</h5>

                    <div class="d-flex flex-column gap-3 mb-4 overflow-auto" style="max-height: 280px;">
                        @foreach($cart->items as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant->product;
                        @endphp
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                @if($variant->color_code)
                                    <span class="swatch-circle" style="background-color: {{ $variant->color_code }};"></span>
                                @endif
                                <div>
                                    <h6 class="fs-8 fw-bold mb-0 text-dark">{{ $product->name }}</h6>
                                    <span class="fs-8 text-muted">{{ $variant->name }} &times; {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <span class="fs-7 fw-semibold text-dark">{{ $item->formatted_subtotal }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3 d-flex flex-column gap-2 fs-7">
                        <div class="d-flex justify-content-between text-muted">
                            <span>Subtotal Produk</span>
                            <span class="text-dark fw-semibold" id="summarySubtotal">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($cart->discount_amount > 0)
                        <div class="d-flex justify-content-between text-success">
                            <span>Diskon Voucher ({{ $cart->coupon_code }})</span>
                            <span class="fw-semibold">-Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between text-muted">
                            <span>Ongkos Kirim KiriminAja</span>
                            <span class="text-dark fw-semibold" id="summaryShippingCost">Pilih Ekspedisi</span>
                        </div>

                        <div class="d-flex justify-content-between text-dark fw-bold fs-5 border-top pt-3 mt-2">
                            <span>Total Tagihan</span>
                            <span id="summaryTotalAmount">Rp {{ number_format($cart->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand-dark py-3" id="btnSubmitOrder">
                            Konfirmasi & Bayar Sekarang
                        </button>
                    </div>

                    <div class="mt-3 text-center fs-8 text-muted">
                        <i class="bi bi-lock-fill me-1"></i> Data dan transaksi terenkripsi aman
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const baseSubtotal = {{ $cart->subtotal - $cart->discount_amount }};

function parseDestination(text) {
    const parts = text.split(',').map(s => s.trim());
    return {
        district: parts[0] || '',
        city: parts[1] || '',
        province: parts[2] || ''
    };
}

function fetchShippingRates(districtId) {
    const loading = document.getElementById('shippingLoadingNotice');
    const container = document.getElementById('shippingRatesContainer');

    loading.style.display = 'block';
    container.innerHTML = '';

    fetch("{{ route('checkout.shippingRates') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ destination_district_id: districtId })
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';

        if (data.success && data.rates && data.rates.length > 0) {
            data.rates.forEach((rate, idx) => {
                const isFirst = idx === 0;
                const card = document.createElement('label');
                card.className = `p-3 border d-flex align-items-center justify-content-between cursor-pointer shipping-option-card ${isFirst ? 'border-dark bg-light' : ''}`;
                card.innerHTML = `
                    <div class="d-flex align-items-center gap-3">
                        <input type="radio" name="shipping_rate_choice" class="shipping-radio" 
                            data-courier-code="${rate.courier_code}" 
                            data-courier-name="${rate.courier_name}" 
                            data-service-type="${rate.service_code}" 
                            data-cost="${rate.cost}" 
                            data-formatted-cost="${rate.formatted_cost}" 
                            ${isFirst ? 'checked' : ''}>
                        <div>
                            <div class="fw-bold fs-7 text-dark">${rate.courier_name} - ${rate.service_name}</div>
                            <div class="fs-8 text-muted">Estimasi sampai: ${rate.etd} Hari</div>
                        </div>
                    </div>
                    <div class="fw-bold fs-6 text-dark">${rate.formatted_cost}</div>
                `;
                container.appendChild(card);

                if (isFirst) {
                    selectShipping(rate.courier_code, rate.courier_name, rate.service_code, rate.cost, rate.formatted_cost);
                }
            });

            document.querySelectorAll('.shipping-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.shipping-option-card').forEach(c => c.classList.remove('border-dark', 'bg-light'));
                    this.closest('.shipping-option-card').classList.add('border-dark', 'bg-light');
                    selectShipping(
                        this.dataset.courierCode,
                        this.dataset.courierName,
                        this.dataset.serviceType,
                        parseFloat(this.dataset.cost),
                        this.dataset.formattedCost
                    );
                });
            });
        }
    })
    .catch(() => {
        loading.style.display = 'none';
        window.showToast('error', 'Gagal memuat ongkos kirim KiriminAja');
    });
}

function selectShipping(code, name, service, cost, formattedCost) {
    document.getElementById('inputCourierCode').value = code;
    document.getElementById('inputCourierName').value = name;
    document.getElementById('inputServiceType').value = service;
    document.getElementById('inputShippingCost').value = cost;

    document.getElementById('summaryShippingCost').innerText = formattedCost;
    const finalTotal = baseSubtotal + cost;
    document.getElementById('summaryTotalAmount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalTotal);
}

document.getElementById('destinationDistrictSelect')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const fullText = selectedOption.dataset.full;
    const districtId = this.value;
    const parsed = parseDestination(fullText);

    document.getElementById('hiddenDistrictId').value = districtId;
    document.getElementById('hiddenDistrictName').value = parsed.district;
    document.getElementById('hiddenCityName').value = parsed.city;
    document.getElementById('hiddenProvinceName').value = parsed.province;

    fetchShippingRates(districtId);
});

document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const vaSelection = document.getElementById('vaBankSelection');
        if (this.value === 'virtual_account') {
            vaSelection.style.display = 'block';
        } else {
            vaSelection.style.display = 'none';
        }
    });
});

document.querySelectorAll('input[name="bank"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="bank"]').forEach(r => r.closest('label').classList.remove('active'));
        if (this.checked) this.closest('label').classList.add('active');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const destSelect = document.getElementById('destinationDistrictSelect');
    if (destSelect) {
        fetchShippingRates(destSelect.value);
    }
});
</script>
@endpush
