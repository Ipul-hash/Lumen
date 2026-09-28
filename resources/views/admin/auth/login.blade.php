<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Login Administrator | LUMEN Hair Color Atelier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat" style="background-image: url('{{ asset('assets/media/auth/bg10.jpeg') }}');">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            
            <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative">
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                    <div class="d-flex flex-row-fluid flex-column flex-center text-center p-10 pt-lg-20">
                        <a href="{{ route('shop.index') }}" class="py-9 mb-5 d-inline-flex align-items-center gap-3 text-white text-decoration-none">
                            <div class="w-45px h-45px bg-primary rounded d-flex align-items-center justify-content-center shadow">
                                <i class="ki-duotone ki-color-filter text-white fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <span class="fs-1 fw-bolder text-white ls-2">LUMEN</span>
                        </a>
                        <h1 class="d-none d-lg-block fw-bold text-white fs-2qx pb-5 pb-md-10">
                            Enterprise Admin Portal
                        </h1>
                        <p class="d-none d-lg-block text-white opacity-75 fs-base mw-400px mx-auto">
                            Portal manajemen e-commerce salon, pemrosesan pesanan real-time, ekspedisi KiriminAja, payment gateway Midtrans, dan persetujuan pengembalian dana.
                        </p>
                    </div>
                    <div class="d-none d-lg-block d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url('{{ asset('assets/media/auth/agency.png') }}')"></div>
                </div>
            </div>

            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <div class="w-lg-500px p-10 p-lg-15 mx-auto bg-body rounded-4 shadow-sm border border-gray-200">
                        
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('admin.login.submit') }}" method="POST">
                            @csrf
                            
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3 fs-2x">Masuk ke Dashboard</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Sistem Administrasi LUMEN Hair Color Atelier</div>
                            </div>

                            @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-7 rounded-3">
                                <i class="ki-duotone ki-shield-tick fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fs-7 fw-semibold text-gray-800">{{ session('success') }}</div>
                            </div>
                            @endif

                            @if(session('warning'))
                            <div class="alert alert-warning d-flex align-items-center p-4 mb-7 rounded-3">
                                <i class="ki-duotone ki-information-5 fs-2 text-warning me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="fs-7 fw-semibold text-gray-800">{{ session('warning') }}</div>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center p-4 mb-7 rounded-3">
                                <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
                            </div>
                            @endif

                            <div class="fv-row mb-8">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Alamat Email</label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control form-control-solid fs-7 @error('email') is-invalid @enderror" 
                                       placeholder="admin@lumenhair.id" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus />
                                @error('email')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="fv-row mb-8">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Kata Sandi</label>
                                </div>
                                <input type="password" 
                                       name="password" 
                                       class="form-control form-control-solid fs-7 @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" 
                                       required 
                                       autocomplete="current-password" />
                                @error('password')
                                    <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-stack flex-wrap gap-3 fs-7 fw-semibold mb-8">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="remember" id="kt_login_remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label text-gray-700" for="kt_login_remember">
                                        Ingat sesi saya
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary fw-bolder py-3 fs-7">
                                    <span class="indicator-label">Masuk</span>
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('shop.index') }}" class="text-muted text-hover-primary fs-7 fw-semibold">
                                    <i class="ki-duotone ki-arrow-left fs-6 me-1"><span class="path1"></span><span class="path2"></span></i> Kembali ke Toko LUMEN
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
