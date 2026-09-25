@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('breadcrumb', 'Edit Kategori')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Kategori: {{ $category->name }}
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.categories.index') }}" class="text-muted text-hover-primary">Kategori</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Edit Kategori</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm fw-bold btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-flush">
                <div class="card-header pt-7">
                    <h3 class="card-title fw-bold text-gray-900">Perbarui Informasi Kategori</h3>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-5">
                        <div class="col-md-8">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Nama Kategori</label>
                                <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Slug</label>
                                <input type="text" name="slug" class="form-control form-control-solid @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" />
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Deskripsi</label>
                                <textarea name="description" rows="4" class="form-control form-control-solid">{{ old('description', $category->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Urutan Tampil (Sort Order)</label>
                                <input type="number" name="sort_order" class="form-control form-control-solid" value="{{ old('sort_order', $category->sort_order) }}" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} />
                                    <span class="form-check-label fw-semibold text-gray-700">Kategori Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
