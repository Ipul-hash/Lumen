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
<body id="kt_app_body" class="app-blank">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid min-vh-100">
            
            <div class="d-flex flex-column flex-lg-row-auto w-lg-50 p-10 p-lg-15 justify-content-between position-relative" style="background: linear-gradient(165deg, #1e1e2d 0%, #151521 100%) !important;">
                <div class="d-flex flex-column align-items-start pt-lg-8">
                    <a href="{{ route('shop.index') }}" class="d-flex align-items-center gap-3 text-white text-decoration-none mb-10">
                        <div class="w-45px h-45px bg-primary rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                            <i class="ki-duotone ki-color-filter text-white fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2 fw-bolder text-white ls-1">LUMEN</span>
                            <span class="fs-9 text-gray-400 fw-semibold text-uppercase tracking-wider">Hair Color Atelier</span>
                        </div>
                    </a>

                    <h1 class="text-white fw-bolder fs-2qx mb-4">
                        Portal Administrasi & Operasional Salon
                    </h1>
                    <p class="text-gray-400 fs-6 mw-450px mb-8" style="line-height: 1.7;">
                        Sistem manajemen terpusat untuk katalog produk pewarna rambut, sinkronisasi stok, otomasi logistik KiriminAja, payment gateway Midtrans, dan audit formulasi.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-8">
                        <span class="badge badge-light-primary fw-bold fs-8 px-3 py-2">Multi-Role RBAC</span>
                        <span class="badge badge-light-success fw-bold fs-8 px-3 py-2">KiriminAja Logistics</span>
                        <span class="badge badge-light-info fw-bold fs-8 px-3 py-2">Midtrans Gateway</span>
                    </div>
                </div>

                <div class="text-center d-none d-lg-block mt-auto pt-5">
                    <img src="{{ asset('assets/media/auth/agency.png') }}" alt="Admin Illustration" class="mw-100 mh-250px mh-xl-325px" />
                </div>
            </div>

            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 p-lg-15 justify-content-center align-items-center" style="background-color: #f9f9fc !important;">
                <div class="w-100 mw-450px bg-white rounded-4 shadow-sm border border-gray-200 p-8 p-lg-12">
                    
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf
                        
                        <div class="text-start mb-8">
                            <h2 class="text-gray-900 fw-bolder fs-2x mb-2">Masuk ke Dashboard</h2>
                            <div class="text-gray-600 fw-semibold fs-7">Masukkan email dan kata sandi akun administrator</div>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3">
                            <i class="ki-duotone ki-shield-tick fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('success') }}</div>
                        </div>
                        @endif

                        @if(session('warning'))
                        <div class="alert alert-warning d-flex align-items-center p-4 mb-6 rounded-3">
                            <i class="ki-duotone ki-information-5 fs-2 text-warning me-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('warning') }}</div>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
                            <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
                        </div>
                        @endif

                        <div class="fv-row mb-6">
                            <label class="form-label fs-7 fw-bolder text-gray-900">Alamat Email</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control form-control-solid fs-7 text-gray-900 @error('email') is-invalid @enderror" 
                                   placeholder="nama@lumenhair.id" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus />
                            @error('email')
                                <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fv-row mb-6">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Kata Sandi</label>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   class="form-control form-control-solid fs-7 text-gray-900 @error('password') is-invalid @enderror" 
                                   placeholder="••••••••" 
                                   required 
                                   autocomplete="current-password" />
                            @error('password')
                                <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-stack flex-wrap gap-3 fs-7 fw-semibold mb-6">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="remember" id="kt_login_remember" {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label text-gray-700 fs-7" for="kt_login_remember">
                                    Ingat sesi saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-6">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary fw-bolder py-3 fs-7">
                                <span class="indicator-label">Masuk ke Dashboard</span>
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

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
