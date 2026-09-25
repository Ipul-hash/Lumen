@extends('shop.layouts.app')

@section('title', 'Panduan Level Rambut & Shade Finder | LUMEN Hair Color Atelier')

@section('content')
<div class="bg-dark text-white py-5 text-center">
    <div class="container px-lg-5">
        <span class="badge bg-light text-dark text-uppercase tracking-wider px-3 py-2 mb-2 fw-bold fs-8">Atelier Color Theory</span>
        <h1 class="display-5 font-serif fw-bold mb-2">Panduan Level Bleaching & Shade Finder</h1>
        <p class="text-light opacity-75 fs-6 mx-auto mb-0" style="max-width: 650px;">
            Dapatkan hasil warna rambut yang presisi seperti di salon profesional dengan memahami level warna dasar rambut Anda.
        </p>
    </div>
</div>

<div class="container px-lg-5 py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <h3 class="font-serif fw-bold text-dark mb-3">Tabel Level Rambut Alami & Kanvas Bleaching</h3>
            <p class="text-muted fs-7 mb-4">
                Pewarna rambut bekerja dengan prinsip transparansi pigmen di atas warna dasar. Semakin terang kanvas dasar rambut Anda, semakin murni dan cerah pantulan warna yang dihasilkan.
            </p>

            <div class="d-flex flex-column gap-3 mb-5">
                <div class="p-3 border d-flex align-items-center gap-3" style="border-left: 8px solid #111111 !important;">
                    <div style="width: 50px; height: 50px; background-color: #111111; flex-shrink: 0;" class="border"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">Level 1 - 2: Jet Black & Darkest Brown</h6>
                            <span class="badge bg-dark fs-8">Dasar Rambut Asia Alami</span>
                        </div>
                        <p class="fs-8 text-muted mb-0 mt-1">Mengandung konsentrasi eumelanin (pigmen hitam/cokelat) sangat padat. Memerlukan bleaching untuk warna terang.</p>
                    </div>
                </div>

                <div class="p-3 border d-flex align-items-center gap-3" style="border-left: 8px solid #3d2314 !important;">
                    <div style="width: 50px; height: 50px; background-color: #3d2314; flex-shrink: 0;" class="border"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">Level 3 - 4: Dark Brown & Medium Brown</h6>
                            <span class="badge bg-secondary fs-8">Warna Cokelat Alami</span>
                        </div>
                        <p class="fs-8 text-muted mb-0 mt-1">Sangat cocok untuk varian warna Burgundy, Espresso Rich, dan Mahogany tanpa bleaching berlebih.</p>
                    </div>
                </div>

                <div class="p-3 border d-flex align-items-center gap-3" style="border-left: 8px solid #824b28 !important;">
                    <div style="width: 50px; height: 50px; background-color: #824b28; flex-shrink: 0;" class="border"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">Level 5 - 6: Light Brown & Dark Blonde</h6>
                            <span class="badge bg-warning bg-opacity-25 text-dark fs-8">Undercoat Merah-Oranye</span>
                        </div>
                        <p class="fs-8 text-muted mb-0 mt-1">Ideal untuk warna Caramel, Copper Brown, dan Chestnut.</p>
                    </div>
                </div>

                <div class="p-3 border d-flex align-items-center gap-3" style="border-left: 8px solid #d4a244 !important;">
                    <div style="width: 50px; height: 50px; background-color: #d4a244; flex-shrink: 0;" class="border"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">Level 7 - 8: Medium Blonde & Light Blonde</h6>
                            <span class="badge bg-warning text-dark fs-8">Undercoat Kuning Emas</span>
                        </div>
                        <p class="fs-8 text-muted mb-0 mt-1">Kanvas sempurna untuk Rose Gold, Lilac, Milk Tea, dan Honey Blonde.</p>
                    </div>
                </div>

                <div class="p-3 border d-flex align-items-center gap-3" style="border-left: 8px solid #f6eed6 !important;">
                    <div style="width: 50px; height: 50px; background-color: #f6eed6; flex-shrink: 0;" class="border"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">Level 9 - 10: Very Light & Pale Platinum Blonde</h6>
                            <span class="badge bg-info bg-opacity-25 text-info-emphasis fs-8">Undercoat Kuning Pucat / Putih</span>
                        </div>
                        <p class="fs-8 text-muted mb-0 mt-1">Wajib dicapai untuk varian warna <strong>Nordic Ash Grey</strong>, Silver Chrome, dan Pastel.</p>
                    </div>
                </div>
            </div>

            <h3 class="font-serif fw-bold text-dark mb-3">Panduan Memilih Developer Cream</h3>
            <div class="table-responsive mb-5">
                <table class="table table-bordered fs-7">
                    <thead class="bg-light">
                        <tr class="text-uppercase fs-8 fw-bold">
                            <th>Volume</th>
                            <th>Kandungan Peroksida</th>
                            <th>Fungsi Utama</th>
                            <th>Rekomendasi Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">10 Vol</td>
                            <td>3% Hydrogen Peroxide</td>
                            <td>Deposit warna saja (tanpa pengangkatan warna)</td>
                            <td>Toning warna setelah bleaching atau menutup uban</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">20 Vol</td>
                            <td>6% Hydrogen Peroxide</td>
                            <td>Mengangkat 1-2 level & deposit pigmen</td>
                            <td>Paling serbaguna untuk seluruh seri cat permanen</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">30 Vol</td>
                            <td>9% Hydrogen Peroxide</td>
                            <td>Mengangkat 2-3 level</td>
                            <td>Campuran Bleaching Powder untuk rambut gelap tebal</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">40 Vol</td>
                            <td>12% Hydrogen Peroxide</td>
                            <td>Mengangkat hingga 4 level</td>
                            <td>Hanya disarankan untuk profesional salon</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="p-4 border bg-white sticky-top" style="top: 100px;">
                <h5 class="font-serif fw-bold text-dark mb-3 pb-2 border-bottom">Butuh Bleaching Kit?</h5>
                <p class="fs-7 text-muted mb-4">
                    Paket lengkap bleaching formula bebas debu (dust-free) diperkaya anti-breakage agent untuk melindungi kutikula rambut Anda.
                </p>
                <div class="p-3 bg-light border mb-4">
                    <h6 class="fw-bold fs-7 mb-1">LUMEN Pro-Lightening Kit</h6>
                    <div class="fs-8 text-muted mb-2">Bleaching Powder 250g + Developer 20 Vol 100ml</div>
                    <span class="fs-6 fw-bold text-dark">Rp 85.000</span>
                </div>
                <a href="{{ route('shop.category', 'bleaching-developers') }}" class="btn btn-brand-dark w-100 py-3 mb-2">
                    Beli Paket Bleaching Sekarang
                </a>
                <div class="text-center mt-3">
                    <span class="fs-8 text-muted">Dikirim langsung dengan kurir KiriminAja</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
