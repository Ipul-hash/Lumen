<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Dashboard') - LUMEN Hair Color</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .colored-toast.swal2-icon-success {
            background-color: #10b981 !important;
        }
        .colored-toast.swal2-icon-error {
            background-color: #ef4444 !important;
        }
        .colored-toast.swal2-icon-warning {
            background-color: #f59e0b !important;
        }
        .colored-toast.swal2-icon-info {
            background-color: #3b82f6 !important;
        }
        .colored-toast .swal2-title {
            color: #ffffff !important;
            font-size: 14px !important;
            font-weight: 600 !important;
        }
        .colored-toast .swal2-close {
            color: #ffffff !important;
        }
        .colored-toast .swal2-html-container {
            color: #ffffff !important;
            font-size: 12px !important;
        }
    </style>

    @stack('styles')
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
    <script>
        var defaultThemeMode = "light"; 
        var themeMode; 
        if ( document.documentElement ) { 
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { 
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); 
            } else { 
                if ( localStorage.getItem("data-bs-theme") !== null ) { 
                    themeMode = localStorage.getItem("data-bs-theme"); 
                } else { 
                    themeMode = defaultThemeMode; 
                } 
            } 
            if (themeMode === "system") { 
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
            } 
            document.documentElement.setAttribute("data-bs-theme", themeMode); 
        }
    </script>

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            
            @include('admin.layouts.header')

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('admin.layouts.sidebar')

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @yield('content')
                    </div>
                    @include('admin.layouts.footer')
                </div>
            </div>
        </div>
    </div>

    <script>var hostUrl = "{{ asset('assets/') }}/";</script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        const AppToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: '#ffffff',
            customClass: {
                popup: 'colored-toast shadow-lg rounded-4'
            },
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        window.showToast = function(icon, title, message) {
            AppToast.fire({
                icon: icon,
                title: title,
                text: message || ''
            });
        };

        window.showConfirm = function(title, text, confirmButtonText, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText || 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary fw-bold px-6',
                    cancelButton: 'btn btn-light fw-bold px-6 me-2',
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        };

        document.addEventListener('click', function(e) {
            const confirmBtn = e.target.closest('[data-confirm]');
            if (!confirmBtn) return;

            e.preventDefault();
            const message = confirmBtn.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const title = confirmBtn.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
            const btnText = confirmBtn.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';

            const form = confirmBtn.closest('form');

            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: btnText,
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary fw-bold px-6',
                    cancelButton: 'btn btn-light fw-bold px-6 me-3',
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else if (confirmBtn.tagName === 'A' && confirmBtn.href) {
                        window.location.href = confirmBtn.href;
                    }
                }
            });
        });

        @if(session('success'))
            AppToast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            AppToast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if(session('warning'))
            AppToast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if(session('info'))
            AppToast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Perhatian!',
                html: '<ul class="text-start mb-0 ps-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                icon: 'error',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary fw-bold px-6',
                    popup: 'rounded-4'
                }
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
