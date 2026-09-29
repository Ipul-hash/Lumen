<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class PerfumeSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'parfum-fragrance'],
            [
                'name' => 'Parfum & Haute Fragrance',
                'description' => 'Koleksi parfum mewah Extrait de Parfum dan Hair & Body Mist dengan konsentrasi wewangian tinggi, formula tahan 12+ jam.',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        // Product 1: Santal Blanc & Amber
        $p1 = Product::updateOrCreate(
            ['slug' => 'lumen-extrait-de-parfum-santal-blanc-amber'],
            [
                'category_id' => $category->id,
                'name' => 'LUMEN Extrait de Parfum - Santal Blanc & Amber',
                'summary' => 'Extrait de Parfum mewah dengan aroma sandalwood creamy Mysore, kehangatan golden ambergris, dan sentuhan cardamom eksotis. Konsentrasi 35% tahan 12+ jam.',
                'description' => '<p><strong>LUMEN Santal Blanc & Amber</strong> adalah mahakarya seni wewangian woody-oriental modern yang dirancang untuk pria dan wanita yang menginginkan jejak aroma berkelas, menenangkan, dan berwibawa.</p><p>Dibuat dengan konsentrasi <em>Extrait de Parfum</em> 35% menggunakan bibit minyak atsiri murni terbaik dari Grasse, Prancis. Perpaduan aroma dimulai dari kesegaran spicy Italian Cardamom, beralih ke creamy Mysore Sandalwood dan Florentine Iris, kemudian mengendap pada basis golden ambergris dan Madagascar vanilla yang tahan seharian penuh.</p>',
                'specifications' => [
                    'Konsentrasi' => 'Extrait de Parfum (35% Fragrance Oil)',
                    'Ketahanan (Longevity)' => '12 - 14 Jam di Kulit, 24+ Jam di Pakaian',
                    'Pancaran (Sillage)' => 'Moderate to Strong, Elegant & Luxurious',
                    'Top Notes' => 'Italian Cardamom, Violet Leaf, Pink Pepper',
                    'Heart Notes' => 'Mysore Sandalwood, Florentine Iris, Papyrus Accord',
                    'Base Notes' => 'Golden Ambergris, Virginian Cedarwood, Madagascar Vanilla, Clean Musk',
                    'Kemasan' => 'Heavy Glass Flacon with Magnetic Metallic Cap',
                    'BPOM' => 'NA18240601892',
                ],
                'care_instructions' => 'Semprotkan pada titik nadi (pergelangan tangan bagian dalam, samping leher, dan belakang telinga) dari jarak 15 cm. Hindari menggosok cairan parfum di kulit agar molekul aroma tidak rusak. Simpan di tempat sejuk terlindung dari sinar matahari langsung.',
                'base_price' => 195000,
                'weight' => 250,
                'length' => 8,
                'width' => 8,
                'height' => 16,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $v1Data = [
            [
                'sku' => 'LMN-PRF-SNT-30',
                'name' => 'Travel Spray 30ml',
                'color_name' => 'Warm Sandalwood',
                'color_code' => '#C49A6C',
                'size' => '30ml',
                'price' => 195000,
                'discount_price' => null,
                'stock' => 45,
                'weight' => 160,
                'image' => 'variants/santal_blanc_30.jpg',
            ],
            [
                'sku' => 'LMN-PRF-SNT-50',
                'name' => 'Signature Flacon 50ml',
                'color_name' => 'Golden Amber',
                'color_code' => '#D4AF37',
                'size' => '50ml',
                'price' => 295000,
                'discount_price' => 275000,
                'stock' => 35,
                'weight' => 260,
                'image' => 'variants/santal_blanc_50.jpg',
            ],
            [
                'sku' => 'LMN-PRF-SNT-100',
                'name' => 'Grand Flacon 100ml',
                'color_name' => 'Royal Ambergris',
                'color_code' => '#8B5A2B',
                'size' => '100ml',
                'price' => 475000,
                'discount_price' => null,
                'stock' => 20,
                'weight' => 420,
                'image' => 'variants/santal_blanc_100.jpg',
            ],
        ];

        foreach ($v1Data as $vd) {
            ProductVariant::updateOrCreate(
                ['sku' => $vd['sku']],
                array_merge($vd, ['product_id' => $p1->id, 'is_active' => true])
            );
        }

        ProductImage::updateOrCreate(
            ['product_id' => $p1->id, 'is_primary' => true],
            ['image_path' => 'variants/santal_blanc_50.jpg', 'sort_order' => 1]
        );

        // Product 2: Velvet Rose & Smoked Oud
        $p2 = Product::updateOrCreate(
            ['slug' => 'lumen-extrait-de-parfum-velvet-rose-smoked-oud'],
            [
                'category_id' => $category->id,
                'name' => 'LUMEN Extrait de Parfum - Velvet Rose & Smoked Oud',
                'summary' => 'Aroma sensual Damask Rose yang manis gelap dengan kehangatan asap kayu gaharu oud Timur Tengah, praline manis, dan amber. Misterius, kaya, dan tak terlupakan.',
                'description' => '<p><strong>Velvet Rose & Smoked Oud</strong> adalah karya wewangian mewah berjiwa misterius dan magnetis. Menggabungkan pesona kelopak mawar Damaskus mekar dengan aroma asap kayu gaharu (oud) berharga dan sentuhan praline gourmand yang adiktif.</p><p>Sangat ideal untuk pesta malam, acara istimewa, atau saat Anda ingin tampil menawan dengan wangi tahan lama yang meninggalkan jejak aroma tak terlupakan.</p>',
                'specifications' => [
                    'Konsentrasi' => 'Extrait de Parfum (32% Fragrance Oil)',
                    'Ketahanan (Longevity)' => '14 - 16 Jam',
                    'Pancaran (Sillage)' => 'Strong & Mesmerizing Trail',
                    'Top Notes' => 'Damask Rose Petals, Moroccan Clove, Sparkling Bergamot',
                    'Heart Notes' => 'Smoked Agarwood (Oud), Sweet Praline, French Geranium',
                    'Base Notes' => 'Deep Amber, Smoky Leather, Benzoin Resin, Indonesian Patchouli',
                    'Karakter' => 'Sensual, Dark Floral, Smoky & Gourmand',
                    'BPOM' => 'NA18240602014',
                ],
                'care_instructions' => 'Cukup semprotkan 2-3 kali pada pakaian dan titik nadi leher untuk keharuman maksimal sepanjang hari.',
                'base_price' => 215000,
                'weight' => 250,
                'length' => 8,
                'width' => 8,
                'height' => 16,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $v2Data = [
            [
                'sku' => 'LMN-PRF-ROSE-30',
                'name' => 'Travel Spray 30ml',
                'color_name' => 'Crimson Rose',
                'color_code' => '#7B1113',
                'size' => '30ml',
                'price' => 215000,
                'discount_price' => null,
                'stock' => 40,
                'weight' => 160,
                'image' => 'variants/velvet_rose_30.jpg',
            ],
            [
                'sku' => 'LMN-PRF-ROSE-50',
                'name' => 'Signature Flacon 50ml',
                'color_name' => 'Deep Smoked Oud',
                'color_code' => '#3E1F2F',
                'size' => '50ml',
                'price' => 320000,
                'discount_price' => 295000,
                'stock' => 30,
                'weight' => 260,
                'image' => 'variants/velvet_rose_50.jpg',
            ],
            [
                'sku' => 'LMN-PRF-ROSE-100',
                'name' => 'Collector Flacon 100ml',
                'color_name' => 'Midnight Obsidian',
                'color_code' => '#1F1418',
                'size' => '100ml',
                'price' => 510000,
                'discount_price' => null,
                'stock' => 15,
                'weight' => 420,
                'image' => 'variants/velvet_rose_100.jpg',
            ],
        ];

        foreach ($v2Data as $vd) {
            ProductVariant::updateOrCreate(
                ['sku' => $vd['sku']],
                array_merge($vd, ['product_id' => $p2->id, 'is_active' => true])
            );
        }

        ProductImage::updateOrCreate(
            ['product_id' => $p2->id, 'is_primary' => true],
            ['image_path' => 'variants/velvet_rose_50.jpg', 'sort_order' => 1]
        );

        // Product 3: Hair & Body Fragrance Mist
        $p3 = Product::updateOrCreate(
            ['slug' => 'lumen-hair-body-fragrance-mist-100ml'],
            [
                'category_id' => $category->id,
                'name' => 'LUMEN Hair & Body Fragrance Mist 100ml',
                'summary' => 'Mist aromaterapi segar untuk rambut dan tubuh dengan keharuman mewah, diperkaya Pro-Vitamin B5 & Aloe Vera. Bebas alkohol kering, aman untuk rambut berwarna.',
                'description' => '<p><strong>LUMEN Hair & Body Fragrance Mist</strong> memberikan kesegaran instan sepanjang hari tanpa membuat batang rambut menjadi kering atau kusam. Formula water-based lembut ini diperkaya Pro-Vitamin B5 dan UV filter untuk melindungi kilau warna rambut.</p><p>Sangat ideal digunakan setelah beraktivitas, sehabis makan, atau kapan saja Anda membutuhkan keharuman segar yang membangkitkan suasana hati.</p>',
                'specifications' => [
                    'Volume' => '100 ml',
                    'Tipe' => 'Hair & Body Fragrance Mist',
                    'Ketahanan' => '6 - 8 Jam',
                    'Nutrisi Tambahan' => 'Pro-Vitamin B5, Aloe Vera Extract, Color UV Shield',
                    'Formula' => 'Non-drying, Aman untuk rambut diwarnai dan kulit sensitif',
                    'Aplikator' => 'Ultra-fine Micro Mist Spray',
                    'BPOM' => 'NA18240602355',
                ],
                'care_instructions' => 'Semprotkan merata ke rambut dan tubuh dari jarak 20 cm. Dapat diaplikasikan kembali kapan pun Anda memerlukan kesegaran aromatik.',
                'base_price' => 135000,
                'weight' => 180,
                'length' => 5,
                'width' => 5,
                'height' => 18,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $v3Data = [
            [
                'sku' => 'LMN-MST-SOL-100',
                'name' => 'Solar Neroli & Jasmine 100ml',
                'color_name' => 'Solar Citrus Gold',
                'color_code' => '#FDB813',
                'size' => '100ml',
                'price' => 135000,
                'discount_price' => null,
                'stock' => 55,
                'weight' => 180,
                'image' => 'variants/mist_solar_100.jpg',
            ],
            [
                'sku' => 'LMN-MST-MTC-100',
                'name' => 'Matcha & White Tea 100ml',
                'color_name' => 'Zen Matcha Green',
                'color_code' => '#5B8C5A',
                'size' => '100ml',
                'price' => 135000,
                'discount_price' => 120000,
                'stock' => 45,
                'weight' => 180,
                'image' => 'variants/mist_matcha_100.jpg',
            ],
        ];

        foreach ($v3Data as $vd) {
            ProductVariant::updateOrCreate(
                ['sku' => $vd['sku']],
                array_merge($vd, ['product_id' => $p3->id, 'is_active' => true])
            );
        }

        ProductImage::updateOrCreate(
            ['product_id' => $p3->id, 'is_primary' => true],
            ['image_path' => 'variants/mist_solar_100.jpg', 'sort_order' => 1]
        );
    }
}
