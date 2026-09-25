@extends('admin.layouts.app')

@section('title', 'Pengaturan Logistik & Payment Gateway')
@section('page-title', 'Pengaturan Logistik & Pembayaran')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Beranda</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.shipments.index') }}" class="text-muted text-hover-primary">Logistik</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-dark">Pengaturan API & Ekspedisi</li>
</ul>
@endsection

@section('content')
<form action="{{ route('admin.shipments.updateSettings') }}" method="POST" id="settingsForm">
    @csrf
    @method('PUT')

    <div class="row g-7 mb-7">
        <div class="col-lg-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Midtrans Payment Gateway</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Kredensial Snap, Core API QRIS & Virtual Account</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-4">
                        <i class="ki-duotone ki-credit-cart fs-2tx text-primary me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h5 class="text-gray-900 fw-bold m-0">Terhubung Otomatis ke Midtrans</h5>
                                <div class="fs-7 text-gray-700">Mendukung Snap Popup, Direct QRIS, dan Bank Transfer Virtual Account dengan verifikasi Signature SHA-512.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Mode Environment Midtrans</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column p-4 w-100 {{ ($settings['midtrans_is_production'] ?? '0') == '0' ? 'active' : '' }}">
                                    <input type="radio" name="midtrans_is_production" value="0" class="btn-check" {{ ($settings['midtrans_is_production'] ?? '0') == '0' ? 'checked' : '' }}>
                                    <span class="fw-bold fs-6">Sandbox (Testing)</span>
                                    <span class="fs-7 text-muted mt-1">Uji coba simulasi pembayaran tanpa uang riil</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex flex-column p-4 w-100 {{ ($settings['midtrans_is_production'] ?? '0') == '1' ? 'active' : '' }}">
                                    <input type="radio" name="midtrans_is_production" value="1" class="btn-check" {{ ($settings['midtrans_is_production'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <span class="fw-bold fs-6">Production (Live)</span>
                                    <span class="fs-7 text-muted mt-1">Transaksi uang riil dari rekening pembeli</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">Merchant ID</label>
                        <input type="text" name="midtrans_merchant_id" class="form-control form-control-solid" value="{{ old('midtrans_merchant_id', $settings['midtrans_merchant_id']) }}" placeholder="Contoh: M966763625">
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Client Key (Frontend)</label>
                        <input type="text" name="midtrans_client_key" class="form-control form-control-solid" value="{{ old('midtrans_client_key', $settings['midtrans_client_key']) }}" placeholder="Mid-client-xxxxxxxxxxxx" required>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Server Key (Backend)</label>
                        <div class="input-group input-group-solid">
                            <input type="password" name="midtrans_server_key" id="serverKeyInput" class="form-control form-control-solid" value="{{ old('midtrans_server_key', $settings['midtrans_server_key']) }}" placeholder="Mid-server-xxxxxxxxxxxx" required>
                            <button class="btn btn-secondary" type="button" id="toggleServerKey">
                                <i class="ki-duotone ki-eye fs-2" id="toggleServerIcon">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Payment Notification URL (Webhook)</label>
                        <div class="input-group input-group-solid">
                            <input type="text" class="form-control form-control-solid bg-light text-muted" value="{{ url('/midtrans/webhook') }}" readonly>
                            <button class="btn btn-light-primary" type="button" onclick="navigator.clipboard.writeText('{{ url('/midtrans/webhook') }}'); window.showToast('success', 'URL Webhook berhasil disalin!');">
                                Salin URL
                            </button>
                        </div>
                        <div class="fs-8 text-muted mt-1">Tempelkan URL ini di Midtrans Dashboard > Settings > Configuration > Payment Notification URL.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">API Gateway KiriminAja</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Kredensial integrasi resi otomatis & auto pickup</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-6 p-4">
                        <i class="ki-duotone ki-shield-tick fs-2tx text-success me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h5 class="text-gray-900 fw-bold m-0">Auto-Generate Waybill (AWB) Siap Digunakan</h5>
                                <div class="fs-7 text-gray-700">Sistem terintegrasi otomatis untuk perhitungan tarif multi-kurir, penarikan nomor resi, dan cetak label thermal A6.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Mode Operasional API</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column p-4 w-100 {{ ($settings['kiriminaja_mode'] ?? 'sandbox') === 'sandbox' ? 'active' : '' }}">
                                    <input type="radio" name="kiriminaja_mode" value="sandbox" class="btn-check" {{ ($settings['kiriminaja_mode'] ?? 'sandbox') === 'sandbox' ? 'checked' : '' }}>
                                    <span class="fw-bold fs-6">Sandbox (Simulasi)</span>
                                    <span class="fs-7 text-muted mt-1">Uji coba tanpa pickup fisik kurir sesungguhnya</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex flex-column p-4 w-100 {{ ($settings['kiriminaja_mode'] ?? '') === 'production' ? 'active' : '' }}">
                                    <input type="radio" name="kiriminaja_mode" value="production" class="btn-check" {{ ($settings['kiriminaja_mode'] ?? '') === 'production' ? 'checked' : '' }}>
                                    <span class="fw-bold fs-6">Production (Live)</span>
                                    <span class="fs-7 text-muted mt-1">Kurir asli menjemput paket ke gudang</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">KiriminAja API Token / Secret</label>
                        <div class="input-group input-group-solid">
                            <input type="password" name="kiriminaja_api_key" id="apiKeyInput" class="form-control form-control-solid" value="{{ $settings['kiriminaja_api_key'] ?? '' }}" placeholder="Masukkan API Key KiriminAja Anda">
                            <button class="btn btn-secondary" type="button" id="toggleApiKey">
                                <i class="ki-duotone ki-eye fs-2" id="toggleIcon">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </button>
                        </div>
                        <div class="text-muted fs-8 mt-1">Format token sandbox KiriminAja (v4.local.xxxx).</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">KiriminAja Security PIN</label>
                        <input type="text" name="kiriminaja_pin" class="form-control form-control-solid" value="{{ $settings['kiriminaja_pin'] ?? '123456' }}" placeholder="Contoh: 123456">
                        <div class="text-muted fs-8 mt-1">PIN keamanan 6 digit yang terdaftar di akun KiriminAja.</div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="button" class="btn btn-light-primary w-100" id="btnTestKiriminAja">
                            <i class="ki-duotone ki-rocket fs-4 me-2"><span class="path1"></span><span class="path2"></span></i> Uji Koneksi KiriminAja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-7">
        <div class="col-lg-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Informasi Kontak Toko (Pengirim)</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Data yang tercetak pada label pengiriman</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Nama Toko Pengirim</label>
                        <input type="text" name="store_name" class="form-control form-control-solid" value="{{ old('store_name', $settings['store_name']) }}" required>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">Nomor WhatsApp / HP Pengirim</label>
                            <input type="text" name="store_phone" class="form-control form-control-solid" value="{{ old('store_phone', $settings['store_phone']) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">Email Pengirim</label>
                            <input type="email" name="store_email" class="form-control form-control-solid" value="{{ old('store_email', $settings['store_email']) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Alamat Gudang Asal (Pickup Point)</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Titik jemput kurir untuk seluruh pesanan</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Alamat Lengkap Gudang</label>
                        <textarea name="origin_address" class="form-control form-control-solid" rows="3" required>{{ old('origin_address', $settings['origin_address']) }}</textarea>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">Provinsi Asal</label>
                            <input type="text" name="origin_province_name" class="form-control form-control-solid" value="{{ old('origin_province_name', $settings['origin_province_name']) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">Kota / Kabupaten Asal</label>
                            <input type="text" name="origin_city_name" class="form-control form-control-solid" value="{{ old('origin_city_name', $settings['origin_city_name']) }}" required>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">Kecamatan Asal</label>
                            <input type="text" name="origin_district_name" class="form-control form-control-solid" value="{{ old('origin_district_name', $settings['origin_district_name']) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label fw-semibold">ID Kecamatan KiriminAja</label>
                            <input type="number" name="origin_district_id" class="form-control form-control-solid" value="{{ old('origin_district_id', $settings['origin_district_id']) }}" required>
                            <span class="fs-8 text-muted">ID master distrik KiriminAja (Contoh: 2105 = Kebayoran Baru)</span>
                        </div>
                    </div>

                    <div class="mb-7">
                        <label class="required form-label fw-semibold">Kode Pos Asal</label>
                        <input type="text" name="origin_postal_code" class="form-control form-control-solid" value="{{ old('origin_postal_code', $settings['origin_postal_code']) }}" required>
                    </div>

                    <div class="notice d-flex bg-light-info rounded border-info border border-dashed p-4">
                        <i class="ki-duotone ki-information-5 fs-2tx text-info me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h5 class="text-gray-900 fw-bold m-0">Ekspedisi Terhubung</h5>
                                <div class="fs-7 text-gray-700 mt-1">J&T Express, SiCepat Ekspres, JNE Express, Anteraja, Ninja Xpress, ID Express.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6">
                    <button type="submit" class="btn btn-primary px-8">
                        <i class="ki-duotone ki-check fs-2 me-1"></i> Simpan Semua Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('toggleApiKey')?.addEventListener('click', function() {
    const input = document.getElementById('apiKeyInput');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
});

document.getElementById('toggleServerKey')?.addEventListener('click', function() {
    const input = document.getElementById('serverKeyInput');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
});

document.querySelectorAll('input[name="kiriminaja_mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="kiriminaja_mode"]').forEach(r => {
            r.closest('label').classList.remove('active');
        });
        if (this.checked) {
            this.closest('label').classList.add('active');
        }
    });
});

document.querySelectorAll('input[name="midtrans_is_production"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="midtrans_is_production"]').forEach(r => {
            r.closest('label').classList.remove('active');
        });
        if (this.checked) {
            this.closest('label').classList.add('active');
        }
    });
});

const btnTest = document.getElementById('btnTestKiriminAja');
if (btnTest) {
    btnTest.addEventListener('click', function() {
        const origHtml = btnTest.innerHTML;
        btnTest.disabled = true;
        btnTest.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menguji...';
        
        fetch('{{ route('admin.shipments.testConnection') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            btnTest.disabled = false;
            btnTest.innerHTML = origHtml;
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Koneksi Berhasil!',
                    text: data.message || 'API Key KiriminAja berhasil terhubung.',
                    confirmButtonText: 'Selesai'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Terhubung',
                    text: data.message || 'Koneksi ke API KiriminAja gagal.',
                    confirmButtonText: 'Tutup'
                });
            }
        })
        .catch(err => {
            btnTest.disabled = false;
            btnTest.innerHTML = origHtml;
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                text: 'Gagal request ke server lokal: ' + err.message,
                confirmButtonText: 'Tutup'
            });
        });
    });
}
</script>
@endpush
