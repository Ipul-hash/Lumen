<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LUMEN Hair Color Atelier | Salon-Grade Hair Dye & Bleach')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --brand-black: #111111;
            --brand-dark: #1e1e1e;
            --brand-gray: #666666;
            --brand-light: #f8f8f8;
            --brand-border: #e8e8e8;
            --brand-accent: #2563eb;
            --brand-font-serif: 'Playfair Display', Georgia, serif;
            --brand-font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            font-family: var(--brand-font-sans);
            color: var(--brand-black);
            background-color: #ffffff;
            -webkit-font-smoothing: antialiased;
            padding-top: 108px;
        }

        .site-header-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background-color: #ffffff;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
        }

        .site-header-wrapper.header-hidden {
            transform: translateY(-100%);
        }

        .site-header-wrapper.header-scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .font-serif {
            font-family: var(--brand-font-serif);
        }

        .announcement-bar {
            background-color: var(--brand-black);
            color: #ffffff;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 8px 16px;
            font-weight: 500;
        }

        .navbar-brand-logo {
            font-family: var(--brand-font-sans);
            font-weight: 800;
            letter-spacing: 0.25em;
            font-size: 1.4rem;
            text-transform: uppercase;
            color: var(--brand-black);
            text-decoration: none;
        }

        .navbar-nav {
            flex-direction: row !important;
            flex-wrap: nowrap;
        }

        .navbar-nav .nav-link {
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand-black);
            padding: 0.4rem 0.75rem;
            white-space: nowrap;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #555555;
        }

        .badge-cart-count {
            font-size: 0.65rem;
            background-color: var(--brand-black);
            color: #ffffff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -4px;
            right: -8px;
        }

        .btn-brand-dark {
            background-color: var(--brand-black);
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-radius: 0;
            padding: 14px 28px;
            border: 1px solid var(--brand-black);
            transition: all 0.25s ease;
        }

        .btn-brand-dark:hover {
            background-color: #2b2b2b;
            color: #ffffff;
            border-color: #2b2b2b;
        }

        .btn-brand-outline {
            background-color: transparent;
            color: var(--brand-black);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-radius: 0;
            padding: 14px 28px;
            border: 1px solid var(--brand-black);
            transition: all 0.25s ease;
        }

        .btn-brand-outline:hover {
            background-color: var(--brand-black);
            color: #ffffff;
        }

        .product-card {
            border: none;
            background: transparent;
            transition: transform 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .product-card .img-wrapper {
            position: relative;
            background-color: #f7f7f7;
            overflow: hidden;
            aspect-ratio: 1 / 1.15;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .swatch-circle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.15);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .swatch-circle:hover {
            transform: scale(1.25);
        }

        .swatch-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            padding: 2px;
            border: 2px solid transparent;
            background: transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .swatch-btn.active {
            border-color: var(--brand-black);
        }

        .swatch-btn span {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: block;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .footer-brand {
            background-color: #0d0d0d;
            color: #d1d5db;
            padding-top: 60px;
            padding-bottom: 40px;
            font-size: 0.875rem;
        }

        .footer-brand a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-brand a:hover {
            color: #ffffff;
        }

        .offcanvas-cart {
            width: 440px !important;
        }

        @media (max-width: 576px) {
            .offcanvas-cart {
                width: 100% !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div id="siteHeaderWrapper" class="site-header-wrapper">
    <div class="announcement-bar text-center">
        <span>Gratis Ongkir se-Indonesia Min. Belanja Rp 500.000 • Kirim Cepat & Terlacak Real-Time via KiriminAja</span>
    </div>

    <header class="bg-white border-bottom py-3">
        <div class="container-fluid px-lg-5">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-link text-dark d-lg-none p-1 me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavOffcanvas" aria-controls="mobileNavOffcanvas" aria-label="Toggle Navigation">
                        <i class="bi bi-list fs-2"></i>
                    </button>

                    <a href="{{ route('shop.index') }}" class="navbar-brand-logo">
                        LUMEN
                        <span class="d-block text-muted fw-normal" style="font-size: 0.6rem; letter-spacing: 0.35em;">HAIR COLOR ATELIER</span>
                    </a>
                    
                    <nav class="d-none d-lg-flex flex-row align-items-center navbar-nav ms-3 ms-xl-4 gap-1">
                        <a href="{{ route('shop.index') }}" class="nav-link">Beranda</a>
                        <a href="{{ route('shop.category', 'semi-permanent-hair-dye') }}" class="nav-link">Koleksi Warna</a>
                        <a href="{{ route('shop.category', 'bleaching-developers') }}" class="nav-link">Bleach & Developers</a>
                        <a href="{{ route('shop.category', 'color-care-treatment') }}" class="nav-link">Color Care</a>
                        <a href="{{ route('shop.shadeGuide') }}" class="nav-link">Panduan Shade</a>
                        <a href="{{ route('shop.track') }}" class="nav-link">Lacak Resi</a>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <a href="{{ route('shop.track') }}" class="btn btn-sm btn-link text-dark text-decoration-none d-none d-md-inline-block fs-7 fw-semibold">
                        <i class="bi bi-geo-alt me-1"></i> Cek Pengiriman
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-dark rounded-0 px-3 py-1 fs-7 fw-semibold">
                        <i class="bi bi-speedometer2 me-1"></i> Admin Portal
                    </a>

                    <button class="btn btn-link text-dark position-relative p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas" id="btnOpenCart">
                        <i class="bi bi-bag fs-4"></i>
                        <span class="badge-cart-count" id="headerCartCount">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel">
    <div class="offcanvas-header border-bottom py-3">
        <a href="{{ route('shop.index') }}" class="navbar-brand-logo fs-5">
            LUMEN
            <span class="d-block text-muted fw-normal" style="font-size: 0.55rem; letter-spacing: 0.35em;">HAIR COLOR ATELIER</span>
        </a>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
        <div class="list-group list-group-flush">
            <a href="{{ route('shop.index') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">Beranda</a>
            <a href="{{ route('shop.category', 'semi-permanent-hair-dye') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">Koleksi Warna</a>
            <a href="{{ route('shop.category', 'bleaching-developers') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">Bleach & Developers</a>
            <a href="{{ route('shop.category', 'color-care-treatment') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">Color Care</a>
            <a href="{{ route('shop.shadeGuide') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">Panduan Shade</a>
            <a href="{{ route('shop.track') }}" class="list-group-item list-group-item-action py-3 px-4 fw-bold fs-7 text-uppercase tracking-wider">
                <i class="bi bi-geo-alt me-2 text-primary"></i> Lacak Resi Pengiriman
            </a>
        </div>
        <div class="p-4 border-top bg-light">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-dark w-100 rounded-0 py-2 fw-semibold fs-7 mb-2">
                <i class="bi bi-speedometer2 me-1"></i> Masuk Admin Portal
            </a>
            <div class="fs-8 text-muted text-center mt-2">&copy; {{ date('Y') }} LUMEN Hair Color Atelier</div>
        </div>
    </div>
</div>

<main>
    @yield('content')
</main>

<div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header border-bottom py-3">
        <h5 class="offcanvas-title fw-bold text-uppercase fs-6 tracking-wide" id="cartOffcanvasLabel">
            Tas Belanja Anda (<span id="drawerItemCount">0</span>)
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
            <span class="fs-8 text-muted">
                <i class="bi bi-box-seam me-1 text-primary"></i> Estimasi Berat KiriminAja:
            </span>
            <span class="fw-bold fs-7 text-dark" id="drawerTotalWeight">0 gram</span>
        </div>

        <div class="flex-grow-1 overflow-auto p-4" id="drawerItemsList">
            <div class="text-center py-5" id="drawerEmptyNotice">
                <i class="bi bi-bag-x fs-1 text-muted"></i>
                <p class="mt-3 text-muted fs-6">Tas belanja Anda masih kosong.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-brand-dark mt-2" data-bs-dismiss="offcanvas">Mulai Belanja</a>
            </div>
        </div>

        <div class="p-4 border-top bg-white">
            <div class="mb-3">
                <form id="drawerCouponForm" class="input-group">
                    @csrf
                    <input type="text" id="couponCodeInput" class="form-control form-control-sm rounded-0" placeholder="Kode Promo (LUMENNEW / KIRIMINAJA)">
                    <button class="btn btn-outline-dark btn-sm rounded-0" type="submit">Pasang</button>
                </form>
                <div id="couponNotice" class="fs-8 mt-1"></div>
            </div>

            <div class="d-flex justify-content-between mb-2 fs-7 text-muted">
                <span>Subtotal</span>
                <span class="text-dark fw-semibold" id="drawerSubtotal">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-2 fs-7 text-success" id="drawerDiscountRow" style="display:none;">
                <span>Diskon Promo</span>
                <span class="fw-semibold" id="drawerDiscount">-Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-4 fs-6 fw-bold text-dark border-top pt-2">
                <span>Total Belanja</span>
                <span class="fs-5" id="drawerTotal">Rp 0</span>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('checkout.index') }}" class="btn btn-brand-dark py-3 text-center text-decoration-none">
                    Lanjut ke Checkout
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
            <p class="text-center text-muted fs-8 mt-2 mb-0">Pengiriman aman & terproteksi via KiriminAja</p>
        </div>
    </div>
</div>

<footer class="footer-brand">
    <div class="container-fluid px-lg-5">
        <div class="row g-4 pb-5">
            <div class="col-lg-4">
                <div class="fs-4 fw-bold text-white mb-2 tracking-wide font-serif">L U M E N</div>
                <div class="fs-7 text-muted mb-3">HAIR COLOR ATELIER • FORMULASI SALON PROFESIONAL</div>
                <p class="text-secondary fs-7 pe-lg-4">
                    Formula pewarna rambut inovatif bebas amonia dengan konsentrat Argan Oil & Keratin. Menghasilkan warna intens, kilau multidimensi, serta helai rambut yang tetap sehat dan lembut.
                </p>
                <div class="mt-4">
                    <span class="badge bg-dark border border-secondary text-secondary me-2 p-2">Cruelty Free</span>
                    <span class="badge bg-dark border border-secondary text-secondary me-2 p-2">Ammonia Free</span>
                    <span class="badge bg-dark border border-secondary text-secondary p-2">Keratin Infused</span>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="text-white text-uppercase fs-7 fw-bold mb-3 tracking-wide">Koleksi</h6>
                <ul class="list-unstyled fs-7 d-flex flex-column gap-2">
                    <li><a href="{{ route('shop.category', 'semi-permanent-hair-dye') }}">Ash & Cool Tones</a></li>
                    <li><a href="{{ route('shop.category', 'bleaching-developers') }}">Bleach & Developers</a></li>
                    <li><a href="{{ route('shop.category', 'color-care-treatment') }}">Purple Shampoo & Care</a></li>
                    <li><a href="{{ route('shop.shadeGuide') }}">Panduan Level Warna</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="text-white text-uppercase fs-7 fw-bold mb-3 tracking-wide">Bantuan & Logistik</h6>
                <ul class="list-unstyled fs-7 d-flex flex-column gap-2">
                    <li><a href="{{ route('shop.track') }}">Lacak Pengiriman KiriminAja</a></li>
                    <li><a href="{{ route('shop.shadeGuide') }}">Cara Memilih Developer</a></li>
                    <li><a href="{{ route('shop.shadeGuide') }}">Panduan Tes Alergi Kulit</a></li>
                    <li><a href="{{ route('admin.dashboard') }}">Login Dashboard Admin</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="text-white text-uppercase fs-7 fw-bold mb-3 tracking-wide">Metode Pembayaran & Ekspedisi</h6>
                <p class="fs-8 text-secondary mb-3">Transaksi terenkripsi otomatis dengan QRIS (Gopay/OVO/Dana/BCA) dan Virtual Account (BCA, Mandiri, BNI, BRI).</p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold">QRIS</span>
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold">BCA VA</span>
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold">Mandiri VA</span>
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold">BNI VA</span>
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold">BRI VA</span>
                </div>

                <div class="fs-8 text-secondary">Didukung oleh Ekspedisi Terpercaya via KiriminAja:</div>
                <div class="d-flex gap-2 mt-2">
                    <span class="badge bg-secondary text-white px-2 py-1">J&T Express</span>
                    <span class="badge bg-secondary text-white px-2 py-1">SiCepat Ekspres</span>
                    <span class="badge bg-secondary text-white px-2 py-1">JNE Express</span>
                </div>
            </div>
        </div>

        <div class="border-top border-secondary pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center fs-8 text-secondary">
            <div>&copy; {{ date('Y') }} LUMEN Hair Color Atelier. Hak cipta dilindungi undang-undang.</div>
            <div class="mt-2 mt-md-0">
                <span class="me-3">Terhubung dengan Ekosistem KiriminAja Real-Time Logistics</span>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.showToast = function(type, message) {
    window.Toast.fire({
        icon: type,
        title: message
    });
};

@if (session('success'))
    window.showToast('success', "{{ session('success') }}");
@endif
@if (session('error'))
    window.showToast('error', "{{ session('error') }}");
@endif
@if (session('warning'))
    window.showToast('warning', "{{ session('warning') }}");
@endif
@if ($errors->any())
    window.showToast('error', "{{ $errors->first() }}");
@endif

window.refreshCartDrawer = function() {
    fetch("{{ route('cart.drawer') }}")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const countBadge = document.getElementById('headerCartCount');
                const drawerCount = document.getElementById('drawerItemCount');
                if (countBadge) countBadge.innerText = data.total_items;
                if (drawerCount) drawerCount.innerText = data.total_items;

                const weightEl = document.getElementById('drawerTotalWeight');
                if (weightEl) weightEl.innerText = data.formatted_weight;

                const subtotalEl = document.getElementById('drawerSubtotal');
                if (subtotalEl) subtotalEl.innerText = data.formatted_subtotal;

                const totalEl = document.getElementById('drawerTotal');
                if (totalEl) totalEl.innerText = data.formatted_total;

                const discountRow = document.getElementById('drawerDiscountRow');
                const discountEl = document.getElementById('drawerDiscount');
                if (data.discount_amount > 0) {
                    if (discountRow) discountRow.style.display = 'flex';
                    if (discountEl) discountEl.innerText = '-Rp ' + new Intl.NumberFormat('id-ID').format(data.discount_amount);
                } else {
                    if (discountRow) discountRow.style.display = 'none';
                }

                const list = document.getElementById('drawerItemsList');
                if (data.items.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-bag-x fs-1 text-muted"></i>
                            <p class="mt-3 text-muted fs-6">Tas belanja Anda masih kosong.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-sm btn-brand-dark mt-2" data-bs-dismiss="offcanvas">Mulai Belanja</a>
                        </div>
                    `;
                } else {
                    let html = '<div class="d-flex flex-column gap-3">';
                    data.items.forEach(item => {
                        const swatch = item.color_code ? `<span class="swatch-circle me-1" style="background-color: ${item.color_code};"></span>` : '';
                        html += `
                            <div class="d-flex align-items-center gap-3 border-bottom pb-3">
                                <div style="width: 64px; height: 64px; background: #f3f3f3; flex-shrink: 0;" class="d-flex align-items-center justify-content-center">
                                    ${item.image ? `<img src="${item.image}" style="width:100%; height:100%; object-fit:cover;">` : `<i class="bi bi-droplet-half fs-3 text-secondary"></i>`}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fs-7 fw-bold mb-0 text-dark">${item.product_name}</h6>
                                    <div class="fs-8 text-muted d-flex align-items-center mt-1">
                                        ${swatch} ${item.variant_name} ${item.size ? `(${item.size})` : ''}
                                    </div>
                                    <div class="fs-7 fw-semibold text-dark mt-1">${item.formatted_price}</div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <div class="input-group input-group-sm" style="width: 90px;">
                                            <button class="btn btn-outline-secondary px-2 btn-cart-minus" data-id="${item.id}" data-qty="${item.quantity - 1}">-</button>
                                            <input type="text" class="form-control text-center p-0 bg-white" value="${item.quantity}" readonly>
                                            <button class="btn btn-outline-secondary px-2 btn-cart-plus" data-id="${item.id}" data-qty="${item.quantity + 1}">+</button>
                                        </div>
                                        <button class="btn btn-link text-danger fs-8 p-0 ms-2 btn-cart-del" data-id="${item.id}">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    list.innerHTML = html;
                }
            }
        });
};

