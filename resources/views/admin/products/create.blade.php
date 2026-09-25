@extends('admin.layouts.app')

@section('title', 'Tambah Produk Pewarna Rambut')
@section('breadcrumb', 'Tambah Produk')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Tambah Produk Pewarna Rambut
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.products.index') }}" class="text-muted text-hover-primary">Katalog</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Form Tambah Produk</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm fw-bold btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="row g-5 g-xl-10">
                <div class="col-xl-8">
                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Informasi Produk Utama</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Nama Produk</label>
                                <input type="text" name="name" class="form-control form-control-solid" placeholder="Contoh: LUMEN Vivid Color Cream 120ml" value="{{ old('name') }}" required />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Ringkasan Singkat (Summary)</label>
                                <textarea name="summary" rows="2" class="form-control form-control-solid" placeholder="Deskripsi singkat untuk kartu produk...">{{ old('summary') }}</textarea>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Deskripsi Lengkap & Manfaat</label>
                                <textarea name="description" rows="5" class="form-control form-control-solid" placeholder="Penjelasan kandungan vegan, ketahanan warna, dan hasil akhir...">{{ old('description') }}</textarea>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Cara Pakai / Perawatan (Care Instructions)</label>
                                <textarea name="care_instructions" rows="3" class="form-control form-control-solid" placeholder="Petunjuk pengaplikasian, waktu tunggu, dan cara keramas...">{{ old('care_instructions') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7 d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title fw-bold text-gray-900 m-0">Varian Warna & Ukuran</h3>
                                <div class="text-muted fs-8">Tambahkan pilihan warna cat, kode hex (swatch), ukuran, harga, dan stok</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-primary fw-bold" id="btn-add-variant">
                                <i class="ki-duotone ki-plus fs-6 me-1"></i> Tambah Varian
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div id="variants-container">
                                <div class="variant-item border border-gray-300 rounded p-4 mb-4 bg-light-secondary position-relative">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="required fs-8 fw-semibold mb-1">Nama Varian Warna</label>
                                            <input type="text" name="variants[0][name]" class="form-control form-control-sm form-control-solid" placeholder="Contoh: Ash Grey Titanium" value="Ash Grey Titanium" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required fs-8 fw-semibold mb-1">SKU Unik</label>
                                            <input type="text" name="variants[0][sku]" class="form-control form-control-sm form-control-solid" placeholder="LMN-ASH-120" value="LMN-ASH-120" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="fs-8 fw-semibold mb-1">Nama Warna</label>
                                            <input type="text" name="variants[0][color_name]" class="form-control form-control-sm form-control-solid" placeholder="Ash Grey" value="Ash Grey" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="fs-8 fw-semibold mb-1">Warna (Hex)</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" name="variants[0][color_code]" class="form-control form-control-sm form-control-color p-0 border-0" value="#8D99AE" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fs-8 fw-semibold mb-1">Ukuran / Volume</label>
                                            <input type="text" name="variants[0][size]" class="form-control form-control-sm form-control-solid" placeholder="120ml" value="120ml" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required fs-8 fw-semibold mb-1">Harga (Rp)</label>
                                            <input type="number" name="variants[0][price]" class="form-control form-control-sm form-control-solid" placeholder="125000" value="125000" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="fs-8 fw-semibold mb-1">Harga Coret (Promo)</label>
                                            <input type="number" name="variants[0][discount_price]" class="form-control form-control-sm form-control-solid" placeholder="Opsional" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required fs-8 fw-semibold mb-1">Stok Tersedia</label>
                                            <input type="number" name="variants[0][stock]" class="form-control form-control-sm form-control-solid" placeholder="25" value="25" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush mb-5">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Kategori & Status</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Pilih Kategori</label>
                                <select name="category_id" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Harga Dasar (Rp)</label>
                                <input type="number" name="base_price" class="form-control form-control-solid" placeholder="125000" value="{{ old('base_price', 125000) }}" required />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="form-check form-switch form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                    <span class="form-check-label fw-semibold text-gray-700">Produk Aktif di Toko</span>
                                </label>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} />
                                    <span class="form-check-label fw-semibold text-gray-700">Produk Unggulan (Featured)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card card-flush mb-5 border-start border-primary border-4">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-900">Parameter Pengiriman (KiriminAja)</h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Berat Barang (Gram)</label>
                                <input type="number" name="weight" class="form-control form-control-solid" placeholder="Contoh: 200" value="{{ old('weight', 200) }}" required />
                                <span class="text-muted fs-8">Wajib akurat untuk perhitungan ongkir otomatis KiriminAja</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="fs-8 fw-semibold mb-1">Panjang (cm)</label>
                                    <input type="number" step="0.1" name="length" class="form-control form-control-sm form-control-solid" value="{{ old('length', 6) }}" />
                                </div>
                                <div class="col-4">
                                    <label class="fs-8 fw-semibold mb-1">Lebar (cm)</label>
                                    <input type="number" step="0.1" name="width" class="form-control form-control-sm form-control-solid" value="{{ old('width', 6) }}" />
                                </div>
                                <div class="col-4">
                                    <label class="fs-8 fw-semibold mb-1">Tinggi (cm)</label>
                                    <input type="number" step="0.1" name="height" class="form-control form-control-sm form-control-solid" value="{{ old('height', 18) }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ki-duotone ki-check fs-2 me-1"></i> Simpan Produk & Varian
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let variantIndex = 1;
    const container = document.getElementById('variants-container');
    const btnAdd = document.getElementById('btn-add-variant');

    btnAdd.addEventListener('click', function () {
        const html = `
            <div class="variant-item border border-gray-300 rounded p-4 mb-4 bg-light-secondary position-relative">
                <button type="button" class="btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-2 btn-remove-variant">
                    <i class="ki-duotone ki-trash fs-6">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                </button>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="required fs-8 fw-semibold mb-1">Nama Varian Warna</label>
                        <input type="text" name="variants[${variantIndex}][name]" class="form-control form-control-sm form-control-solid" placeholder="Contoh: Rose Gold Pastel" required />
                    </div>
                    <div class="col-md-3">
                        <label class="required fs-8 fw-semibold mb-1">SKU Unik</label>
                        <input type="text" name="variants[${variantIndex}][sku]" class="form-control form-control-sm form-control-solid" placeholder="LMN-VAR-${variantIndex}" required />
                    </div>
                    <div class="col-md-3">
                        <label class="fs-8 fw-semibold mb-1">Nama Warna</label>
                        <input type="text" name="variants[${variantIndex}][color_name]" class="form-control form-control-sm form-control-solid" placeholder="Rose Gold" />
                    </div>
                    <div class="col-md-2">
                        <label class="fs-8 fw-semibold mb-1">Warna (Hex)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="variants[${variantIndex}][color_code]" class="form-control form-control-sm form-control-color p-0 border-0" value="#DDA7A5" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="fs-8 fw-semibold mb-1">Ukuran / Volume</label>
                        <input type="text" name="variants[${variantIndex}][size]" class="form-control form-control-sm form-control-solid" placeholder="120ml" value="120ml" />
                    </div>
                    <div class="col-md-3">
                        <label class="required fs-8 fw-semibold mb-1">Harga (Rp)</label>
                        <input type="number" name="variants[${variantIndex}][price]" class="form-control form-control-sm form-control-solid" placeholder="125000" value="125000" required />
                    </div>
                    <div class="col-md-3">
                        <label class="fs-8 fw-semibold mb-1">Harga Coret (Promo)</label>
                        <input type="number" name="variants[${variantIndex}][discount_price]" class="form-control form-control-sm form-control-solid" placeholder="Opsional" />
                    </div>
                    <div class="col-md-3">
                        <label class="required fs-8 fw-semibold mb-1">Stok Tersedia</label>
                        <input type="number" name="variants[${variantIndex}][stock]" class="form-control form-control-sm form-control-solid" placeholder="20" value="20" required />
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-variant')) {
            const item = e.target.closest('.variant-item');
            if (container.querySelectorAll('.variant-item').length > 1) {
                item.remove();
            } else {
                window.showToast('warning', 'Minimal harus ada 1 varian produk.');
            }
        }
    });
});
</script>
@endpush
@endsection
