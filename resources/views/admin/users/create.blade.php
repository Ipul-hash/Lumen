@extends('admin.layouts.app')

@section('page-title', 'Tambah Pengguna Baru')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-muted">Akses & Keamanan</li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.users.index') }}" class="text-muted text-hover-primary">Kelola User</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-gray-900">Tambah User</li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column gap-6">

    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-center p-4 rounded-3 shadow-sm">
        <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
        <div>
            <div class="fw-bold fs-7 mb-1">Terdapat kesalahan pada isian formulir:</div>
            <ul class="mb-0 ps-4 fs-8">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-6">
            <div class="col-lg-8">
                <div class="card border">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="fw-bolder fs-5 text-gray-900">Informasi Akun</span>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="row g-5">
                            <div class="col-12">
                                <label class="required form-label fs-7 fw-bold">Nama Lengkap</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       class="form-control form-control-solid fs-7 @error('name') is-invalid @enderror" 
                                       placeholder="cth: Ahmad Fauzi" 
                                       required />
                                @error('name')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="required form-label fs-7 fw-bold">Alamat Email</label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="form-control form-control-solid fs-7 @error('email') is-invalid @enderror" 
                                       placeholder="cth: fauzi@lumenhair.id" 
                                       required />
                                @error('email')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-bold">Nomor WhatsApp / HP</label>
                                <input type="text" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       class="form-control form-control-solid fs-7 @error('phone') is-invalid @enderror" 
                                       placeholder="cth: 081234567890" />
                                @error('phone')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="required form-label fs-7 fw-bold">Kata Sandi</label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control form-control-solid fs-7 @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter" 
                                       required />
                                @error('password')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="required form-label fs-7 fw-bold">Konfirmasi Kata Sandi</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       class="form-control form-control-solid fs-7" 
                                       placeholder="Ulangi kata sandi" 
                                       required />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3 py-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">Batal</a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Simpan Pengguna Baru
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border mb-6">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="fw-bolder fs-5 text-gray-900">Peran & Hak Akses (Role)</span>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="mb-4">
                            <label class="required form-label fs-7 fw-bold mb-3">Pilih Hak Akses Akun</label>
                            
                            <div class="d-flex flex-column gap-3">
                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role') === 'admin' || !old('role') ? 'border-primary' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="admin" {{ old('role') === 'admin' || !old('role') ? 'checked' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-gray-900">Admin Biasa</span>
                                            <span class="badge badge-light-primary fw-bold fs-9 ms-2">Operasional</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Akses penuh transaksi pesanan, produk, katalog, ekspedisi KiriminAja & persetujuan refund. <strong>Tidak bisa</strong> membuat atau menghapus user.
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role') === 'superadmin' ? 'border-danger' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="superadmin" {{ old('role') === 'superadmin' ? 'checked' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-danger">Superadmin</span>
                                            <span class="badge badge-light-danger fw-bold fs-9 ms-2">Full Control</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Hak akses tertinggi seluruh sistem termasuk menambah, mengubah, dan menghapus akun administrator dan pelanggan.
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role') === 'customer' ? 'border-secondary' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="customer" {{ old('role') === 'customer' ? 'checked' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-gray-800">Pelanggan Toko</span>
                                            <span class="badge badge-light-secondary fw-bold fs-9 ms-2">Customer</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Hanya memiliki hak akses transaksi di front-store pelanggan, tidak dapat mengakses portal administrasi.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <div class="text-danger fs-8 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card bg-light-primary border-primary border border-dashed">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ki-duotone ki-shield-tick text-primary fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                            <span class="fw-bolder fs-7 text-gray-900">Keamanan Akses Akun</span>
                        </div>
                        <p class="fs-8 text-gray-700 mb-0">
                            Pastikan kata sandi yang dibuat unik dan aman. Pengguna dapat langsung masuk ke portal dengan email dan kata sandi yang Anda tentukan di sini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
