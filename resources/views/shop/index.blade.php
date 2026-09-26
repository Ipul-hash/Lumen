@extends('shop.layouts.app')

@section('title', 'LUMEN Hair Color Atelier | Pewarna Rambut Formulasi Salon')

@section('content')
<section class="position-relative bg-dark text-white py-5 overflow-hidden" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%); min-height: 560px; display: flex; align-items: center;">
    <div class="container-fluid px-lg-5 position-relative" style="z-index: 1;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 oxva-reveal">
                <span class="oxva-badge mb-3">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #38bdf8; display: inline-block; box-shadow: 0 0 10px #38bdf8;"></span>
                    Salon-Grade Formulation at Home
                </span>
                <h1 class="display-3 fw-bold text-white mb-3 font-serif" style="letter-spacing: -0.02em; line-height: 1.1;">
                    ELEVATE YOUR <span class="oxva-text-gradient">TONE.</span>
                </h1>
                <p class="lead text-light mb-4 fs-6 pe-lg-5" style="opacity: 0.88; max-width: 600px; line-height: 1.6;">
                    Formulasi pewarna rambut premium bebas amonia berpadu dengan keratin hidrolisat dan minyak argan murni. Menghadirkan warna berdimensi intens tanpa mengorbankan kelembutan helai rambut Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#koleksi-warna" class="oxva-btn-primary">
                        Jelajahi Koleksi Warna <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('shop.shadeGuide') }}" class="oxva-btn-outline">
                        Panduan Level Bleaching
                    </a>
                </div>
                <div class="mt-4 pt-3 d-flex flex-wrap align-items-center gap-3 fs-8 text-white-50">
                    <span class="d-inline-flex align-items-center gap-1"><i class="bi bi-check2-circle text-info"></i> Tanpa Amonia</span>
                    <span class="d-inline-flex align-items-center gap-1"><i class="bi bi-shield-check text-info"></i> KiriminAja Auto-AWB</span>
                    <span class="d-inline-flex align-items-center gap-1"><i class="bi bi-qr-code text-info"></i> QRIS & VA Otomatis</span>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block oxva-reveal oxva-reveal-scale delay-2">
                    <div class="hero-ambient-glow"></div>
                    <div class="hero-feature-card text-start" style="width: 340px; max-width: 100%;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="oxva-badge" style="font-size: 0.65rem; padding: 4px 12px; background: rgba(255,255,255,0.1);">Color of the Season</span>
                            <span class="badge rounded-pill bg-light text-dark fw-bold fs-8">Level 9+</span>
                        </div>
                        <h3 class="text-white font-serif mb-1 fs-4">Nordic Ash Grey</h3>
                        <p class="fs-8 text-white-50 mb-3">Formula dingin berpigmen ultra-halus untuk menetralkan pantulan kekuningan rambut Asia.</p>
                        <div class="d-flex align-items-center gap-2 mb-4 p-2 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                            <span class="swatch-circle" style="background-color: #8C8D91; width:26px; height:26px;"></span>
                            <span class="swatch-circle" style="background-color: #C0C0C0; width:26px; height:26px;"></span>
                            <span class="swatch-circle" style="background-color: #5C5D61; width:26px; height:26px;"></span>
                            <span class="fs-8 text-white-50 ms-auto">Micro-Pigment Trio</span>
                        </div>
                        <a href="{{ route('shop.show', 'lumen-vivid-color-cream-120ml') }}" class="btn btn-outline-light rounded-pill w-100 py-2 fs-8 fw-bold">
                            Lihat Detail Shade Ini <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: #fbfbfd;">
    <div class="container-fluid px-lg-5">
        <div class="row g-4">
            <div class="col-6 col-lg-3 oxva-reveal delay-1">
                <div class="atelier-feature-card">
                    <div class="atelier-feature-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-7 text-uppercase text-dark tracking-wide">100% Bebas Amonia</div>
                        <div class="fs-8 text-muted mt-1">Aman & nyaman untuk kulit kepala sensitif</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 oxva-reveal delay-2">
                <div class="atelier-feature-card">
                    <div class="atelier-feature-icon">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-7 text-uppercase text-dark tracking-wide">Argan & Keratin</div>
                        <div class="fs-8 text-muted mt-1">Rambut tetap lembut, berkilau & terlindungi</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 oxva-reveal delay-3">
                <div class="atelier-feature-card">
                    <div class="atelier-feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-7 text-uppercase text-dark tracking-wide">KiriminAja Otomatis</div>
                        <div class="fs-8 text-muted mt-1">Tarif akurat & resi diterbitkan otomatis</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 oxva-reveal delay-4">
                <div class="atelier-feature-card">
                    <div class="atelier-feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-7 text-uppercase text-dark tracking-wide">QRIS & VA Midtrans</div>
                        <div class="fs-8 text-muted mt-1">Konfirmasi transaksi instan real-time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="koleksi-warna">
    <div class="container-fluid px-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 pb-3 border-bottom oxva-reveal">
            <div>
                <span class="fs-8 text-muted text-uppercase fw-bold tracking-widest d-block mb-1">Katalog Formulasi</span>
                <h2 class="font-serif fw-bold text-dark mt-0 mb-0 display-6">Koleksi Warna Unggulan</h2>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('shop.shadeGuide') }}" class="btn btn-sm btn-outline-dark rounded-pill px-4 py-2 fw-semibold fs-8">
                    <i class="bi bi-palette me-1"></i> Buka Color Chart
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
            @php
                $staggerDelay = ($loop->index % 4) + 1;
                $firstVariant = $product->activeVariants->first();
                $hasColorCode = $firstVariant && $firstVariant->color_code;
            @endphp
            <div class="col-6 col-md-4 col-lg-3 oxva-reveal delay-{{ $staggerDelay }}">
                <div class="product-card">
                    <div class="img-wrapper">
                        @if($product->primaryImage)
                            <img src="{{ str_starts_with($product->primaryImage->image_path, 'http') ? $product->primaryImage->image_path : asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-center p-3" style="background: {{ $hasColorCode ? 'linear-gradient(180deg, ' . $firstVariant->color_code . ' 0%, #171717 100%)' : '#f0f0f4' }}; color: {{ $hasColorCode ? '#fff' : '#111' }};">
                                <div>
                                    <div class="fs-6 font-serif fw-bold">{{ $product->name }}</div>
                                    <div class="fs-8 opacity-75 mt-1">{{ $firstVariant->color_name ?? 'Formula' }}</div>
                                </div>
                            </div>
                        @endif

                        @if($product->category)
                            <span class="product-badge-luxury">
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
                                <span class="fs-8 text-muted ms-1">+{{ $product->activeVariants->count() - 5 }}</span>
                            @endif
                        </div>

                        <h5 class="fs-6 fw-bold mb-1">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <p class="fs-8 text-muted mb-3 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->summary ?? 'Formula pewarna rambut intens salon grade dengan kilau lembut.' }}
                        </p>

                        <div class="mt-auto pt-2 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-8 text-muted d-block">Mulai dari</span>
                                <span class="fs-6 fw-bolder text-dark">{{ $product->formatted_price }}</span>
                            </div>

                            @if($product->activeVariants->count() === 1 && $product->activeVariants->first()->stock > 0)
                                <button type="button" class="btn-card-action" onclick="window.addToCart({{ $product->activeVariants->first()->id }}, 1)">
                                    + Beli
                                </button>
                            @else
                                <a href="{{ route('shop.show', $product->slug) }}" class="btn-card-action-outline">
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

