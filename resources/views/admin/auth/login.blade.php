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
<body id="kt_app_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%);">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-500px p-10 p-lg-15 mx-auto bg-white rounded-4 shadow-lg">
                        
                        <div class="text-center mb-8">
                            <div class="symbol symbol-50px bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-shield-tick text-white fs-1"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <h1 class="text-gray-900 fw-bolder mb-2 fs-2x">Portal Admin LUMEN</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Sistem Manajemen E-Commerce & Formulasi Salon</div>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3">
                            <i class="ki-duotone ki-shield-tick fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('success') }}</div>
                        </div>
                        @endif

                        @if(session('warning'))
                        <div class="alert alert-warning d-flex align-items-center p-4 mb-5 rounded-3">
                            <i class="ki-duotone ki-information-5 fs-2 text-warning me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('warning') }}</div>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3">
                            <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
                        </div>
                        @endif

                        <form action="{{ route('admin.login.submit') }}" method="POST" id="kt_sign_in_form">
                            @csrf
                            
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Alamat Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="adminEmailInput"
                                       class="form-control form-control-solid fs-7 @error('email') is-invalid @enderror" 
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
                                       id="adminPasswordInput"
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

                            <div class="d-grid mb-8">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary fw-bolder py-3 fs-7">
                                    <span class="indicator-label">Masuk ke Dashboard</span>
                                </button>
                            </div>

                            <div class="p-4 bg-light rounded-3 border">
                                <span class="fs-8 fw-bold text-uppercase text-muted d-block mb-2">Pintasan Akun Role:</span>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-sm w-100 text-start p-2" onclick="fillCredentials('superadmin@lumenhair.id', 'password123')">
                                            <span class="badge badge-light-danger fs-9 fw-bolder d-block mb-1">Superadmin</span>
                                            <span class="fs-9 text-gray-700 d-block text-truncate">superadmin@...</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm w-100 text-start p-2" onclick="fillCredentials('admin@lumenhair.id', 'password123')">
                                            <span class="badge badge-light-primary fs-9 fw-bolder d-block mb-1">Admin Biasa</span>
                                            <span class="fs-9 text-gray-700 d-block text-truncate">admin@...</span>
                                        </button>
                                    </div>
                                </div>
                                <span class="fs-9 text-muted d-block mt-2 text-center">Klik kartu di atas untuk mengisi akun otomatis.</span>
                            </div>

                            <div class="text-center mt-6">
                                <a href="{{ route('shop.index') }}" class="text-muted text-hover-primary fs-7 fw-semibold">
                                    <i class="ki-duotone ki-arrow-left fs-6 me-1"></i> Kembali ke Toko LUMEN
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 d-none d-lg-flex flex-column align-items-center justify-content-center p-12 text-white text-center">
                <div class="mw-450px">
                    <span class="text-uppercase tracking-widest text-white-50 fw-bold fs-7 mb-3 d-block">LUMEN HAIR COLOR ATELIER</span>
                    <h2 class="display-6 font-serif fw-bold text-white mb-4">Enterprise Beauty-Tech & E-Commerce</h2>
                    <p class="text-light opacity-75 fs-6 mb-8" style="line-height: 1.8;">
                        Portal administrasi terpusat untuk pemrosesan pesanan, otomasi ekspedisi KiriminAja, payment gateway Midtrans, audit formulasi cat rambut, serta manajemen persetujuan & pengembalian dana.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge badge-outline text-white px-3 py-2 fs-8 rounded-pill border-secondary">Multi-Role RBAC</span>
                        <span class="badge badge-outline text-white px-3 py-2 fs-8 rounded-pill border-secondary">Real-Time Sync</span>
                        <span class="badge badge-outline text-white px-3 py-2 fs-8 rounded-pill border-secondary">Audit Trail</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script>
    function fillCredentials(email, pwd) {
        document.getElementById('adminEmailInput').value = email;
        document.getElementById('adminPasswordInput').value = pwd;
    }
    </script>
</body>
</html>
