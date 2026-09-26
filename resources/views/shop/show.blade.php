@extends('shop.layouts.app')

@section('title', $product->name . ' | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-light py-3 border-bottom">
    <div class="container-fluid px-lg-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-8 text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-dark text-decoration-none">Beranda</a></li>
                @if($product->category)
                    <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}" class="text-dark text-decoration-none">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid px-lg-5 py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="position-sticky" style="top: 100px;">
                <div class="border bg-light text-center mb-3 rounded-4 shadow-sm overflow-hidden" style="aspect-ratio: 1/1.1; display: flex; align-items: center; justify-content: center;" id="mainImageContainer">
                    @php
                        $firstVariant = $product->activeVariants->first();
                        $hasColorCode = $firstVariant && $firstVariant->color_code;
                    @endphp
                    @if($product->primaryImage)
                        <img src="{{ str_starts_with($product->primaryImage->image_path, 'http') ? $product->primaryImage->image_path : asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" id="mainProductImage" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="mainSwatchBanner" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-white" style="background: {{ $hasColorCode ? 'linear-gradient(135deg, ' . $firstVariant->color_code . ' 0%, #151515 100%)' : '#222' }};">
                            <span class="fs-7 text-uppercase tracking-widest opacity-75">LUMEN Hair Color Atelier</span>
                            <h2 class="display-6 font-serif fw-bold mt-2" id="heroVariantTitle">{{ $firstVariant->name ?? $product->name }}</h2>
                            <p class="fs-7 opacity-75 mt-2" id="heroVariantColor">{{ $firstVariant->color_name ?? 'Formula Khusus' }}</p>
                        </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                <div class="d-flex gap-2 overflow-auto pb-2">
                    @foreach($product->images as $img)
                        <div class="border rounded-3 overflow-hidden shadow-sm" style="width: 70px; height: 70px; cursor: pointer; flex-shrink: 0;" onclick="document.getElementById('mainProductImage').src='{{ str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path) }}'">
                            <img src="{{ str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div>
                @if($product->category)
                    <div class="text-uppercase tracking-wider fs-8 text-muted fw-bold mb-2">
                        {{ $product->category->name }}
                    </div>
                @endif

                <h1 class="font-serif fw-bold text-dark display-6 mb-2">{{ $product->name }}</h1>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="fs-4 fw-bold text-dark" id="displayPrice">{{ $product->formatted_price }}</div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-8" id="displayStock">
                        Stok Tersedia
                    </span>
                </div>

                <p class="text-secondary fs-7 mb-4">
                    {{ $product->summary ?? $product->description }}
                </p>

                <div class="border-top border-bottom py-4 mb-4">
                    <h6 class="text-uppercase fw-bold fs-7 tracking-wider mb-2">
                        Pilihan Shade / Varian Warna:
                        <span class="text-primary fw-normal ms-1" id="selectedVariantName">
                            {{ $firstVariant->color_name ?? $firstVariant->name ?? 'Default' }}
                        </span>
                    </h6>

                    <div class="d-flex flex-wrap gap-2 mb-4" id="variantSelectorGroup">
                        @foreach($product->activeVariants as $index => $variant)
                            <button type="button" 
                                class="swatch-btn variant-option-btn {{ $index === 0 ? 'active' : '' }}" 
                                data-id="{{ $variant->id }}" 
                                data-name="{{ $variant->name }}"
                                data-color-name="{{ $variant->color_name ?? $variant->name }}"
                                data-color-code="{{ $variant->color_code ?? '#333333' }}"
                                data-price="{{ $variant->formatted_price }}"
                                data-stock="{{ $variant->stock }}"
                                data-weight="{{ $variant->effective_weight }}"
                                title="{{ $variant->name }}">
                                <span style="background-color: {{ $variant->color_code ?? '#444444' }};"></span>
                            </button>
                        @endforeach
                    </div>

                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-sm-4">
                            <label class="form-label fs-8 text-uppercase fw-bold text-muted mb-1">Jumlah</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary rounded-0 px-3" type="button" id="btnQtyMinus">-</button>
                                <input type="number" id="inputQuantity" class="form-control text-center rounded-0 bg-white" value="1" min="1" max="20" readonly>
                                <button class="btn btn-outline-secondary rounded-0 px-3" type="button" id="btnQtyPlus">+</button>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="fs-8 text-muted mt-sm-4">
                                <i class="bi bi-box me-1"></i> Berat Bersih: <span id="displayWeight">{{ $firstVariant->effective_weight ?? 250 }}</span> gram
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <button type="button" class="btn btn-brand-dark rounded-pill flex-grow-1 py-3" id="btnAddToCart">
                            <i class="bi bi-bag-plus me-2"></i> Tambah ke Tas Belanja
                        </button>
                        <button type="button" class="btn btn-brand-outline rounded-pill flex-grow-1 py-3" id="btnBuyNow">
                            Beli Sekarang
                        </button>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-4 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-truck fs-4 text-dark"></i>
                            <span class="fw-bold fs-7 text-uppercase">Cek Tarif Ekspedisi KiriminAja</span>
                        </div>
                        <p class="fs-8 text-muted mb-3">Estimasi ongkos kirim dari Gudang Pusat Kebayoran Baru, Jakarta Selatan ke lokasi Anda.</p>
                        
                        <div class="row g-2">
                            <div class="col-8">
                                <select id="shippingDestSelect" class="form-select form-select-sm rounded-pill px-3">
                                    <option value="2108">Tebet, Kota Jakarta Selatan</option>
                                    <option value="2110">Gambir, Kota Jakarta Pusat</option>
                                    <option value="2189">Bekasi Barat, Kota Bekasi</option>
                                    <option value="2195">Bogor Tengah, Kota Bogor</option>
                                    <option value="2210">Coblong, Kota Bandung</option>
                                    <option value="2305">Banyumanik, Kota Semarang</option>
                                    <option value="2380">Depok, Kab. Sleman (DIY)</option>
                                    <option value="2450">Gubeng, Kota Surabaya</option>
                                    <option value="2600">Denpasar Selatan, Kota Denpasar</option>
                                    <option value="1200">Medan Kota, Kota Medan</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="button" id="btnCheckPdpShipping" class="btn btn-sm btn-dark rounded-pill w-100">
                                    Cek Tarif
                                </button>
                            </div>
                        </div>

                        <div id="pdpShippingResults" class="mt-3 fs-8" style="display: none;">
                            <div class="border-top pt-2" id="pdpShippingList"></div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-flush" id="pdpAccordion">
                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-bold fs-7 text-uppercase tracking-wider" type="button" data-bs-toggle="collapse" data-bs-target="#accFormula">
                                Formula & Keunggulan Produk
                            </button>
                        </h2>
                        <div id="accFormula" class="accordion-collapse collapse" data-bs-parent="#pdpAccordion">
                            <div class="accordion-body px-0 text-muted fs-7">
                                <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                                    <li><i class="bi bi-check-circle-fill text-dark me-2"></i> <strong>Ammonia Free:</strong> Nyaman tanpa sensasi perih di kulit kepala dan bebas aroma menyengat.</li>
                                    <li><i class="bi bi-check-circle-fill text-dark me-2"></i> <strong>Argan Oil Concentrated:</strong> Mengunci hidrasi dan mengembalikan kelembutan batang rambut.</li>
                                    <li><i class="bi bi-check-circle-fill text-dark me-2"></i> <strong>Keratin Peptide:</strong> Memperbaiki kutikula rambut yang terangkat selama proses pewarnaan.</li>
                                    <li><i class="bi bi-check-circle-fill text-dark me-2"></i> <strong>Nano Pigment:</strong> Partikel warna menyerap lebih dalam sehingga warna tidak cepat pudar.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-bold fs-7 text-uppercase tracking-wider" type="button" data-bs-toggle="collapse" data-bs-target="#accUsage">
                                Panduan Aplikasi & Petunjuk Pakai
                            </button>
                        </h2>
                        <div id="accUsage" class="accordion-collapse collapse" data-bs-parent="#pdpAccordion">
                            <div class="accordion-body px-0 text-muted fs-7">
                                <ol class="ps-3 mb-0 d-flex flex-column gap-2">
                                    <li>Pastikan rambut sudah dibleaching hingga level yang sesuai dengan shade yang dipilih (minimal level 8 untuk warna Ash/Grey).</li>
                                    <li>Campurkan pewarna rambut LUMEN dengan Developer Cream dengan rasio 1:1 di dalam mangkuk non-logam.</li>
                                    <li>Aplikasikan campuran secara merata dari pangkal hingga ujung rambut menggunakan kuas aplikator.</li>
                                    <li>Diamkan selama 35 - 45 menit untuk peresapan pigmen maksimal.</li>
                                    <li>Bilas dengan air bersih hingga air bilasan jernih, lalu lanjutkan dengan LUMEN Purple Shampoo & Conditioner.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-bold fs-7 text-uppercase tracking-wider" type="button" data-bs-toggle="collapse" data-bs-target="#accShipping">
                                Garansi Pengiriman & Kebijakan Toko
                            </button>
                        </h2>
                        <div id="accShipping" class="accordion-collapse collapse" data-bs-parent="#pdpAccordion">
                            <div class="accordion-body px-0 text-muted fs-7">
                                Seluruh pesanan dikemas aman dengan bubble wrap tebal dan box karton protektif. Resi diterbitkan secara otomatis melalui sistem KiriminAja segera setelah pembayaran terverifikasi. Kami menjamin penggantian produk baru jika paket mengalami kebocoran atau kerusakan selama perjalanan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="font-serif fw-bold text-dark mb-4 oxva-reveal">Lengkapi Perawatan Rambut Anda</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $rel)
            <div class="col-6 col-md-3 oxva-reveal delay-{{ ($loop->index % 4) + 1 }}">
                <div class="product-card">
                    <div class="img-wrapper">
                        @if($rel->primaryImage)
                            <img src="{{ str_starts_with($rel->primaryImage->image_path, 'http') ? $rel->primaryImage->image_path : asset('storage/' . $rel->primaryImage->image_path) }}" alt="{{ $rel->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted fs-7">
                                LUMEN
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 d-flex flex-column">
                        <h6 class="fs-7 fw-bold mb-1">
                            <a href="{{ route('shop.show', $rel->slug) }}" class="text-dark text-decoration-none">
                                {{ $rel->name }}
                            </a>
                        </h6>
                        <div class="fs-7 fw-bolder text-dark mb-3">{{ $rel->formatted_price }}</div>
                        <a href="{{ route('shop.show', $rel->slug) }}" class="btn-card-action-outline mt-auto text-center w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let selectedVariantId = {{ $firstVariant->id ?? 'null' }};
