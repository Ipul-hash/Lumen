<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>

        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ route('admin.dashboard') }}" class="d-lg-none">
                <span class="fw-bolder fs-4 text-gray-900 tracking-wider">LUMEN</span>
            </a>
        </div>

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <div class="page-title d-flex justify-content-center flex-column me-5">
                <h1 class="d-flex flex-column text-gray-900 fw-bold fs-3 mb-0">@yield('page-title', 'Dashboard')</h1>
                @yield('breadcrumb')
            </div>

            <div class="app-navbar flex-shrink-0 align-items-center">
                <div class="app-navbar-item ms-1 ms-md-3">
                    <a href="{{ route('shop.index') }}" target="_blank" class="btn btn-sm btn-light-dark fw-bold">
                        <i class="ki-duotone ki-exit-right-corner fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Lihat Storefront Toko
                    </a>
                </div>

                <div class="app-navbar-item ms-1 ms-md-3">
                    <a href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-night-day theme-light-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                            <span class="path6"></span>
                            <span class="path7"></span>
                            <span class="path8"></span>
                            <span class="path9"></span>
                            <span class="path10"></span>
                        </i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2 active" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="app-navbar-item ms-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="symbol-label bg-light-primary text-primary fw-bolder fs-5">
                            {{ substr(auth()->user()->name ?? 'Admin', 0, 1) }}
                        </div>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-light-primary text-primary fw-bolder fs-3">
                                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ auth()->user()->name ?? 'Administrator' }}
                                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span>
                                    </div>
                                    <span class="fw-semibold text-muted text-hover-primary fs-7">
                                        {{ auth()->user()->email ?? 'admin@lumenhair.id' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.dashboard') }}" class="menu-link px-5">Dashboard</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.shipments.settings') }}" class="menu-link px-5">Pengaturan Ekspedisi</a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route('shop.index') }}" class="menu-link px-5">Kembali ke Toko</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
