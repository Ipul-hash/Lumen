@extends('admin.layouts.app')

@section('title', 'Katalog Pewarna Rambut')
@section('breadcrumb', 'Produk & Varian')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Katalog Pewarna Rambut & Perawatan
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Katalog</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Daftar Produk & Varian Warna</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm fw-bold btn-primary">
                <i class="ki-duotone ki-plus fs-5 me-1"></i>
                Tambah Produk Pewarna
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="card card-flush mb-5">
            <div class="card-body py-4">
                <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" name="search" class="form-control form-control-solid ps-12" placeholder="Cari nama pewarna rambut / SKU..." value="{{ request('search') }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select form-select-solid">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        @if(request()->hasAny(['search', 'category_id']))
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                <th class="min-w-200px">Nama Produk</th>
                                <th class="min-w-120px">Kategori</th>
                                <th class="min-w-100px">Harga Dasar</th>
                                <th class="min-w-180px">Varian & Warna (Swatches)</th>
                                <th class="min-w-100px">Total Stok</th>
                                <th class="min-w-90px">Berat (Kirim)</th>
                                <th class="min-w-90px">Status</th>
                                <th class="min-w-100px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold fs-6">{{ $product->name }}</span>
                                        <span class="text-muted fs-8">{{ Str::limit($product->summary, 45) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-info fw-bold fs-8">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-900 fw-bold fs-7">
                                        {{ $product->formatted_price }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        @foreach($product->variants as $variant)
                                            @if($variant->color_code)
                                                <span class="w-18px h-18px rounded-circle d-inline-block border border-2 border-white shadow-sm" style="background-color: {{ $variant->color_code }};" title="{{ $variant->color_name }} - {{ $variant->name }}"></span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <span class="text-muted fs-8">{{ $product->variants->count() }} pilihan varian</span>
                                </td>
                                <td>
                                    @php $stock = $product->total_stock; @endphp
                                    @if($stock > 10)
                                        <span class="badge badge-light-success fw-bold fs-7">{{ $stock }} pcs</span>
                                    @elseif($stock > 0)
                                        <span class="badge badge-light-warning fw-bold fs-7">{{ $stock }} pcs</span>
                                    @else
                                        <span class="badge badge-light-danger fw-bold fs-7">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-gray-700 fs-7">{{ $product->weight }} gram</span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger fw-bold fs-8">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-icon btn-light-primary me-1" title="Edit Produk">
                                        <i class="ki-duotone ki-pencil fs-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus Produk" data-confirm="Hapus produk '{{ $product->name }}' beserta seluruh variannya?" data-confirm-title="Hapus Produk">
                                            <i class="ki-duotone ki-trash fs-5">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-6">Tidak ada produk ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
