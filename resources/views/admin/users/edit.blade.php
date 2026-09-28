@extends('admin.layouts.app')

@section('page-title', 'Edit Pengguna: ' . $user->name)

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
    <li class="breadcrumb-item text-gray-900">Edit User</li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column gap-6">

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-4 rounded-3 shadow-sm">
        <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
    </div>
    @endif

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

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-6">
            <div class="col-lg-8">
                <div class="card border mb-6">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="fw-bolder fs-5 text-gray-900">Informasi Pengguna</span>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="row g-5">
                            <div class="col-12">
                                <label class="required form-label fs-7 fw-bold">Nama Lengkap</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       class="form-control form-control-solid fs-7 @error('name') is-invalid @enderror" 
                                       placeholder="Nama Lengkap" 
                                       required />
                                @error('name')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="required form-label fs-7 fw-bold">Alamat Email</label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       class="form-control form-control-solid fs-7 @error('email') is-invalid @enderror" 
                                       placeholder="cth: user@lumenhair.id" 
                                       required />
                                @error('email')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-bold">Nomor WhatsApp / HP</label>
                                <input type="text" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       class="form-control form-control-solid fs-7 @error('phone') is-invalid @enderror" 
                                       placeholder="cth: 081234567890" />
                                @error('phone')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="fw-bolder fs-5 text-gray-900">Ubah Kata Sandi (Opsional)</span>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <p class="fs-8 text-muted mb-4">Kosongkan kolom kata sandi di bawah ini apabila Anda tidak berniat mengganti kata sandi akun ini.</p>
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-bold">Kata Sandi Baru</label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control form-control-solid fs-7 @error('password') is-invalid @enderror" 
                                       placeholder="Kosongkan jika tetap" />
                                @error('password')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-bold">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       class="form-control form-control-solid fs-7" 
                                       placeholder="Ulangi kata sandi baru" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3 py-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">Batal</a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Perbarui Data Pengguna
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
                        @if($user->id === auth()->id())
                        <div class="alert alert-warning p-3 rounded-2 fs-8 mb-4">
                            <i class="ki-duotone ki-information-5 text-warning fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Ini adalah akun yang sedang Anda gunakan saat ini.
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="required form-label fs-7 fw-bold mb-3">Tentukan Hak Akses</label>
                            
                            <div class="d-flex flex-column gap-3">
                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role', $user->role) === 'admin' ? 'border-primary' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-gray-900">Admin Biasa</span>
                                            <span class="badge badge-light-primary fw-bold fs-9 ms-2">Operasional</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Akses transaksi, pesanan, refund, ekspedisi KiriminAja. <strong>Tidak bisa</strong> membuat user.
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role', $user->role) === 'superadmin' ? 'border-danger' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'checked' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-danger">Superadmin</span>
                                            <span class="badge badge-light-danger fw-bold fs-9 ms-2">Full Control</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Hak akses tertinggi seluruh sistem termasuk manajemen user dan admin.
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded p-4 d-flex align-items-start cursor-pointer hover-elevate-up bg-state-light {{ old('role', $user->role) === 'customer' ? 'border-secondary' : '' }}">
                                    <input class="form-check-input me-3 mt-1" type="radio" name="role" value="customer" {{ old('role', $user->role) === 'customer' ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }} />
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold fs-7 text-gray-800">Pelanggan Toko</span>
                                            <span class="badge badge-light-secondary fw-bold fs-9 ms-2">Customer</span>
                                        </div>
                                        <div class="fs-8 text-muted">
                                            Hanya akses storefront toko.
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="superadmin">
                            @endif

                            @error('role')
                                <div class="text-danger fs-8 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="fw-bolder fs-6 text-gray-900">Metadata Akun</span>
                        </div>
                    </div>
                    <div class="card-body p-5 fs-8">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">User ID</span>
                            <span class="fw-bold text-gray-800">#{{ $user->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Dibuat Pada</span>
                            <span class="fw-bold text-gray-800">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Terakhir Diperbarui</span>
                            <span class="fw-bold text-gray-800">{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