window.addToCart = function(variantId, quantity = 1) {
    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ variant_id: variantId, quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.showToast('success', data.message);
            window.refreshCartDrawer();
            const offcanvasEl = document.getElementById('cartOffcanvas');
            if (offcanvasEl) {
                const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                offcanvas.show();
            }
        } else {
            window.showToast('error', data.message || 'Gagal menambahkan produk ke tas');
        }
    })
    .catch(() => {
        window.showToast('error', 'Terjadi kesalahan sistem saat menambahkan produk');
    });
};

document.addEventListener('DOMContentLoaded', function() {
    window.refreshCartDrawer();

    document.getElementById('drawerItemsList')?.addEventListener('click', function(e) {
        const minusBtn = e.target.closest('.btn-cart-minus');
        const plusBtn = e.target.closest('.btn-cart-plus');
        const delBtn = e.target.closest('.btn-cart-del');

        if (minusBtn || plusBtn) {
            const btn = minusBtn || plusBtn;
            const itemId = btn.dataset.id;
            const qty = btn.dataset.qty;

            fetch(`/cart/items/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(res => res.json())
            .then(() => window.refreshCartDrawer());
        }

        if (delBtn) {
            const itemId = delBtn.dataset.id;
            fetch(`/cart/items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                window.showToast('info', data.message);
                window.refreshCartDrawer();
            });
        }
    });

    document.getElementById('drawerCouponForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const code = document.getElementById('couponCodeInput').value;
        if (!code) return;

        fetch("{{ route('cart.coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(res => res.json())
        .then(data => {
            const notice = document.getElementById('couponNotice');
            if (data.success) {
                notice.className = 'fs-8 mt-1 text-success';
                notice.innerText = data.message;
                window.showToast('success', data.message);
                window.refreshCartDrawer();
            } else {
                notice.className = 'fs-8 mt-1 text-danger';
                notice.innerText = data.message;
                window.showToast('warning', data.message);
            }
        });
    });

    const headerWrapper = document.getElementById('siteHeaderWrapper');
    if (headerWrapper) {
        function syncBodyPadding() {
            document.body.style.paddingTop = headerWrapper.offsetHeight + 'px';
        }
        syncBodyPadding();
        window.addEventListener('resize', syncBodyPadding);

        let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
        let ticking = false;
        const threshold = 8;

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
                    const diff = currentScrollY - lastScrollY;

                    if (currentScrollY <= 15) {
                        headerWrapper.classList.remove('header-hidden');
                        headerWrapper.classList.remove('header-scrolled');
                    } else {
                        headerWrapper.classList.add('header-scrolled');
                        if (diff > threshold && currentScrollY > 70) {
                            headerWrapper.classList.add('header-hidden');
                        } else if (diff < -threshold) {
                            headerWrapper.classList.remove('header-hidden');
                        }
                    }

                    lastScrollY = currentScrollY;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }
});
</script>
@stack('scripts')
</body>
</html>