let selectedVariantWeight = {{ $firstVariant->effective_weight ?? 250 }};

document.querySelectorAll('.variant-option-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.variant-option-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        selectedVariantId = this.dataset.id;
        selectedVariantWeight = this.dataset.weight;

        document.getElementById('selectedVariantName').innerText = this.dataset.colorName;
        document.getElementById('displayPrice').innerText = this.dataset.price;
        document.getElementById('displayWeight').innerText = selectedVariantWeight;

        const stock = parseInt(this.dataset.stock);
        const stockEl = document.getElementById('displayStock');
        if (stock > 0) {
            stockEl.className = 'badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-8';
            stockEl.innerText = `Stok Tersedia (${stock})`;
            document.getElementById('btnAddToCart').disabled = false;
            document.getElementById('btnBuyNow').disabled = false;
        } else {
            stockEl.className = 'badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fs-8';
            stockEl.innerText = 'Stok Habis';
            document.getElementById('btnAddToCart').disabled = true;
            document.getElementById('btnBuyNow').disabled = true;
        }

        const banner = document.getElementById('mainSwatchBanner');
        if (banner) {
            banner.style.background = `linear-gradient(135deg, ${this.dataset.colorCode} 0%, #151515 100%)`;
            const titleEl = document.getElementById('heroVariantTitle');
            if (titleEl) titleEl.innerText = this.dataset.name;
            const colorEl = document.getElementById('heroVariantColor');
            if (colorEl) colorEl.innerText = this.dataset.colorName;
        }
    });
});

