@extends('shop.layouts.app')

@section('title', 'Kalkulator Formula Racikan Rambut | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-dark text-white py-4 py-lg-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #09090d 0%, #15141d 50%, #060608 100%);">
    <div class="container px-lg-5 position-relative" style="z-index: 1;">
        <div class="text-center mw-800px mx-auto">
            <div class="text-uppercase tracking-wider fs-8 text-white-50 fw-bold mb-2">
                Beauty-Tech Diagnostic Engine
            </div>
            <h1 class="display-6 display-lg-5 font-serif fw-bold text-white mb-2">Atelier Hair Color & Bleach Mixer</h1>
            <p class="text-light opacity-75 fs-7 fs-md-6 mx-auto mb-0" style="max-width: 650px; line-height: 1.6;">
                Pilih kanvas dasar helai rambut Anda saat ini dan warna impian yang diinginkan. Sistem formula salon kami akan mengkalkulasi kebutuhan lifting, rasio developer, estimasi durasi, dan meracik paket bahan lengkap yang siap Anda beli.
            </p>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-5 py-4 py-lg-5 pb-5 mb-5" style="background: #fdfdfd;">
    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="card border rounded-4 shadow-sm p-4 p-md-5 mb-4 bg-white">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom step-anchor" id="stepCanvas">
                    <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                    <div>
                        <h5 class="font-serif fw-bold text-dark mb-0">Kanvas Helai Rambut Anda Saat Ini</h5>
                        <span class="fs-8 text-muted">Pilih tingkat level warna rambut Anda yang paling mendekati saat ini</span>
                    </div>
                </div>

                @php
                    $levels = [
                        ['level' => 1, 'name' => 'Level 1: Jet Black', 'desc' => 'Hitam pekat alami', 'color' => '#0a0a0a', 'text_color' => '#ffffff'],
                        ['level' => 2, 'name' => 'Level 2: Darkest Brown', 'desc' => 'Hitam alami rambut Asia', 'color' => '#1f1b1a', 'text_color' => '#ffffff'],
                        ['level' => 3, 'name' => 'Level 3: Dark Brown', 'desc' => 'Cokelat tua pekat', 'color' => '#3b2f2f', 'text_color' => '#ffffff'],
                        ['level' => 4, 'name' => 'Level 4: Medium Brown', 'desc' => 'Cokelat sedang', 'color' => '#4e3b31', 'text_color' => '#ffffff'],
                        ['level' => 5, 'name' => 'Level 5: Light Brown', 'desc' => 'Cokelat terang / kemerahan', 'color' => '#6f4e37', 'text_color' => '#ffffff'],
                        ['level' => 6, 'name' => 'Level 6: Dark Blonde', 'desc' => 'Pirang gelap / oranye', 'color' => '#9e7540', 'text_color' => '#ffffff'],
                        ['level' => 7, 'name' => 'Level 7: Medium Blonde', 'desc' => 'Pirang sedang / kuning emas', 'color' => '#c89d5c', 'text_color' => '#111111'],
                        ['level' => 8, 'name' => 'Level 8: Light Blonde', 'desc' => 'Kuning terang bersih', 'color' => '#dfbe7a', 'text_color' => '#111111'],
                        ['level' => 9, 'name' => 'Level 9: Very Light Blonde', 'desc' => 'Kuning sangat pucat', 'color' => '#ebd79b', 'text_color' => '#111111'],
                        ['level' => 10, 'name' => 'Level 10: Platinum Blonde', 'desc' => 'Putih pucat bebas brass', 'color' => '#fbf3d5', 'text_color' => '#111111'],
                    ];
                @endphp

                <div class="row g-2 mb-4">
                    @foreach($levels as $lvl)
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                        <button type="button" 
                            class="level-card-btn w-100 text-start p-2 rounded-3 border {{ $lvl['level'] === 2 ? 'active' : '' }}" 
                            data-level="{{ $lvl['level'] }}"
                            onclick="selectBaseLevel({{ $lvl['level'] }}, this)">
                            <div class="level-swatch mb-2 rounded-2" style="background-color: {{ $lvl['color'] }}; height: 36px; border: 1px solid rgba(0,0,0,0.1);"></div>
                            <div class="fs-8 fw-bold text-dark text-truncate">Lvl {{ $lvl['level'] }}</div>
                            <div class="fs-9 text-muted text-truncate">{{ $lvl['desc'] }}</div>
                        </button>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom pt-3 step-anchor" id="stepLength">
                    <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                    <div>
                        <h5 class="font-serif fw-bold text-dark mb-0">Panjang & Ketebalan Rambut</h5>
                        <span class="fs-8 text-muted">Menentukan jumlah takaran tube cat dan gramasi bleach yang presisi</span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <button type="button" class="hair-length-btn w-100 p-3 rounded-3 border text-start" data-length="short" onclick="selectHairLength('short', this)">
                            <i class="bi bi-person fs-3 text-dark d-block mb-1"></i>
                            <div class="fw-bold fs-7 text-dark">Pendek / Pixie</div>
                            <div class="fs-8 text-muted">Kebutuhan: 1 Tube (60–120ml)</div>
                        </button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="hair-length-btn w-100 p-3 rounded-3 border text-start active" data-length="medium" onclick="selectHairLength('medium', this)">
                            <i class="bi bi-person-fill fs-3 text-dark d-block mb-1"></i>
                            <div class="fw-bold fs-7 text-dark">Sedang / Sebahu</div>
                            <div class="fs-8 text-muted">Kebutuhan: 1 Tube (120ml)</div>
                        </button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="hair-length-btn w-100 p-3 rounded-3 border text-start" data-length="long" onclick="selectHairLength('long', this)">
                            <i class="bi bi-person-lines-fill fs-3 text-dark d-block mb-1"></i>
                            <div class="fw-bold fs-7 text-dark">Panjang / Tebal</div>
                            <div class="fs-8 text-muted">Kebutuhan: 2 Tube (240ml)</div>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom pt-3 step-anchor" id="stepShade">
                    <span class="badge bg-dark text-white rounded-circle p-2" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">3</span>
                    <div>
                        <h5 class="font-serif fw-bold text-dark mb-0">Target Shade Warna Impian</h5>
                        <span class="fs-8 text-muted">Pilih tone warna yang ingin Anda capai dari koleksi salon LUMEN</span>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($targetVariants as $index => $tVar)
                    <div class="col-6 col-sm-4">
                        <button type="button" 
                            class="target-shade-btn w-100 p-3 rounded-3 border text-start {{ $index === 0 ? 'active' : '' }}" 
                            data-variant-id="{{ $tVar->id }}"
                            onclick="selectTargetShade({{ $tVar->id }}, this)">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="swatch-circle" style="background-color: {{ $tVar->color_code }}; width: 22px; height: 22px;"></span>
                                <span class="fs-8 fw-bold text-dark text-truncate">{{ $tVar->name }}</span>
                            </div>
                            <div class="fs-8 text-muted">{{ $tVar->formatted_price }}</div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="prescription-card-wrapper" id="prescriptionWrapper">
                <div class="card border rounded-4 shadow-sm bg-white overflow-hidden prescription-card-inner" id="prescriptionCard">
                    <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center flex-shrink-0">
                        <div>
                            <span class="fs-8 text-white-50 text-uppercase fw-bold tracking-wider d-block">Resep Formulasi Laboratorium</span>
                            <h5 class="font-serif fw-bold text-white mb-0">Atelier Prescription Protocol</h5>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark rounded-pill fs-8 fw-bold" id="badgeDeltaLift">
                                Lift: +{{ $initialFormula['hair_analysis']['delta_lift'] ?? 7 }} Level
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4 custom-scroll-area">
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <div class="row g-2 text-center">
                                <div class="col-5">
                                    <span class="fs-8 text-muted d-block text-uppercase fw-bold">Kanvas Awal</span>
                                    <span class="fs-7 fw-bold text-dark" id="displayCurrentLevel">Level {{ $initialFormula['hair_analysis']['current_level'] ?? 2 }}</span>
                                </div>
                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-right fs-5 text-muted"></i>
                                </div>
                                <div class="col-5">
                                    <span class="fs-8 text-muted d-block text-uppercase fw-bold">Target Warna</span>
                                    <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                                        <span id="displayTargetColorSwatch" style="width: 12px; height: 12px; border-radius: 50%; background: {{ $initialFormula['hair_analysis']['target_color_code'] ?? '#8D99AE' }}; display: inline-block;"></span>
                                        <span class="fs-7 fw-bold text-dark" id="displayTargetName">{{ $initialFormula['hair_analysis']['target_name'] ?? 'Ash Grey' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fs-8 text-uppercase fw-bold text-muted mb-3 tracking-wider">Tahapan Protokol Pewarnaan</h6>
                            <div class="d-flex flex-column gap-3" id="protocolStepsList">
                                @if(!empty($initialFormula['steps']))
                                    @foreach($initialFormula['steps'] as $st)
                                    <div class="p-3 border rounded-3 bg-white shadow-xs">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fs-8 fw-bold text-dark">{{ $st['phase'] }}</span>
                                            <span class="badge bg-light text-dark border fs-9 rounded-pill">{{ $st['timer'] }}</span>
                                        </div>
                                        <p class="fs-8 text-muted mb-1" style="line-height: 1.4;">{{ $st['instruction'] }}</p>
                                        <div class="fs-9 text-dark fw-semibold">
                                            <i class="bi bi-check2-circle text-success me-1"></i>{{ $st['target_canvas'] }}
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fs-8 text-uppercase fw-bold text-muted mb-0 tracking-wider">Paket Produk yang Dibutuhkan</h6>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-9 rounded-pill fw-bold">Diskon Bundle 10%</span>
                            </div>

                            <div class="d-flex flex-column gap-2 mb-3" id="bundleItemsList">
                                @if(!empty($initialFormula['bundle']['items']))
                                    @foreach($initialFormula['bundle']['items'] as $bItem)
                                    <div class="d-flex align-items-center justify-content-between fs-8 py-1 border-bottom">
                                        <div>
                                            <span class="fw-bold text-dark">{{ $bItem['product_name'] }}</span>
                                            <span class="text-muted d-block fs-9">{{ $bItem['variant_name'] }} ({{ $bItem['quantity'] }}x)</span>
                                        </div>
                                        <div class="fw-semibold text-dark">{{ $bItem['formatted_subtotal'] }}</div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-1 fs-8 text-muted">
                                <span>Harga Normal</span>
                                <del id="bundleNormalPrice">{{ $initialFormula['bundle']['formatted_total_normal'] ?? 'Rp 0' }}</del>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="fs-7 fw-bold text-dark d-block">Total Paket Bundle</span>
                                    <span class="fs-9 text-success fw-bold" id="bundleSavings">Hemat {{ $initialFormula['bundle']['formatted_discount_amount'] ?? 'Rp 0' }}</span>
                                </div>
                                <div class="fs-5 fw-bolder text-dark" id="bundleFinalPrice">
                                    {{ $initialFormula['bundle']['formatted_bundle_total'] ?? 'Rp 0' }}
                                </div>
                            </div>

                            <button type="button" class="btn btn-dark w-100 py-3 rounded-pill fw-bold fs-7 shadow-sm text-uppercase" id="btnAddBundleToCart" onclick="submitBundleToCart()">
                                <i class="bi bi-bag-plus me-2 text-warning"></i> Beli 1 Paket Formula Ini
                            </button>
                            <p class="fs-9 text-muted text-center mt-2 mb-0">Semua item otomatis masuk ke keranjang belanja Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
html {
    scroll-behavior: smooth;
}
body {
    overflow-y: auto !important;
}
.step-anchor, #prescriptionCard {
    scroll-margin-top: 130px;
}
.level-card-btn, .hair-length-btn, .target-shade-btn {
    background: #ffffff;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.level-card-btn:hover, .hair-length-btn:hover, .target-shade-btn:hover {
    border-color: #111111 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}
.level-card-btn.active, .hair-length-btn.active, .target-shade-btn.active {
    border-color: #111111 !important;
    background-color: #f7f7fa !important;
    box-shadow: 0 0 0 2px #111111;
}
.fs-9 {
    font-size: 0.72rem;
}
.prescription-card-wrapper {
    position: sticky;
    top: 95px;
    z-index: 10;
}
.prescription-card-inner {
    max-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}
.custom-scroll-area {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.custom-scroll-area::-webkit-scrollbar {
    width: 6px;
}
.custom-scroll-area::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scroll-area::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 999px;
}
@media (max-width: 991.98px) {
    .prescription-card-wrapper {
        position: static !important;
        max-height: none !important;
    }
    .prescription-card-inner {
        max-height: none !important;
    }
    .custom-scroll-area {
        overflow-y: visible !important;
        max-height: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentSelectedLevel = 2;
let currentSelectedLength = 'medium';
let currentSelectedTargetVariant = {{ $initialFormula['hair_analysis']['target_variant_id'] ?? ($targetVariants->first()->id ?? 1) }};
let currentBundleItems = @json($initialFormula['bundle']['items'] ?? []);

function selectBaseLevel(lvl, btn) {
    document.querySelectorAll('.level-card-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentSelectedLevel = lvl;
    fetchFormulaCalculation();
    const nextStep = document.getElementById('stepLength');
    if (nextStep) {
        nextStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function selectHairLength(len, btn) {
    document.querySelectorAll('.hair-length-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentSelectedLength = len;
    fetchFormulaCalculation();
    const nextStep = document.getElementById('stepShade');
    if (nextStep) {
        nextStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function selectTargetShade(variantId, btn) {
    document.querySelectorAll('.target-shade-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentSelectedTargetVariant = variantId;
    fetchFormulaCalculation();
    if (window.innerWidth < 992) {
        const nextStep = document.getElementById('prescriptionCard');
        if (nextStep) {
            nextStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

function fetchFormulaCalculation() {
    const payload = {
        current_level: currentSelectedLevel,
        hair_length: currentSelectedLength,
        target_variant_id: currentSelectedTargetVariant
    };

    fetch("{{ route('shop.formulaCalculator.calculate') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success && res.data) {
            updateFormulaUI(res.data);
        }
    })
    .catch(() => {
        window.showToast('error', 'Gagal menghitung racikan formula');
    });
}

function updateFormulaUI(data) {
    const analysis = data.hair_analysis;
    const bundle = data.bundle;
    const steps = data.steps;

    currentBundleItems = bundle.items;

    document.getElementById('badgeDeltaLift').innerText = `Lift: +${analysis.delta_lift} Level`;
    document.getElementById('displayCurrentLevel').innerText = `Level ${analysis.current_level}`;
    document.getElementById('displayTargetName').innerText = analysis.target_name;
    document.getElementById('displayTargetColorSwatch').style.backgroundColor = analysis.target_color_code || '#8D99AE';

    let stepsHtml = '';
    steps.forEach(st => {
        stepsHtml += `
            <div class="p-3 border rounded-3 bg-white shadow-xs">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fs-8 fw-bold text-dark">${st.phase}</span>
                    <span class="badge bg-light text-dark border fs-9 rounded-pill">${st.timer}</span>
                </div>
                <p class="fs-8 text-muted mb-1" style="line-height: 1.4;">${st.instruction}</p>
                <div class="fs-9 text-dark fw-semibold">
                    <i class="bi bi-check2-circle text-success me-1"></i>${st.target_canvas}
                </div>
            </div>
        `;
    });
    document.getElementById('protocolStepsList').innerHTML = stepsHtml;

    let bundleHtml = '';
    bundle.items.forEach(bi => {
        bundleHtml += `
            <div class="d-flex align-items-center justify-content-between fs-8 py-1 border-bottom">
                <div>
                    <span class="fw-bold text-dark">${bi.product_name}</span>
                    <span class="text-muted d-block fs-9">${bi.variant_name} (${bi.quantity}x)</span>
                </div>
                <div class="fw-semibold text-dark">${bi.formatted_subtotal}</div>
            </div>
        `;
    });
    document.getElementById('bundleItemsList').innerHTML = bundleHtml;

    document.getElementById('bundleNormalPrice').innerText = bundle.formatted_total_normal;
    document.getElementById('bundleSavings').innerText = `Hemat ${bundle.formatted_discount_amount}`;
    document.getElementById('bundleFinalPrice').innerText = bundle.formatted_bundle_total;
}

function submitBundleToCart() {
    if (!currentBundleItems || currentBundleItems.length === 0) {
        window.showToast('error', 'Tidak ada item dalam racikan formula');
        return;
    }

    const btn = document.getElementById('btnAddBundleToCart');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses Paket...';

    const itemsPayload = currentBundleItems.map(item => ({
        variant_id: item.variant_id,
        quantity: item.quantity
    }));

    fetch("{{ route('cart.addBundle') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ items: itemsPayload })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-bag-plus me-2 text-warning"></i> Beli 1 Paket Formula Ini';

        if (data.success) {
            window.showToast('success', data.message);
            window.refreshCartDrawer();
            const offcanvasEl = document.getElementById('cartOffcanvas');
            if (offcanvasEl) {
                const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                offcanvas.show();
            }
        } else {
            window.showToast('error', data.message || 'Gagal menambahkan bundle');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-bag-plus me-2 text-warning"></i> Beli 1 Paket Formula Ini';
        window.showToast('error', 'Terjadi kesalahan sistem saat memproses bundle');
    });
}
</script>
@endpush
