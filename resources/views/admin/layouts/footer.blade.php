<div id="kt_app_footer" class="app-footer">
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <div class="text-gray-900 order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-800 text-hover-primary fw-bold">LUMEN Hair Color Atelier</a>
            <span class="text-muted ms-2 fs-7">- Integrasi KiriminAja & Payment Gateway</span>
        </div>
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ route('admin.shipments.calculator') }}" class="menu-link px-2">Kalkulator Ekspedisi</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.shipments.settings') }}" class="menu-link px-2">Pengaturan KiriminAja</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('shop.index') }}" target="_blank" class="menu-link px-2">Buka Toko</a>
            </li>
        </ul>
    </div>
</div>
