@extends('shop.layouts.app')

@section('title', 'LUMEN Hair Color Atelier | Pewarna Rambut Formulasi Salon')

@push('styles')
<style>
    .perfume-bottle-hover {
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .perfume-full-card:hover .perfume-bottle-hover {
        transform: scale(1.05);
    }
    .perfume-full-card {
        transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.45s ease;
    }
    .perfume-full-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.85), 0 0 35px rgba(212, 175, 55, 0.25) !important;
    }
    .hero-smoke-card .hero-smoke-bg {
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
    }
    .hero-smoke-card:hover .hero-smoke-bg {
        transform: scale(1.08) rotate(1deg);
        opacity: 0.85;
    }
</style>
@endpush

@section('content')
<section class="position-relative bg-dark text-white py-5 overflow-hidden" style="background: linear-gradient(135deg, #07070a 0%, #101016 50%, #050508 100%); min-height: 580px; display: flex; align-items: center;">
    <!-- Pure Ethereal Smoke Background for Elevate Your Tone section -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('{{ asset('images/nordic-ash-smoke.jpg') }}') center right / cover no-repeat; opacity: 0.28; mix-blend-mode: screen; pointer-events: none;"></div>
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, rgba(7,7,10,0.92) 0%, rgba(12,12,18,0.65) 45%, rgba(6,6,9,0.85) 100%); pointer-events: none;"></div>

    <div class="container-fluid px-lg-5 position-relative" style="z-index: 1;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 oxva-reveal">
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
                <div class="mt-4 pt-3 d-flex flex-wrap align-items-center gap-4 fs-8 text-white-50">
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2 text-light opacity-50"></i> Tanpa Amonia</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2 text-light opacity-50"></i> Salon Grade Formula</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2 text-light opacity-50"></i> Keratin & Argan Infused</span>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block oxva-reveal oxva-reveal-scale delay-2">
                    <div class="hero-ambient-glow" style="background: radial-gradient(circle, rgba(160, 170, 190, 0.28) 0%, rgba(90, 100, 120, 0.15) 45%, transparent 70%);"></div>
                    <div class="hero-feature-card hero-smoke-card text-start position-relative overflow-hidden" style="width: 340px; max-width: 100%; border: 1px solid rgba(255, 255, 255, 0.18); box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.75), 0 0 30px rgba(160, 175, 195, 0.18); padding-top: 32px; padding-bottom: 32px;">
                        <!-- Ethereal Pure Smoke Background Layer for the Card -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 hero-smoke-bg" style="background: url('{{ asset('images/nordic-ash-smoke.jpg') }}') center/cover no-repeat; opacity: 0.85; mix-blend-mode: screen; pointer-events: none;"></div>
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(12, 12, 18, 0.45) 0%, rgba(10, 10, 15, 0.85) 100%); pointer-events: none;"></div>

                        <div class="position-relative" style="z-index: 1;">
                            <h3 class="text-white font-serif mb-2 fs-3" style="letter-spacing: -0.01em;">Nordic Ash Grey</h3>
                            <p class="fs-8 text-white-50 mb-3" style="line-height: 1.6;">Formula dingin berpigmen ultra-halus untuk menetralkan pantulan kekuningan rambut Asia.</p>
                            <div class="d-flex align-items-center gap-2 mb-4 p-2 rounded-3" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(8px);">
                                <span class="swatch-circle" style="background-color: #8C8D91; width:26px; height:26px; border: 1px solid rgba(255,255,255,0.25);"></span>
                                <span class="swatch-circle" style="background-color: #C0C0C0; width:26px; height:26px; border: 1px solid rgba(255,255,255,0.25);"></span>
                                <span class="swatch-circle" style="background-color: #5C5D61; width:26px; height:26px; border: 1px solid rgba(255,255,255,0.25);"></span>
                            </div>
                            <a href="{{ route('shop.show', 'lumen-vivid-color-cream-120ml') }}" class="btn btn-outline-light rounded-pill w-100 py-2 fs-8 fw-bold" style="border-color: rgba(255,255,255,0.4); backdrop-filter: blur(8px);">
                                Lihat Detail Shade Ini <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
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

