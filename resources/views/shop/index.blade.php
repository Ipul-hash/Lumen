@extends('shop.layouts.app')

@section('title', 'LUMEN Hair Color Atelier | Pewarna Rambut Formulasi Salon')

@section('content')
<section class="position-relative bg-dark text-white py-5 overflow-hidden" style="background: linear-gradient(135deg, #111111 0%, #1f1b24 50%, #0d0d0d 100%); min-height: 520px; display: flex; align-items: center;">
    <div class="container-fluid px-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-light text-dark text-uppercase tracking-wider px-3 py-2 mb-3 fw-bold fs-8">Salon-Grade Formulation at Home</span>
                <h1 class="display-3 fw-bold text-white mb-3 font-serif" style="letter-spacing: -0.02em; line-height: 1.1;">
                    ELEVATE YOUR TONE.
                </h1>
                <p class="lead text-light mb-4 fs-6 pe-lg-5" style="opacity: 0.9; max-width: 600px;">
                    Formulasi pewarna rambut premium bebas amonia berpadu dengan keratin hidrolisat dan minyak argan murni. Menghadirkan warna berdimensi intens tanpa mengorbankan kelembutan helai rambut Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#koleksi-warna" class="btn btn-light rounded-0 px-4 py-3 fw-bold text-uppercase fs-7 tracking-wide">
                        Jelajahi Koleksi Warna
                    </a>
                    <a href="{{ route('shop.shadeGuide') }}" class="btn btn-outline-light rounded-0 px-4 py-3 fw-bold text-uppercase fs-7 tracking-wide">
                        Panduan Level Bleaching
                    </a>
                </div>
                <div class="mt-4 pt-2 d-flex align-items-center gap-4 fs-8 text-white-50">
                    <div><i class="bi bi-check2-circle text-info me-1"></i> Tanpa Amonia</div>
                    <div><i class="bi bi-shield-check text-info me-1"></i> KiriminAja Auto-AWB</div>
                    <div><i class="bi bi-qr-code text-info me-1"></i> QRIS & VA Otomatis</div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block">
                    <div style="width: 320px; height: 420px; background: radial-gradient(circle, rgba(168,85,247,0.2) 0%, rgba(0,0,0,0) 70%); position: absolute; top: -20px; left: -20px; z-index: 0;"></div>
                    <div class="p-4 bg-black bg-opacity-50 border border-secondary border-opacity-25 backdrop-blur position-relative" style="backdrop-filter: blur(10px);">
                        <div class="fs-7 text-uppercase tracking-widest text-white-50 mb-1">Color of the Season</div>
                        <h3 class="text-white font-serif mb-2">Nordic Ash Grey</h3>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="swatch-circle" style="background-color: #8C8D91; width:24px; height:24px;"></span>
                            <span class="swatch-circle" style="background-color: #C0C0C0; width:24px; height:24px;"></span>
                            <span class="swatch-circle" style="background-color: #5C5D61; width:24px; height:24px;"></span>
                        </div>
                        <p class="fs-8 text-white-50 mb-3">Formula dingin berpigmen ultra-halus untuk menetralkan pantulan kekuningan rambut Asia.</p>
                        <a href="{{ route('shop.show', 'lumen-vivid-color-cream-120ml') }}" class="btn btn-sm btn-outline-light rounded-0 w-100 py-2 fs-8 fw-bold">
                            Lihat Detail Shade Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-bottom py-3 bg-light">
    <div class="container-fluid px-lg-5">
        <div class="row g-3 text-center text-md-start">
            <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                <i class="bi bi-droplet fs-3 text-dark"></i>
                <div>
                    <div class="fw-bold fs-7 text-uppercase">100% Bebas Amonia</div>
                    <div class="fs-8 text-muted">Aman untuk kulit kepala sensitif</div>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                <i class="bi bi-flower1 fs-3 text-dark"></i>
                <div>
                    <div class="fw-bold fs-7 text-uppercase">Argan & Keratin Infused</div>
                    <div class="fs-8 text-muted">Rambut tetap halus & berkilau</div>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                <i class="bi bi-truck fs-3 text-dark"></i>
                <div>
                    <div class="fw-bold fs-7 text-uppercase">Ekspedisi KiriminAja</div>
                    <div class="fs-8 text-muted">Tarif akurat & resi diterbitkan otomatis</div>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                <i class="bi bi-credit-card-2-front fs-3 text-dark"></i>
                <div>
                    <div class="fw-bold fs-7 text-uppercase">QRIS & Virtual Account</div>
                    <div class="fs-8 text-muted">Konfirmasi transaksi instan tanpa upload slip</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="koleksi-warna">
    <div class="container-fluid px-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 pb-2 border-bottom">
            <div>
                <span class="fs-8 text-muted text-uppercase fw-bold tracking-widest">Katalog Formulasi</span>
                <h2 class="font-serif fw-bold text-dark mt-1 mb-0">Koleksi Warna Unggulan</h2>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('shop.shadeGuide') }}" class="btn btn-sm btn-outline-dark rounded-0 px-3">
                    <i class="bi bi-palette me-1"></i> Buka Color Chart
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="img-wrapper mb-3 position-relative">
                        @php
                            $firstVariant = $product->activeVariants->first();
                            $hasColorCode = $firstVariant && $firstVariant->color_code;
                        @endphp
                        @if($product->primaryImage)
                            <img src="{{ str_starts_with($product->primaryImage->image_path, 'http') ? $product->primaryImage->image_path : asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-center p-3" style="background: {{ $hasColorCode ? 'linear-gradient(180deg, ' . $firstVariant->color_code . ' 0%, #1e1e1e 100%)' : '#f0f0f0' }}; color: {{ $hasColorCode ? '#fff' : '#000' }};">
                                <div>
                                    <div class="fs-5 font-serif fw-bold">{{ $product->name }}</div>
                                    <div class="fs-8 opacity-75 mt-1">{{ $firstVariant->color_name ?? 'Formula' }}</div>
                                </div>
                            </div>
                        @endif

                        @if($product->category)
                            <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 rounded-0 fs-8 fw-semibold border">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center gap-1 mb-2">
                            @foreach($product->activeVariants->take(5) as $v)
                                @if($v->color_code)
                                    <span class="swatch-circle" style="background-color: {{ $v->color_code }};" title="{{ $v->color_name }}"></span>
                                @endif
                            @endforeach
                            @if($product->activeVariants->count() > 5)
                                <span class="fs-8 text-muted">+{{ $product->activeVariants->count() - 5 }}</span>
                            @endif
                        </div>

                        <h5 class="fs-6 fw-bold mb-1">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <p class="fs-8 text-muted mb-2 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->summary ?? 'Formula pewarna rambut intens salon grade dengan kilau lembut.' }}
                        </p>

                        <div class="mt-auto pt-2 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-8 text-muted d-block">Mulai dari</span>
                                <span class="fs-6 fw-bolder text-dark">{{ $product->formatted_price }}</span>
                            </div>

                            @if($product->activeVariants->count() === 1 && $product->activeVariants->first()->stock > 0)
                                <button type="button" class="btn btn-sm btn-outline-dark rounded-0 px-3 py-1" onclick="window.addToCart({{ $product->activeVariants->first()->id }}, 1)">
                                    + Beli
                                </button>
                            @else
                                <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-sm btn-outline-dark rounded-0 px-3 py-1">
                                    Pilih Shade
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk pewarna rambut aktif.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-fluid px-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-dark text-white text-uppercase tracking-wider px-3 py-2 mb-3 fw-bold fs-8">Sistem Pewarnaan Sempurna</span>
                <h2 class="display-5 font-serif fw-bold text-dark mb-3">Bleaching & Pre-Lightening Kit</h2>
                <p class="text-muted fs-6 mb-4 pe-lg-4">
                    Untuk mencapai warna ash, pastel, atau tone terang impian, rambut memerlukan dasar bleaching yang bersih dan merata. LUMEN Pro-Bleach Powder diformulasikan dengan anti-brass violet agent yang menahan pigmen kuning kemerahan sejak menit pertama.
                </p>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-shield-plus fs-4 text-dark"></i>
                        <div>
                            <h6 class="mb-0 fw-bold fs-7">Anti-Breakage Bond Protector</h6>
                            <p class="fs-8 text-muted mb-0">Menjaga ikatan keratin batang rambut agar tidak rapuh dan elastisitas tetap terjaga.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-brightness-high fs-4 text-dark"></i>
                        <div>
                            <h6 class="mb-0 fw-bold fs-7">Angkat Hingga Level 9+</h6>
                            <p class="fs-8 text-muted mb-0">Hasil pengangkatan warna rambut konsisten untuk kanvas pewarnaan maksimal.</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('shop.category', 'bleaching-developers') }}" class="btn btn-brand-dark">
                    Lihat Koleksi Bleach & Developer
                </a>
            </div>
            <div class="col-lg-6">
                <div class="bg-white p-4 border shadow-sm">
                    <h5 class="font-serif fw-bold text-dark mb-3">Panduan Singkat Level Rambut Sebelum Mewarnai</h5>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="p-2 border d-flex align-items-center justify-content-between" style="background: #1c1c1c; color:#fff;">
                            <span class="fs-8 fw-bold">Level 1 - 3: Hitam / Dark Brown</span>
                            <span class="badge bg-secondary fs-8">Perlu Bleach ke Lvl 8+ untuk Ash</span>
                        </div>
                        <div class="p-2 border d-flex align-items-center justify-content-between" style="background: #633f27; color:#fff;">
                            <span class="fs-8 fw-bold">Level 4 - 6: Medium Brown / Cokelat Terang</span>
                            <span class="badge bg-secondary fs-8">Cocok untuk Burgundy & Warm Brown</span>
                        </div>
                        <div class="p-2 border d-flex align-items-center justify-content-between" style="background: #d8b056; color:#000;">
                            <span class="fs-8 fw-bold">Level 7 - 8: Golden Blonde</span>
                            <span class="badge bg-dark fs-8">Ideal untuk Rose Gold & Milk Tea</span>
                        </div>
                        <div class="p-2 border d-flex align-items-center justify-content-between" style="background: #f7edd4; color:#000;">
                            <span class="fs-8 fw-bold">Level 9 - 10: Pale Platinum Blonde</span>
                            <span class="badge bg-dark fs-8">Sempurna untuk Nordic Ash Grey & Silver</span>
                        </div>
                    </div>
                    <a href="{{ route('shop.shadeGuide') }}" class="btn btn-outline-dark btn-sm rounded-0 w-100 py-2">
                        Buka Panduan Lengkap Shade Finder
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container-fluid px-lg-5">
        <div class="border p-4 p-md-5 bg-dark text-white text-center position-relative overflow-hidden">
            <div class="position-relative" style="z-index: 1;">
                <span class="text-uppercase tracking-widest fs-8 text-white-50 fw-bold">Pengiriman Kilat & Terpantau</span>
                <h2 class="display-6 font-serif fw-bold text-white mt-2 mb-3">Kemitraan Logistik Otomatis dengan KiriminAja</h2>
                <p class="fs-6 text-light opacity-75 mx-auto mb-4" style="max-width: 680px;">
                    Pesanan Anda langsung diteruskan ke kurir pilihan (J&T, SiCepat, JNE) dengan penjemputan berkala dari warehouse Jakarta Selatan. Anda dapat memantau pergerakan paket secara real-time kapan saja.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('shop.track') }}" class="btn btn-light rounded-0 px-4 py-3 fw-bold text-uppercase fs-7">
                        <i class="bi bi-search me-1"></i> Lacak Status Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