document.getElementById('btnQtyMinus')?.addEventListener('click', function() {
    const input = document.getElementById('inputQuantity');
    let val = parseInt(input.value) || 1;
    if (val > 1) input.value = val - 1;
});

document.getElementById('btnQtyPlus')?.addEventListener('click', function() {
    const input = document.getElementById('inputQuantity');
    let val = parseInt(input.value) || 1;
    if (val < 20) input.value = val + 1;
});

document.getElementById('btnAddToCart')?.addEventListener('click', function() {
    if (!selectedVariantId) return;
    const qty = parseInt(document.getElementById('inputQuantity').value) || 1;
    window.addToCart(selectedVariantId, qty);
});

document.getElementById('btnBuyNow')?.addEventListener('click', function() {
    if (!selectedVariantId) return;
    const qty = parseInt(document.getElementById('inputQuantity').value) || 1;

    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ variant_id: selectedVariantId, quantity: qty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "{{ route('checkout.index') }}";
        } else {
            window.showToast('error', data.message);
        }
    });
});

document.getElementById('btnCheckPdpShipping')?.addEventListener('click', function() {
    const btn = this;
    const destId = document.getElementById('shippingDestSelect').value;
    const resultsContainer = document.getElementById('pdpShippingResults');
    const list = document.getElementById('pdpShippingList');

    btn.disabled = true;
    btn.innerText = 'Memuat...';

    fetch("{{ route('checkout.shippingRates') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ destination_district_id: destId })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerText = 'Cek Tarif';

        if (data.success && data.rates) {
            list.innerHTML = '';
            data.rates.forEach(r => {
                list.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div>
                            <strong>${r.courier_name}</strong> <span class="text-muted">(${r.service_name})</span>
                            <span class="badge bg-light text-dark ms-1">${r.etd} Hari</span>
                        </div>
                        <div class="fw-bold text-dark">${r.formatted_cost}</div>
                    </div>
                `;
            });
            resultsContainer.style.display = 'block';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerText = 'Cek Tarif';
        window.showToast('error', 'Gagal memuat tarif kurir KiriminAja');
    });
});
</script>
@endpush