<section class="position-relative text-white py-5 overflow-hidden" id="koleksi-parfum" style="background: linear-gradient(135deg, #090807 0%, #15110d 50%, #060504 100%); min-height: 560px; display: flex; align-items: center; border-top: 1px solid rgba(212, 175, 55, 0.15); border-bottom: 1px solid rgba(212, 175, 55, 0.15);">
    <div class="container-fluid px-lg-5 position-relative" style="z-index: 1;">
        <div class="row align-items-center g-5">
            <!-- Left Column: Perfume Full Image Showcase (Desk: Left / Mob: 2nd) -->
            <div class="col-lg-5 text-center order-2 order-lg-1">
                <div class="position-relative d-inline-block oxva-reveal oxva-reveal-scale delay-2">
                    <div class="hero-ambient-glow" style="background: radial-gradient(circle, rgba(212, 175, 55, 0.32) 0%, rgba(180, 83, 9, 0.16) 45%, transparent 70%); width: 440px; height: 440px;"></div>
                    <a href="{{ route('shop.show', 'lumen-extrait-de-parfum-santal-blanc-amber') }}" class="d-block text-decoration-none position-relative" style="z-index: 1;">
                        <div class="perfume-full-card position-relative overflow-hidden" style="width: 370px; max-width: 100%; border-radius: 26px; border: 1px solid rgba(212, 175, 55, 0.3); box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.8), 0 0 35px rgba(212, 175, 55, 0.15); background: #12100e;">
                            <img src="{{ asset('storage/variants/santal_blanc_50.jpg') }}" alt="LUMEN Extrait de Parfum - Santal Blanc & Amber" style="width: 100%; height: 480px; object-fit: cover; display: block;" class="perfume-bottle-hover">
                            <div class="position-absolute bottom-0 start-0 w-100 p-4 text-start" style="background: linear-gradient(180deg, transparent 0%, rgba(8, 7, 6, 0.65) 40%, rgba(6, 5, 4, 0.95) 100%);">
                                <h3 class="text-white font-serif mb-1 fs-4">Santal Blanc & Amber</h3>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="fs-7 fw-semibold" style="color: #f5d77f;">Extrait de Parfum &bull; Rp 195.000</span>
                                    <span class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 fs-8 fw-bold" style="border-color: rgba(212,175,55,0.5); color: #fbf7ee;">
                                        Lihat Detail <i class="bi bi-chevron-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Right Column: Headline, Copy, CTAs (Desk: Right / Mob: 1st) -->
            <div class="col-lg-7 oxva-reveal ps-lg-5 order-1 order-lg-2">
                <h2 class="display-3 fw-bold text-white mb-3 font-serif" style="letter-spacing: -0.02em; line-height: 1.1;">
                    THE ART OF <span style="background: linear-gradient(135deg, #fce8a6 0%, #d4af37 50%, #c49a6c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PURE ESSENCE.</span>
                </h2>
                <p class="lead text-light mb-4 fs-6 pe-lg-5" style="opacity: 0.88; max-width: 600px; line-height: 1.65;">
                    Koleksi wewangian mewah Extrait de Parfum dan Hair & Body Mist dengan konsentrat minyak wangi murni Prancis hingga 35%. Menghadirkan jejak aroma woody, floral, dan amber yang memikat, tahan hingga 14+ jam di kulit serta aman tanpa membuat rambut menjadi kering.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('shop.category', 'parfum-fragrance') }}" class="oxva-btn-primary" style="background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%); color: #0b0a08; border: none; font-weight: 700;">
                        Jelajahi Koleksi Parfum <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('shop.show', 'lumen-hair-body-fragrance-mist-100ml') }}" class="oxva-btn-outline" style="border-color: rgba(212,175,55,0.45); color: #fbf7ee;">
                        Hair & Body Mist
                    </a>
                    <a href="{{ route('shop.show', 'lumen-extrait-de-parfum-velvet-rose-smoked-oud') }}" class="btn btn-sm btn-link text-white-50 text-decoration-none d-flex align-items-center gap-1 fs-8">
                        Velvet Rose & Smoked Oud <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
                <div class="mt-4 pt-3 d-flex flex-wrap align-items-center gap-4 fs-8 text-white-50">
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2" style="color: #d4af37;"></i> 35% Pure Extrait Oil</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2" style="color: #d4af37;"></i> 14+ Jam Longevity</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2" style="color: #d4af37;"></i> Non-Drying Safe Mist</span>
                    <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-check2" style="color: #d4af37;"></i> BPOM Certified</span>
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
                <a href="{{ route('shop.formulaCalculator') }}" class="btn btn-sm btn-dark rounded-pill px-3 py-2 fw-semibold fs-8">
                    <i class="bi bi-magic text-warning me-1"></i> Formula Mixer
                </a>
                <a href="{{ route('shop.shadeGuide') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-semibold fs-8">
                    <i class="bi bi-palette me-1"></i> Color Chart
                </a>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4 oxva-reveal">
            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fs-8 fw-semibold catalog-filter-btn active" onclick="window.filterCatalogByCategory('all', this)">
                Semua Formulasi ({{ $featuredProducts->count() }})
            </button>
            @foreach($categories as $cat)
                @php
                    $catCount = $featuredProducts->where('category_id', $cat->id)->count();
                @endphp
                @if($catCount > 0)
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fs-8 fw-semibold catalog-filter-btn" onclick="window.filterCatalogByCategory('{{ $cat->slug }}', this)">
                        {{ $cat->name }} ({{ $catCount }})
                    </button>
                @endif
            @endforeach
        </div>

        <div class="row g-4" id="catalogGrid">
            @forelse($featuredProducts as $product)
            @php
                $staggerDelay = ($loop->index % 4) + 1;
                $firstVariant = $product->activeVariants->first();
                $hasColorCode = $firstVariant && $firstVariant->color_code;
                $catSlug = $product->category->slug ?? 'uncategorized';
            @endphp
            <div class="col-6 col-md-4 col-lg-3 oxva-reveal delay-{{ $staggerDelay }} catalog-product-item" data-category="{{ $catSlug }}">
                <div class="product-card">
                    <div class="img-wrapper" id="cardImgWrapper_{{ $product->id }}">
                        @if($product->thumbnail_url)
                            <img id="cardImg_{{ $product->id }}" src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-center p-3 card-gradient-preview" id="cardPlaceholder_{{ $product->id }}" style="background: {{ $hasColorCode ? 'linear-gradient(180deg, ' . $firstVariant->color_code . ' 0%, #171717 100%)' : '#f0f0f4' }}; color: {{ $hasColorCode ? '#fff' : '#111' }};">
                                <div>
                                    <div class="fs-6 font-serif fw-bold">{{ $product->name }}</div>
                                    <div class="fs-8 opacity-75 mt-1 card-variant-title" id="cardVariantTitle_{{ $product->id }}">{{ $firstVariant->color_name ?? 'Formula' }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-1">
                                @foreach($product->activeVariants->take(5) as $vIndex => $v)
                                    @if($v->image_url || $v->color_code)
                                        <button type="button" class="swatch-circle-btn {{ $vIndex === 0 ? 'active' : '' }}" style="{{ $v->image_url ? 'background-image: url(' . $v->image_url . '); background-size: cover;' : ('background-color: ' . ($v->color_code ?? '#444') . ';') }}" title="{{ $v->color_name ?? $v->name }}" onclick="window.switchCardVariant({{ $product->id }}, {{ $v->id }}, '{{ addslashes($v->color_name ?? $v->name) }}', '{{ $v->color_code }}', '{{ $v->formatted_price }}', {{ $v->stock }}, this, '{{ $v->image_url ?? '' }}')" aria-label="{{ $v->color_name ?? $v->name }}"></button>
                                    @endif
                                @endforeach
                                @if($product->activeVariants->count() > 5)
                                    <span class="fs-8 text-muted ms-1">+{{ $product->activeVariants->count() - 5 }}</span>
                                @endif
                            </div>
                            <span class="fs-8 text-muted text-uppercase fw-semibold tracking-wide" id="cardShadeLabel_{{ $product->id }}">
                                {{ $firstVariant->color_name ?? ($product->category ? $product->category->name : '') }}
                            </span>
                        </div>

                        <h5 class="fs-6 fw-bold mb-1">
                            <a href="{{ route('shop.show', $product->slug) }}" id="cardDetailLink_{{ $product->id }}" data-base-url="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <p class="fs-8 text-muted mb-3 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->summary ?? 'Formula pewarna rambut intens salon grade dengan kilau lembut.' }}
                        </p>

                        <div class="mt-auto pt-2 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-8 text-muted d-block">Mulai dari</span>
                                <span class="fs-6 fw-bolder text-dark" id="cardPrice_{{ $product->id }}">{{ $firstVariant ? $firstVariant->formatted_price : $product->formatted_price }}</span>
                            </div>

                            <div id="cardActionWrap_{{ $product->id }}">
                                @if($firstVariant && $firstVariant->stock > 0)
                                    <button type="button" class="btn-card-action" onclick="window.addToCart({{ $firstVariant->id }}, 1)">
                                        + Beli
                                    </button>
                                @elseif($firstVariant && $firstVariant->stock <= 0)
                                    <button type="button" class="btn-card-action-outline disabled" disabled>
                                        Habis
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
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('shop.category', 'bleaching-developers') }}" class="btn btn-brand-dark rounded-pill px-4 py-3">
                        Lihat Koleksi Bleach & Developer <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('shop.formulaCalculator') }}" class="btn btn-outline-dark rounded-pill px-4 py-3">
                        <i class="bi bi-magic text-warning me-1"></i> Racik Formula Mandiri
                    </a>
                </div>
            </div>
            <div class="col-lg-6 oxva-reveal oxva-reveal-right delay-2">
                <div class="atelier-guide-card">
                    <div class="mb-3">
                        <h5 class="font-serif fw-bold text-white mb-0 fs-5">Panduan Singkat Level Rambut</h5>
                    </div>
                    <p class="fs-8 text-white-50 mb-4">Pahami kanvas dasar helai rambut Anda sebelum mengaplikasikan formula tone impian.</p>
                    <div class="d-flex flex-column mb-4">
                        <div class="level-row-item" style="background: rgba(28, 28, 28, 0.85);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #111; border: 1px solid #444; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 1 - 3: Hitam / Dark Brown</span>
                            </div>
                            <span class="fs-8 text-white-50">Perlu Bleach ke Lvl 8+</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(99, 63, 39, 0.45);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #633f27; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 4 - 6: Medium Brown</span>
                            </div>
                            <span class="fs-8 text-white-50">Burgundy & Warm Brown</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(216, 176, 86, 0.25);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #d8b056; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 7 - 8: Golden Blonde</span>
                            </div>
                            <span class="fs-8 text-white-50">Rose Gold & Milk Tea</span>
                        </div>
                        <div class="level-row-item" style="background: rgba(247, 237, 212, 0.2);">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 14px; height: 14px; border-radius: 50%; background: #f7edd4; display: inline-block;"></span>
                                <span class="fs-8 fw-bold text-white">Level 9 - 10: Pale Platinum</span>
                            </div>
                            <span class="fs-8 text-white-50">Nordic Ash Grey</span>
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
                <h2 class="display-6 font-serif fw-bold text-white mb-3">Kemitraan Logistik Otomatis dengan KiriminAja</h2>
                <p class="fs-6 text-light opacity-75 mx-auto mb-4" style="max-width: 680px; line-height: 1.6;">
                    Pesanan Anda langsung diteruskan ke kurir pilihan (J&T Express, SiCepat, JNE) dengan penjemputan berkala dari warehouse Jakarta Selatan. Anda dapat memantau pergerakan paket secara real-time kapan saja.
                </p>
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
