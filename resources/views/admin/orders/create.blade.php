@extends('admin.layouts.app')

@section('title', 'Buat Pesanan Manual')
@section('breadcrumb', 'Input Pesanan Manual')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Input Pesanan Manual (WhatsApp / DM)
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.orders.index') }}" class="text-muted text-hover-primary">Pesanan Masuk</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Buat Pesanan Baru</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm fw-bold btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        @if($errors->any())
        <div class="alert alert-danger p-5 mb-5">
            <div class="fw-bold mb-2">Periksa kembali input data:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            <div class="row g-5 g-xl-10">
                <div class="col-xl-8">
                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Data Pelanggan & Alamat Pengiriman</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="required fs-7 fw-semibold mb-1">Nama Lengkap Pembeli</label>
                                    <input type="text" name="customer_name" class="form-control form-control-solid" placeholder="Contoh: Rian Anggara" value="{{ old('customer_name') }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="required fs-7 fw-semibold mb-1">Nomor WhatsApp / HP</label>
                                    <input type="text" name="customer_phone" class="form-control form-control-solid" placeholder="0812xxxxxxxx" value="{{ old('customer_phone') }}" required />
                                </div>
                                <div class="col-12">
                                    <label class="required fs-7 fw-semibold mb-1">Email Pembeli</label>
                                    <input type="email" name="customer_email" class="form-control form-control-solid" placeholder="email@gmail.com" value="{{ old('customer_email') }}" required />
                                </div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="required fs-7 fw-semibold mb-1">Alamat Lengkap (Jalan, No Rumah, RT/RW)</label>
                                <textarea name="address_line" rows="2" class="form-control form-control-solid" placeholder="Jl. Anggrek No. 12, RT 01/RW 03..." required>{{ old('address_line') }}</textarea>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-3">
                                    <label class="required fs-8 fw-semibold mb-1">Provinsi</label>
                                    <input type="text" name="province_name" class="form-control form-control-sm form-control-solid" placeholder="Jawa Barat" value="{{ old('province_name', 'Jawa Barat') }}" required />
                                </div>
                                <div class="col-md-3">
                                    <label class="required fs-8 fw-semibold mb-1">Kota / Kabupaten</label>
                                    <input type="text" name="city_name" class="form-control form-control-sm form-control-solid" placeholder="Bandung" value="{{ old('city_name', 'Kota Bandung') }}" required />
                                </div>
                                <div class="col-md-3">
                                    <label class="required fs-8 fw-semibold mb-1">Kecamatan</label>
                                    <input type="text" name="district_name" class="form-control form-control-sm form-control-solid" placeholder="Coblong" value="{{ old('district_name', 'Coblong') }}" required />
                                </div>
                                <div class="col-md-3">
                                    <label class="required fs-8 fw-semibold mb-1">Kode Pos</label>
                                    <input type="text" name="postal_code" class="form-control form-control-sm form-control-solid" placeholder="40132" value="{{ old('postal_code', '40132') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7 d-flex justify-content-between align-items-center">
                            <h3 class="card-title fw-bold text-gray-900 m-0">Pilihan Produk Pewarna Rambut</h3>
                            <button type="button" class="btn btn-sm btn-light-primary fw-bold" id="btn-add-item">
                                <i class="ki-duotone ki-plus fs-6 me-1"></i> Tambah Item
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div id="items-container">
                                <div class="item-row border border-gray-300 rounded p-4 mb-3 bg-light-secondary position-relative">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-8">
                                            <label class="required fs-8 fw-semibold mb-1">Pilih Produk & Varian Warna</label>
                                            <select name="items[0][variant_id]" class="form-select form-select-sm form-select-solid variant-select" required>
                                                <option value="">-- Pilih Pewarna Rambut --</option>
                                                @foreach($products as $product)
                                                    <optgroup label="{{ $product->name }}">
                                                        @foreach($product->variants as $variant)
                                                            <option value="{{ $variant->id }}" data-price="{{ (int)$variant->effective_price }}" data-stock="{{ $variant->stock }}">
                                                                {{ $variant->name }} - Rp {{ number_format($variant->effective_price, 0, ',', '.') }} (Stok: {{ $variant->stock }})
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="required fs-8 fw-semibold mb-1">Jumlah (Qty)</label>
                                            <input type="number" name="items[0][quantity]" class="form-control form-control-sm form-control-solid qty-input" value="1" min="1" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush mb-5 border-start border-4 border-primary">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Pengiriman KiriminAja</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="alert alert-light-primary p-3 mb-4">
                                <span class="fs-8 text-gray-700 d-block">Asal Gudang:</span>
                                <span class="fs-7 fw-bold text-gray-900">{{ $originDistrict }}, {{ $originCity }}</span>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="required fs-7 fw-semibold mb-1">Pilihan Ekspedisi</label>
                                <select name="courier_code" class="form-select form-select-solid" required>
                                    <option value="jnt">J&T Express</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="jne">JNE</option>
                                </select>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="required fs-7 fw-semibold mb-1">Jenis Layanan</label>
                                <input type="text" name="service_type" class="form-control form-control-solid" value="EZ / Reguler" required />
                            </div>

                            <div class="fv-row mb-4">
                                <label class="required fs-7 fw-semibold mb-1">Ongkos Kirim (Rp)</label>
                                <input type="number" name="shipping_cost" class="form-control form-control-solid" value="18000" min="0" required />
                            </div>
                        </div>
                    </div>

                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Metode Pembayaran</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="fv-row mb-4">
                                <label class="required fs-7 fw-semibold mb-1">Pilih Metode</label>
                                <select name="payment_type" id="payment_type" class="form-select form-select-solid" required>
                                    <option value="qris">QRIS (Semua E-Wallet / Mobile Banking)</option>
                                    <option value="virtual_account">Virtual Account</option>
                                </select>
                            </div>

                            <div class="fv-row mb-4" id="bank_select_wrap" style="display: none;">
                                <label class="fs-7 fw-semibold mb-1">Pilih Bank VA</label>
                                <select name="bank" class="form-select form-select-solid">
                                    <option value="bca">BCA Virtual Account</option>
                                    <option value="mandiri">Mandiri Virtual Account</option>
                                    <option value="bni">BNI Virtual Account</option>
                                    <option value="bri">BRI Virtual Account</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ki-duotone ki-check fs-2 me-1"></i> Simpan & Buat Pesanan
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentType = document.getElementById('payment_type');
    const bankWrap = document.getElementById('bank_select_wrap');

    paymentType.addEventListener('change', function () {
        if (this.value === 'virtual_account') {
            bankWrap.style.display = 'block';
        } else {
            bankWrap.style.display = 'none';
        }
    });

    let itemIndex = 1;
    const btnAdd = document.getElementById('btn-add-item');
    const container = document.getElementById('items-container');

    btnAdd.addEventListener('click', function () {
        const firstRow = container.querySelector('.item-row');
        const clone = firstRow.cloneNode(true);

        const select = clone.querySelector('select');
        select.name = `items[${itemIndex}][variant_id]`;
        select.selectedIndex = 0;

        const input = clone.querySelector('input');
        input.name = `items[${itemIndex}][quantity]`;
        input.value = 1;

        if (!clone.querySelector('.btn-remove-row')) {
            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.className = 'btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-2 btn-remove-row';
            btnRemove.innerHTML = '<i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
            clone.appendChild(btnRemove);
        }

        container.appendChild(clone);
        itemIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-row')) {
            const row = e.target.closest('.item-row');
            if (container.querySelectorAll('.item-row').length > 1) {
                row.remove();
            }
        }
    });
});
</script>
@endpush
@endsection
