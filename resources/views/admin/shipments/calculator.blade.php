@extends('admin.layouts.app')

@section('title', 'Kalkulator Ongkir KiriminAja')
@section('page-title', 'Kalkulator Ongkos Kirim')

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
    <li class="breadcrumb-item text-dark">Kalkulator Ongkir</li>
</ul>
@endsection

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Cek Tarif Pengiriman</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Koneksi Real-time API KiriminAja</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                <form id="rateCalculatorForm">
                    @csrf
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-4">
                        <i class="ki-duotone ki-delivery-3 fs-2tx text-primary me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h5 class="text-gray-900 fw-bold m-0">Gudang Asal (Origin)</h5>
                                <div class="fs-7 text-gray-700">Kecamatan {{ $originDistrict }}, {{ $originCity }}</div>
                                <span class="badge badge-light-primary fw-bold mt-1">ID Kecamatan: {{ $originDistrictId }}</span>
                            </div>
                            <a href="{{ route('admin.shipments.settings') }}" class="btn btn-sm btn-light-primary">Ubah</a>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Kecamatan / Kota Tujuan</label>
                        <select name="destination_district_id" id="destinationDistrict" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kecamatan / Kota Tujuan">
                            <option value="2108" selected>Tebet, Kota Jakarta Selatan (DKI Jakarta)</option>
                            <option value="2101">Cilandak, Kota Jakarta Selatan (DKI Jakarta)</option>
                            <option value="2110">Gambir, Kota Jakarta Pusat (DKI Jakarta)</option>
                            <option value="2189">Bekasi Barat, Kota Bekasi (Jawa Barat)</option>
                            <option value="2195">Bogor Tengah, Kota Bogor (Jawa Barat)</option>
                            <option value="2210">Coblong, Kota Bandung (Jawa Barat)</option>
                            <option value="2215">Sumur Bandung, Kota Bandung (Jawa Barat)</option>
                            <option value="2305">Banyumanik, Kota Semarang (Jawa Tengah)</option>
                            <option value="2380">Depok, Kab. Sleman (DI Yogyakarta)</option>
                            <option value="2385">Danurejan, Kota Yogyakarta (DI Yogyakarta)</option>
                            <option value="2450">Gubeng, Kota Surabaya (Jawa Timur)</option>
                            <option value="2455">Wonokromo, Kota Surabaya (Jawa Timur)</option>
                            <option value="2600">Denpasar Selatan, Kota Denpasar (Bali)</option>
                            <option value="1200">Medan Kota, Kota Medan (Sumatera Utara)</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label fw-semibold">Berat Paket (Gram)</label>
                        <div class="input-group input-group-solid">
                            <input type="number" name="weight" id="packetWeight" class="form-control form-control-solid" value="500" min="50" step="50" required>
                            <span class="input-group-text">Gram</span>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-xs btn-light set-weight" data-weight="250">250g (1 Cat)</button>
                            <button type="button" class="btn btn-xs btn-light set-weight" data-weight="500">500g (2 Cat + Bleach)</button>
                            <button type="button" class="btn btn-xs btn-light set-weight" data-weight="1000">1000g (Paket Bundling)</button>
                            <button type="button" class="btn btn-xs btn-light set-weight" data-weight="2000">2000g (Grosir/Salon)</button>
                        </div>
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold mb-3">Filter Kurir</label>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column align-items-center justify-content-center p-3 w-100 active">
                                    <input type="checkbox" name="couriers[]" value="jnt" class="btn-check" checked>
                                    <span class="fw-bold fs-7">J&T Express</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column align-items-center justify-content-center p-3 w-100 active">
                                    <input type="checkbox" name="couriers[]" value="sicepat" class="btn-check" checked>
                                    <span class="fw-bold fs-7">SiCepat</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column align-items-center justify-content-center p-3 w-100 active">
                                    <input type="checkbox" name="couriers[]" value="jne" class="btn-check" checked>
                                    <span class="fw-bold fs-7">JNE Express</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnCalculate" class="btn btn-primary w-100">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-calculator fs-2 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Hitung Ongkos Kirim
                        </span>
                        <span class="indicator-progress">
                            Memuat tarif... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Pilihan Layanan & Tarif</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6" id="resultSubtitle">Klik tombol Hitung Ongkir untuk memuat perbandingan tarif</span>
                </h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-success fs-7 fw-bold" id="resultCountBadge" style="display:none;"></span>
                </div>
            </div>
            <div class="card-body pt-5">
                <div id="resultsPlaceholder" class="text-center py-12">
                    <div class="symbol symbol-100px symbol-circle bg-light-primary mb-5">
                        <i class="ki-duotone ki-delivery-24 fs-2tx text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </div>
                    <h4 class="text-gray-800 fw-bold">Belum Ada Perhitungan</h4>
                    <p class="text-gray-500 fs-6 mw-400px mx-auto">Silakan pilih destinasi pengiriman dan tentukan berat paket cat rambut di sebelah kiri, lalu tekan tombol Hitung.</p>
                </div>

                <div id="resultsContainer" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-dashed align-middle gs-4 gy-4">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Kurir</th>
                                    <th>Layanan</th>
                                    <th>Estimasi Waktu</th>
                                    <th class="text-end">Tarif Ongkir</th>
                                </tr>
                            </thead>
                            <tbody id="ratesTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.set-weight').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('packetWeight').value = this.dataset.weight;
    });
});