<section class="py-5" style="background: #f7f7fa;">
    <div class="container-fluid px-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 oxva-reveal oxva-reveal-left">
                <span class="badge bg-dark text-white text-uppercase tracking-wider px-3 py-2 mb-3 fw-bold fs-8 rounded-pill">Sistem Pewarnaan Sempurna</span>
                <h2 class="display-5 font-serif fw-bold text-dark mb-3">Bleaching & Pre-Lightening Kit</h2>
                <p class="text-muted fs-6 mb-4 pe-lg-4" style="line-height: 1.6;">
                    Untuk mencapai warna ash, pastel, atau tone terang impian, rambut memerlukan dasar bleaching yang bersih dan merata. LUMEN Pro-Bleach Powder diformulasikan dengan anti-brass violet agent yang menahan pigmen kuning kemerahan sejak menit pertama.
                </p>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="atelier-feature-card py-3">
                        <div class="atelier-feature-icon" style="width: 42px; height: 42px; min-width: 42px; font-size: 1.15rem;">
                            <i class="bi bi-shield-plus"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold fs-7 text-dark">Anti-Breakage Bond Protector</h6>
                            <p class="fs-8 text-muted mb-0 mt-1">Menjaga ikatan keratin batang rambut agar tidak rapuh dan elastisitas tetap terjaga.</p>
                        </div>
                    </div>
                    <div class="atelier-feature-card py-3">
                        <div class="atelier-feature-icon" style="width: 42px; height: 42px; min-width: 42px; font-size: 1.15rem;">
                            <i class="bi bi-brightness-high"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold fs-7 text-dark">Angkat Hingga Level 9+</h6>
                            <p class="fs-8 text-muted mb-0 mt-1">Hasil pengangkatan warna rambut konsisten untuk kanvas pewarnaan maksimal.</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('shop.category', 'bleaching-developers') }}" class="btn btn-brand-dark rounded-pill px-4 py-3">
                    Lihat Koleksi Bleach & Developer <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="col-lg-6 oxva-reveal oxva-reveal-right delay-2">
                <div class="atelier-guide-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="font-serif fw-bold text-white mb-0 fs-5">Panduan Singkat Level Rambut</h5>
                        <span class="badge rounded-pill bg-white text-dark fw-bold fs-8">Atelier Chart</span>
                    </div>
                    <p class="fs-8 text-white-50 mb-4">Pahami kanvas dasar helai rambut Anda sebelum mengaplikasikan formula tone impian.</p>
                    <div class="d-flex flex-column mb-4">
                        <div class="level-row-item" style="background: rgba(28, 28, 28, 0.85);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #111; border: 1px solid #444; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 1 - 3: Hitam / Dark Brown</span>
                            </div>
                            <span class="badge bg-secondary rounded-pill fs-8">Perlu Bleach ke Lvl 8+</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(99, 63, 39, 0.45);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #633f27; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 4 - 6: Medium Brown</span>
                            </div>
                            <span class="badge bg-secondary rounded-pill fs-8">Burgundy & Warm Brown</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(216, 176, 86, 0.25);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #d8b056; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 7 - 8: Golden Blonde</span>
                            </div>
                            <span class="badge bg-light text-dark rounded-pill fs-8 fw-bold">Rose Gold & Milk Tea</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(247, 237, 212, 0.2);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #f7edd4; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 9 - 10: Pale Platinum</span>
                            </div>
                            <span class="badge bg-light text-dark rounded-pill fs-8 fw-bold">Nordic Ash Grey</span>
                        </div>
                    </div>
                    <a href="{{ route('shop.shadeGuide') }}" class="btn btn-outline-light rounded-pill w-100 py-2 fs-8 fw-semibold">
                        Buka Panduan Lengkap Shade Finder <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container-fluid px-lg-5">
        <div class="logistics-card text-white text-center oxva-reveal oxva-reveal-scale">
            <div style="position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 600px; height: 300px; background: radial-gradient(ellipse, rgba(56, 189, 248, 0.15) 0%, rgba(168, 85, 247, 0.12) 40%, transparent 70%); pointer-events: none;"></div>
            <div class="position-relative" style="z-index: 1;">
                <span class="oxva-badge mb-3">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    Pengiriman Kilat & Terpantau
                </span>
                <h2 class="display-6 font-serif fw-bold text-white mt-2 mb-3">Kemitraan Logistik Otomatis dengan KiriminAja</h2>
                <p class="fs-6 text-light opacity-75 mx-auto mb-4" style="max-width: 680px; line-height: 1.6;">
                    Pesanan Anda langsung diteruskan ke kurir pilihan (J&T Express, SiCepat, JNE) dengan penjemputan berkala dari warehouse Jakarta Selatan. Anda dapat memantau pergerakan paket secara real-time kapan saja.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff;">J&T Express</span>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff;">SiCepat REG / BEST</span>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff;">JNE Regular / YES</span>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff;">Automated AWB</span>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('shop.track') }}" class="oxva-btn-primary">
                        <i class="bi bi-search me-1"></i> Lacak Status Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
