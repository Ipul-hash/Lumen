<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="240px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none py-3">
            <div class="symbol symbol-30px bg-primary rounded me-3 d-flex align-items-center justify-content-center">
                <i class="ki-duotone ki-color-filter text-white fs-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div class="d-flex flex-column app-sidebar-logo-default">
                <span class="fs-4 fw-bolder text-white ls-1">LUMEN HAIR</span>
                <span class="fs-8 text-gray-500 fw-semibold">Pewarna Rambut Admin</span>
            </div>
        </a>

        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-gray-500">Katalog Produk</span>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-cube-2 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Produk & Varian</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-category fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Kategori</span>
                        </a>
                    </div>

                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-gray-500">Transaksi & Penjualan</span>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-basket fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Pesanan Masuk</span>
                            @php
                                $processingOrders = \App\Models\Order::where('status', 'processing')->count();
                            @endphp
                            @if($processingOrders > 0)
                                <span class="badge badge-light-warning badge-circle fw-bold fs-7">{{ $processingOrders }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-gray-500">Logistik KiriminAja</span>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.shipments.index') ? 'active' : '' }}" href="{{ route('admin.shipments.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-delivery-3 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Daftar Pengiriman</span>
                            @php
                                $pendingPickup = \App\Models\Shipment::where('status', 'pending_pickup')->count();
                            @endphp
                            @if($pendingPickup > 0)
                                <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1">({{ $pendingPickup }})</span>
                            @endif
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.shipments.calculator') ? 'active' : '' }}" href="{{ route('admin.shipments.calculator') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-calculator fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Kalkulator Ongkir</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.shipments.settings') ? 'active' : '' }}" href="{{ route('admin.shipments.settings') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-setting-2 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Gudang & API Keys</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <div class="bg-gray-800 rounded p-4 d-flex flex-column text-white">
            <div class="d-flex align-items-center mb-1">
                <span class="badge badge-success badge-circle w-8px h-8px me-2"></span>
                <span class="fs-8 fw-bold">KiriminAja: Active</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-success badge-circle w-8px h-8px me-2"></span>
                <span class="fs-8 fw-bold">J&T, SiCepat, JNE</span>
            </div>
        </div>
    </div>
</div>
