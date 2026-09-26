@extends('shop.layouts.app')

@section('title', $category->name . ' | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-light py-4 border-bottom">
    <div class="container-fluid px-lg-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 fs-8 text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-dark text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        <h1 class="font-serif fw-bold text-dark display-6 mb-1">{{ $category->name }}</h1>
        <p class="text-muted fs-7 mb-0">{{ $category->description ?? 'Koleksi formula salon profesional pilihan untuk hasil maksimal di rumah.' }}</p>
    </div>
</div>

<div class="container-fluid px-lg-5 py-5">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="p-4 border bg-white sticky-top rounded-4 shadow-sm" style="top: 90px; z-index: 10;">
                <h6 class="text-uppercase fw-bold fs-7 tracking-wider mb-3">Kategori Produk</h6>
                <div class="d-flex flex-column gap-2 mb-4">
                    @foreach($categories as $cat)
                        <a href="{{ route('shop.category', $cat->slug) }}" class="text-decoration-none fs-7 py-2 px-2 rounded-2 d-flex justify-content-between align-items-center {{ $cat->id === $category->id ? 'fw-bold text-dark bg-light border-start border-3 border-dark ps-2' : 'text-muted' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="badge rounded-pill bg-light text-dark border">{{ $cat->products_count ?? $cat->products()->where('is_active', true)->count() }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="border-top pt-4">
                    <h6 class="text-uppercase fw-bold fs-7 tracking-wider mb-2">Panduan Rambut</h6>
                    <p class="fs-8 text-muted mb-3">Bingung menentukan level bleaching sebelum aplikasi warna?</p>
                    <a href="{{ route('shop.shadeGuide') }}" class="btn btn-outline-dark btn-sm rounded-pill w-100 py-2 fw-semibold">
                        Buka Shade Finder <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom oxva-reveal">
                <span class="fs-7 text-muted">Menampilkan {{ $products->total() }} formula pewarna</span>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                @php
                    $staggerDelay = ($loop->index % 3) + 1;
                    $firstVariant = $product->activeVariants->first();
                    $hasColorCode = $firstVariant && $firstVariant->color_code;
                @endphp
                <div class="col-6 col-md-4 oxva-reveal delay-{{ $staggerDelay }}">
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
                                    <span class="fs-8 text-muted d-block">Harga</span>
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
                    <p class="text-muted">Tidak ada produk dalam kategori ini.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