document.querySelectorAll('input[type="checkbox"][name="couriers[]"]').forEach(cb => {
    cb.addEventListener('change', function() {
        if (this.checked) {
            this.closest('label').classList.add('active');
        } else {
            this.closest('label').classList.remove('active');
        }
    });
});

document.getElementById('btnCalculate').addEventListener('click', function() {
    const btn = this;
    const destSelect = document.getElementById('destinationDistrict');
    const destId = destSelect.value;
    const destText = destSelect.options[destSelect.selectedIndex].text;
    const weight = document.getElementById('packetWeight').value;

    const checkedCouriers = Array.from(document.querySelectorAll('input[name="couriers[]"]:checked')).map(cb => cb.value);

    if (checkedCouriers.length === 0) {
        window.showToast('warning', 'Pilih minimal satu ekspedisi kurir');
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    fetch("{{ route('admin.shipments.calculateRatesAjax') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            destination_district_id: destId,
            weight: weight,
            couriers: checkedCouriers
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.removeAttribute('data-kt-indicator');
        btn.disabled = false;

        if (data.success && data.rates && data.rates.length > 0) {
            const body = document.getElementById('ratesTableBody');
            body.innerHTML = '';

            data.rates.forEach(rate => {
                let badgeClass = 'badge-light-primary';
                if (rate.courier === 'sicepat') badgeClass = 'badge-light-danger';
                if (rate.courier === 'jne') badgeClass = 'badge-light-warning';

                const formattedCost = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(rate.cost);

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge ${badgeClass} fw-bold fs-7 text-uppercase px-3 py-2">${rate.courier}</span>
                    </td>
                    <td>
                        <span class="fw-bold text-gray-800">${rate.service_name}</span>
                        <div class="text-muted fs-8">${rate.service}</div>
                    </td>
                    <td>
                        <span class="badge badge-light-info fw-semibold">
                            <i class="ki-duotone ki-time fs-8 me-1 text-info"><span class="path1"></span><span class="path2"></span></i>
                            ${rate.etd} Hari
                        </span>
                    </td>
                    <td class="text-end">
                        <span class="fw-bolder text-gray-900 fs-5">${formattedCost}</span>
                    </td>
                `;
                body.appendChild(row);
            });

            document.getElementById('resultsPlaceholder').style.display = 'none';
            document.getElementById('resultsContainer').style.display = 'block';
            document.getElementById('resultSubtitle').innerText = `Tujuan: ${destText} (${weight} gram)`;
            const badge = document.getElementById('resultCountBadge');
            badge.innerText = `${data.rates.length} Layanan Tersedia`;
            badge.style.display = 'inline-block';

            window.showToast('success', `Ditemukan ${data.rates.length} pilihan layanan kirim`);
        } else {
            window.showToast('error', 'Gagal memuat tarif pengiriman dari KiriminAja');
        }
    })
    .catch(err => {
        btn.removeAttribute('data-kt-indicator');
        btn.disabled = false;
        window.showToast('error', 'Terjadi kesalahan jaringan saat menghitung tarif');
    });
});
</script>
@endpush
