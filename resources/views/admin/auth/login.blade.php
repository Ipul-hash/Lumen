<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Sign In | LUMEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_app_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat" style="background-image: url('{{ asset('assets/media/auth/bg4.jpg') }}');">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <a href="{{ route('shop.index') }}" class="mb-3 text-decoration-none">
                        <span class="fs-2tx fw-bolder text-white tracking-wide">Lumen</span>
                    </a>
                    <div class="text-white opacity-75 fs-5 fw-normal">
                        Branding tools designed for your business
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-6 p-lg-20">
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10 p-lg-15 shadow-lg">
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <div class="d-flex flex-center flex-column-fluid pb-10">
                            
                            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('admin.login.submit') }}" method="POST">
                                @csrf
                                
                                <div class="text-center mb-11">
                                    <h1 class="text-gray-900 fw-bolder mb-3 fs-2x">Sign In</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Sistem Administrasi LUMEN Hair Color</div>
                                </div>

                                @if(session('success'))
                                <div class="alert alert-success d-flex align-items-center p-4 mb-7 rounded-3">
                                    <i class="ki-duotone ki-shield-tick fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fs-7 fw-semibold text-gray-800">{{ session('success') }}</div>
                                </div>
                                @endif

                                @if(session('warning'))
                                <div class="alert alert-warning d-flex align-items-center p-4 mb-7 rounded-3">
                                    <i class="ki-duotone ki-information-5 fs-2 text-warning me-3"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fs-7 fw-semibold text-gray-800">{{ session('warning') }}</div>
                                </div>
                                @endif

                                @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center p-4 mb-7 rounded-3">
                                    <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
                                </div>
                                @endif

                                <div class="fv-row mb-6">
                                    <label class="form-label fs-7 fw-bolder text-gray-900 mb-2">Email</label>
                                    <input type="email" 
                                           name="email" 
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
                                        <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Password</label>
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
                                        <label class="form-check-label text-gray-700 fs-7" for="kt_login_remember">
                                            Ingat sesi saya
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary fw-bolder py-3 fs-7">
                                        <span class="indicator-label">Sign In</span>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('shop.index') }}" class="text-muted text-hover-primary fs-7 fw-semibold">
                                        <i class="ki-duotone ki-arrow-left fs-6 me-1"><span class="path1"></span><span class="path2"></span></i> Kembali ke Toko LUMEN
                                    </a>
                                </div>
                            </form>

                        </div>

                        <div class="d-flex flex-stack fs-7 fw-semibold text-gray-500 pt-5 border-top">
                            <span class="text-gray-600">LUMEN Hair Color</span>
                            <div class="d-flex gap-4">
                                <a href="{{ route('shop.index') }}" class="text-gray-500 text-hover-primary">Store</a>
                                <a href="{{ route('shop.formulaCalculator') }}" class="text-gray-500 text-hover-primary">Kalkulator</a>
                                <a href="{{ route('refund.create') }}" class="text-gray-500 text-hover-primary">Refund</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
