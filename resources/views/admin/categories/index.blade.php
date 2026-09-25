@extends('admin.layouts.app')

@section('title', 'Kategori Pewarna Rambut')
@section('breadcrumb', 'Kategori')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Kategori Pewarna Rambut
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Katalog</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Daftar Kategori</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm fw-bold btn-primary">
                <i class="ki-duotone ki-plus fs-5 me-1"></i>
                Tambah Kategori
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="card card-flush">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Semua Kategori Produk</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Kelompok pewarna rambut, bleaching, dan perawatan</span>
                </h3>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-hover table-row-dashed align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 text-uppercase">
                                <th class="min-w-50px">Urutan</th>
                                <th class="min-w-200px">Nama Kategori</th>
                                <th class="min-w-250px">Deskripsi</th>
                                <th class="min-w-100px">Jumlah Produk</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-100px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <span class="badge badge-light fw-bold fs-7">{{ $category->sort_order }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold fs-6">{{ $category->name }}</span>
                                        <span class="text-muted fs-8">slug: {{ $category->slug }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-700 fs-7">{{ $category->description ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold fs-7">{{ $category->products_count }} Produk</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger fw-bold fs-8">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-icon btn-light-primary me-1" title="Edit Kategori">
                                        <i class="ki-duotone ki-pencil fs-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus Kategori" data-confirm="Hapus kategori '{{ $category->name }}'? Produk di dalamnya tidak akan terhapus namun kategori akan dilepas." data-confirm-title="Hapus Kategori">
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
                                <td colspan="6" class="text-center text-muted py-6">Belum ada kategori yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
