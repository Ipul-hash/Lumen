<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\StoreSetting;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'store_name' => 'LUMEN Hair Color Store',
            'store_email' => 'support@lumenhair.id',
            'store_phone' => '081288990011',
            'origin_province_id' => '6',
            'origin_province_name' => 'DKI Jakarta',
            'origin_city_id' => '153',
            'origin_city_name' => 'Kota Jakarta Selatan',
            'origin_district_id' => '2105',
            'origin_district_name' => 'Kebayoran Baru',
            'origin_subdistrict_id' => '210501',
            'origin_subdistrict_name' => 'Senayan',
            'origin_postal_code' => '12190',
            'origin_address' => 'Jl. Radio Dalam Raya No. 42, Kebayoran Baru, Jakarta Selatan',
            'announcement_bar' => 'Gratis Ongkir se-Indonesia untuk pesanan di atas Rp 200.000 | Produk Vegan & Bebas Amonia',
        ];

        foreach ($settings as $key => $value) {
            StoreSetting::set($key, $value);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@lumenhair.id'],
            [
                'name' => 'Admin Lumen Hair',
                'phone' => '081288990011',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'bara@ubsi.ac.id'],
            [
                'name' => 'Bara Ubsi',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        UserAddress::firstOrCreate(
            ['user_id' => $customer->id, 'label' => 'Rumah'],
            [
                'recipient_name' => 'Bara Ubsi',
                'phone' => '081234567890',
                'address_line' => 'Jl. Fatmawati Raya No. 45, RT 02 / RW 05',
                'province_id' => 6,
                'province_name' => 'DKI Jakarta',
                'city_id' => 153,
                'city_name' => 'Kota Jakarta Selatan',
                'district_id' => 2108,
                'district_name' => 'Cilandak',
                'subdistrict_id' => 210802,
                'subdistrict_name' => 'Cilandak Barat',
                'postal_code' => '12430',
                'is_default' => true,
            ]
        );

        if (Category::count() > 0) {
            return;
        }

        $catSemiPermanent = Category::create([
            'name' => 'Semi-Permanent Hair Dye',
            'slug' => 'semi-permanent-hair-dye',
            'description' => 'Pewarna rambut semi permanen formula vegan tanpa amonia dan hidrogen peroksida.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $catBleaching = Category::create([
            'name' => 'Bleaching & Developers',
            'slug' => 'bleaching-developers',
            'description' => 'Paket bleaching level tinggi aman untuk rambut dan krim developer peroksida.',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $catCare = Category::create([
            'name' => 'Color Care & Treatment',
            'slug' => 'color-care-treatment',
            'description' => 'Shampoo ungu anti-kuning, masker rambut keratin, dan kondisioner pengunci warna.',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $catTools = Category::create([
            'name' => 'Tools & Accessories',
            'slug' => 'tools-accessories',
            'description' => 'Mangkuk cat rambut, kuas presisi, sarung tangan nitril, dan jubah pewarnaan.',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $prod1 = Product::create([
            'category_id' => $catSemiPermanent->id,
            'name' => 'LUMEN Vivid Color Cream 120ml',
            'slug' => 'lumen-vivid-color-cream-120ml',
            'summary' => 'Pewarna rambut semi-permanen dengan pigmen intens ekstra berkilau, bebas amonia, dan wangi buah tropis.',
            'description' => '<p>LUMEN Vivid Color Cream hadir dengan formulasi vegan conditioning base yang menutrisi rambut saat diwarnai. Menghasilkan warna berkilau, cerah, dan tahan hingga 6-8 minggu tanpa merusak kutikula rambut.</p>',
            'specifications' => [
                'Volume' => '120 ml',
                'Ketahanan' => '30 - 45 kali keramas',
                'Bahan Utama' => 'Vegan Keratin, Argan Oil, Chamomile Extract',
                'Aroma' => 'Berry Breeze',
                'Sertifikasi' => 'BPOM RI & Cruelty-Free',
            ],
            'care_instructions' => 'Gunakan pada rambut kering yang sudah dibleaching minimal level 8-9. Diamkan selama 35-45 menit lalu bilas dengan air dingin tanpa shampoo.',
            'base_price' => 125000,
            'weight' => 200,
            'length' => 6,
            'width' => 6,
            'height' => 18,
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'LMN-ASH-120',
            'name' => 'Ash Grey Titanium',
            'color_name' => 'Ash Grey Titanium',
            'color_code' => '#8D99AE',
            'size' => '120ml',
            'price' => 125000,
            'stock' => 35,
            'weight' => 200,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'LMN-ROSE-120',
            'name' => 'Rose Gold Pastel',
            'color_name' => 'Rose Gold Pastel',
            'color_code' => '#DDA7A5',
            'size' => '120ml',
            'price' => 125000,
            'stock' => 24,
            'weight' => 200,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'LMN-BLUE-120',
            'name' => 'Midnight Sapphire',
            'color_name' => 'Midnight Sapphire',
            'color_code' => '#1B263B',
            'size' => '120ml',
            'price' => 125000,
            'discount_price' => 110000,
            'stock' => 18,
            'weight' => 200,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'LMN-BURG-120',
            'name' => 'Velvet Burgundy',
            'color_name' => 'Velvet Burgundy',
            'color_code' => '#581845',
            'size' => '120ml',
            'price' => 125000,
            'stock' => 8,
            'weight' => 200,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod1->id,
            'sku' => 'LMN-EMR-120',
            'name' => 'Emerald Green',
            'color_name' => 'Emerald Green',
            'color_code' => '#0F4C3A',
            'size' => '120ml',
            'price' => 125000,
            'stock' => 4,
            'weight' => 200,
            'is_active' => true,
        ]);

        $prod2 = Product::create([
            'category_id' => $catBleaching->id,
            'name' => 'LUMEN Ultra Lift Bleaching Powder 250g',
            'slug' => 'lumen-ultra-lift-bleaching-powder-250g',
            'summary' => 'Bubuk bleaching anti-kuning dengan formula dust-free yang mampu menaikkan hingga 9 tingkat level warna rambut dalam sekali proses.',
            'description' => '<p>Formulasi debu minimal dengan pigmen biru penangkal warna kuningan (anti-brass). Menjaga serat rambut tetap kuat berkat kandungan plex technology.</p>',
            'specifications' => [
                'Berat Bersih' => '250 gram',
                'Kekuatan Lift' => 'Mencapai Level 9+',
                'Teknologi' => 'Anti-Brass Blue Powder & Plex Bond Protector',
            ],
            'care_instructions' => 'Campurkan dengan Developer Cream perbandingan 1:2. Hindari kontak langsung dengan kulit kepala sensitif.',
            'base_price' => 165000,
            'weight' => 350,
            'length' => 10,
            'width' => 10,
            'height' => 12,
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod2->id,
            'sku' => 'BLC-PLX-250',
            'name' => 'Bleaching Powder 250g',
            'color_name' => 'Cool Blue Powder',
            'color_code' => '#4A90E2',
            'size' => '250g',
            'price' => 165000,
            'stock' => 40,
            'weight' => 350,
            'is_active' => true,
        ]);

        $prod3 = Product::create([
            'category_id' => $catCare->id,
            'name' => 'LUMEN Silver Toning Purple Shampoo 300ml',
            'slug' => 'lumen-silver-toning-purple-shampoo-300ml',
            'summary' => 'Shampoo khusus rambut bleached dan berwarna ash/abu-abu untuk menetralkan warna kuning kusam.',
            'description' => '<p>Mengandung pigmen ungu pekat yang membersihkan sekaligus menghilangkan tone kuning/oranye pada rambut pirang, abu-abu, dan pastel.</p>',
            'specifications' => [
                'Volume' => '300 ml',
                'pH Level' => '5.5 Seimbang',
                'Kandungan' => 'Deep Violet Extract, Biotin, Vitamin E',
            ],
            'care_instructions' => 'Gunakan 2-3 kali seminggu. Diamkan 3-5 menit pada busa sebelum dibilas hingga bersih.',
            'base_price' => 140000,
            'weight' => 400,
            'length' => 7,
            'width' => 7,
            'height' => 20,
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod3->id,
            'sku' => 'SHP-PUR-300',
            'name' => 'Purple Shampoo 300ml',
            'color_name' => 'Deep Violet',
            'color_code' => '#7209B7',
            'size' => '300ml',
            'price' => 140000,
            'stock' => 28,
            'weight' => 400,
            'is_active' => true,
        ]);

        $prod4 = Product::create([
            'category_id' => $catTools->id,
            'name' => 'LUMEN Professional Hair Color Tool Kit',
            'slug' => 'lumen-professional-hair-color-tool-kit',
            'summary' => 'Satu set lengkap peralatan cat rambut salon berkualitas tinggi untuk pemakaian mandiri di rumah.',
            'description' => '<p>Termasuk mangkuk ukur takar, 2 jenis kuas aplikator lembut, cape pelindung anti-noda, penjepit rambut buaya, dan sarung tangan tahan kimia.</p>',
            'specifications' => [
                'Isi Paket' => 'Mangkuk, 2 Kuas, Cape, 2 Klip, Sarung Tangan',
                'Material' => 'BPA-Free Recycled Plastic & Premium Silicone',
            ],
            'care_instructions' => 'Cuci bersih dengan sabun hangat setelah selesai digunakan.',
            'base_price' => 65000,
            'weight' => 250,
            'length' => 20,
            'width' => 15,
            'height' => 8,
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $prod4->id,
            'sku' => 'TLS-KIT-01',
            'name' => 'Standard Kit Hitam Matte',
            'color_name' => 'Matte Black',
            'color_code' => '#212529',
            'size' => 'All-in-One Set',
            'price' => 65000,
            'stock' => 50,
            'weight' => 250,
            'is_active' => true,
        ]);

        $vAsh = ProductVariant::where('sku', 'LMN-ASH-120')->first();
        $vBleach = ProductVariant::where('sku', 'BLC-PLX-250')->first();
        $vShampoo = ProductVariant::where('sku', 'SHP-PUR-300')->first();

        $order1 = Order::create([
            'order_number' => 'INV-20260924-LMN01',
            'user_id' => $customer->id,
            'customer_name' => 'Bara Ubsi',
            'customer_email' => 'bara@ubsi.ac.id',
            'customer_phone' => '081234567890',
            'shipping_address' => [
                'recipient_name' => 'Bara Ubsi',
                'phone' => '081234567890',
                'address_line' => 'Jl. Fatmawati Raya No. 45',
                'city_name' => 'Jakarta Selatan',
                'district_name' => 'Cilandak',
                'province_name' => 'DKI Jakarta',
                'postal_code' => '12430',
            ],
            'subtotal' => 290000,
            'shipping_cost' => 19000,
            'discount_amount' => 0,
            'total_amount' => 309000,
            'status' => 'shipped',
            'paid_at' => now()->subHours(6),
            'shipped_at' => now()->subHours(2),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $vAsh->id,
            'product_name' => 'LUMEN Vivid Color Cream 120ml',
            'variant_name' => 'Ash Grey Titanium',
            'sku' => 'LMN-ASH-120',
            'price' => 125000,
            'quantity' => 1,
            'weight' => 200,
            'total_price' => 125000,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $vBleach->id,
            'product_name' => 'LUMEN Ultra Lift Bleaching Powder 250g',
            'variant_name' => 'Bleaching Powder 250g',
            'sku' => 'BLC-PLX-250',
            'price' => 165000,
            'quantity' => 1,
            'weight' => 350,
            'total_price' => 165000,
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'payment_gateway' => 'midtrans',
            'transaction_id' => 'TRX-QRIS-9918231',
            'payment_type' => 'qris',
            'qr_string' => '00020101021226670014ID.LINKAJA.WWW011893600911002234000102150000000000000150303UMI51440014ID.GO.BI.QRIS0102195204481453033605802ID5914LUMEN COLOR STORE6015JAKARTA SELATAN61051219062070703A0163045E1B',
            'amount' => 309000,
            'fee' => 2000,
            'status' => 'settlement',
            'paid_at' => now()->subHours(6),
        ]);

        Shipment::create([
            'order_id' => $order1->id,
            'courier_name' => 'J&T Express',
            'courier_code' => 'jnt',
            'service_type' => 'EZ',
            'booking_id' => 'KA-BKG-992182',
            'waybill_number' => 'JT882910291ID',
            'shipping_cost' => 19000,
            'total_weight' => 550,
            'origin_district_id' => 2105,
            'destination_district_id' => 2108,
            'status' => 'picked_up',
            'pickup_scheduled_at' => now()->subHours(4),
            'shipped_at' => now()->subHours(2),
        ]);

        $order2 = Order::create([
            'order_number' => 'INV-20260924-LMN02',
            'customer_name' => 'Clarissa Putri',
            'customer_email' => 'clarissa.p@gmail.com',
            'customer_phone' => '081399882211',
            'shipping_address' => [
                'recipient_name' => 'Clarissa Putri',
                'phone' => '081399882211',
                'address_line' => 'Apartemen Sudirman Park Tower B Lt 12',
                'city_name' => 'Jakarta Pusat',
                'district_name' => 'Tanah Abang',
                'province_name' => 'DKI Jakarta',
                'postal_code' => '10220',
            ],
            'subtotal' => 265000,
            'shipping_cost' => 15000,
            'discount_amount' => 0,
            'total_amount' => 280000,
            'status' => 'processing',
            'paid_at' => now()->subHour(),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $vAsh->id,
            'product_name' => 'LUMEN Vivid Color Cream 120ml',
            'variant_name' => 'Ash Grey Titanium',
            'sku' => 'LMN-ASH-120',
            'price' => 125000,
            'quantity' => 1,
            'weight' => 200,
            'total_price' => 125000,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $vShampoo->id,
            'product_name' => 'LUMEN Silver Toning Purple Shampoo 300ml',
            'variant_name' => 'Purple Shampoo 300ml',
            'sku' => 'SHP-PUR-300',
            'price' => 140000,
            'quantity' => 1,
            'weight' => 400,
            'total_price' => 140000,
        ]);

        Payment::create([
            'order_id' => $order2->id,
            'payment_gateway' => 'midtrans',
            'transaction_id' => 'TRX-VABCA-771822',
            'payment_type' => 'virtual_account',
            'bank' => 'bca',
            'va_number' => '8271081399882211',
            'amount' => 280000,
            'fee' => 4000,
            'status' => 'settlement',
            'paid_at' => now()->subHour(),
        ]);

        Shipment::create([
            'order_id' => $order2->id,
            'courier_name' => 'SiCepat',
            'courier_code' => 'sicepat',
            'service_type' => 'REG',
            'shipping_cost' => 15000,
            'total_weight' => 600,
            'origin_district_id' => 2105,
            'destination_district_id' => 2102,
            'status' => 'pending_pickup',
        ]);
    }
}
