<?php

namespace App\Services;

use App\Models\ProductVariant;

class ColorFormulaEngine
{
    protected array $levelNames = [
        1 => 'Level 1: Jet Black (Hitam Pekat)',
        2 => 'Level 2: Darkest Brown / Natural Black (Rambut Asia Alami)',
        3 => 'Level 3: Dark Brown (Cokelat Tua)',
        4 => 'Level 4: Medium Brown (Cokelat Sedang)',
        5 => 'Level 5: Light Brown (Cokelat Terang)',
        6 => 'Level 6: Dark Blonde (Pirang Gelap)',
        7 => 'Level 7: Medium Blonde (Kuning Oranye)',
        8 => 'Level 8: Light Blonde (Kuning Emas)',
        9 => 'Level 9: Very Light Blonde (Kuning Pucat)',
        10 => 'Level 10: Pale Platinum Blonde (Putih Bersih)',
    ];

    protected array $undercoats = [
        1 => 'Pigmen eumelanin hitam-merah pekat',
        2 => 'Pigmen hitam alami padat',
        3 => 'Undercoat merah tua',
        4 => 'Undercoat merah kecokelatan',
        5 => 'Undercoat merah-oranye',
        6 => 'Undercoat oranye',
        7 => 'Undercoat oranye-kuning',
        8 => 'Undercoat kuning emas (brass)',
        9 => 'Undercoat kuning pucat',
        10 => 'Undercoat putih bersih bebas brass',
    ];

    public function calculate(int $currentLevel, string $hairLength, int $targetVariantId): array
    {
        $currentLevel = max(1, min(10, $currentLevel));
        $targetVariant = ProductVariant::with('product')->findOrFail($targetVariantId);

        $targetName = strtolower($targetVariant->name . ' ' . ($targetVariant->color_name ?? ''));
        $requiredLevel = $this->determineRequiredLevel($targetName);
        $deltaLift = max(0, $requiredLevel - $currentLevel);

        $colorTubes = match ($hairLength) {
            'long' => 2,
            default => 1,
        };

        $bleachSessions = 0;
        if ($deltaLift > 4) {
            $bleachSessions = 2;
        } elseif ($deltaLift > 0) {
            $bleachSessions = 1;
        }

        $bleachPotNeeded = $bleachSessions > 0 ? 1 : 0;
        $purpleShampooNeeded = ($bleachSessions > 0 || $requiredLevel >= 7) ? 1 : 0;
        $toolKitNeeded = 1;

        $bundleItems = [];

        $bundleItems[] = [
            'variant_id' => $targetVariant->id,
            'product_name' => $targetVariant->product->name,
            'variant_name' => $targetVariant->name,
            'color_code' => $targetVariant->color_code,
            'role' => 'Pewarna Utama (Pigmen Vegan)',
            'quantity' => $colorTubes,
            'unit_price' => (float)$targetVariant->price,
            'subtotal' => (float)$targetVariant->price * $colorTubes,
            'formatted_subtotal' => 'Rp ' . number_format($targetVariant->price * $colorTubes, 0, ',', '.'),
        ];

        if ($bleachPotNeeded > 0) {
            $bleachVariant = ProductVariant::with('product')->where('sku', 'BLC-PLX-250')->first();
            if ($bleachVariant) {
                $bundleItems[] = [
                    'variant_id' => $bleachVariant->id,
                    'product_name' => $bleachVariant->product->name,
                    'variant_name' => $bleachVariant->name,
                    'color_code' => $bleachVariant->color_code,
                    'role' => 'Pre-Lightening Powder (Plex Bond)',
                    'quantity' => $bleachPotNeeded,
                    'unit_price' => (float)$bleachVariant->price,
                    'subtotal' => (float)$bleachVariant->price * $bleachPotNeeded,
                    'formatted_subtotal' => 'Rp ' . number_format($bleachVariant->price * $bleachPotNeeded, 0, ',', '.'),
                ];
            }
        }

        if ($purpleShampooNeeded > 0) {
            $shampooVariant = ProductVariant::with('product')->where('sku', 'SHP-PUR-300')->first();
            if ($shampooVariant) {
                $bundleItems[] = [
                    'variant_id' => $shampooVariant->id,
                    'product_name' => $shampooVariant->product->name,
                    'variant_name' => $shampooVariant->name,
                    'color_code' => $shampooVariant->color_code,
                    'role' => 'Anti-Brass Toning Shampoo',
                    'quantity' => $purpleShampooNeeded,
                    'unit_price' => (float)$shampooVariant->price,
                    'subtotal' => (float)$shampooVariant->price * $purpleShampooNeeded,
                    'formatted_subtotal' => 'Rp ' . number_format($shampooVariant->price * $purpleShampooNeeded, 0, ',', '.'),
                ];
            }
        }

        $toolVariant = ProductVariant::with('product')->where('sku', 'TLS-KIT-01')->first();
        if ($toolVariant) {
            $bundleItems[] = [
                'variant_id' => $toolVariant->id,
                'product_name' => $toolVariant->product->name,
                'variant_name' => $toolVariant->name,
                'color_code' => $toolVariant->color_code,
                'role' => 'Peralatan Salon Mandiri (Kuas & Mangkuk)',
                'quantity' => $toolKitNeeded,
                'unit_price' => (float)$toolVariant->price,
                'subtotal' => (float)$toolVariant->price * $toolKitNeeded,
                'formatted_subtotal' => 'Rp ' . number_format($toolVariant->price * $toolKitNeeded, 0, ',', '.'),
            ];
        }

        $totalNormal = array_sum(array_column($bundleItems, 'subtotal'));
        $discountPercent = 10;
        $discountAmount = round($totalNormal * ($discountPercent / 100));
        $bundleTotal = $totalNormal - $discountAmount;

        $steps = [];

        if ($bleachSessions > 0) {
            $steps[] = [
                'step_number' => 1,
                'phase' => 'Pre-Lightening (Proses Bleaching)',
                'badge' => $bleachSessions === 1 ? '1x Proses Bleaching' : '2x Proses Bertahap',
                'instruction' => 'Campurkan LUMEN Pro-Bleach Powder dengan Developer Cream perbandingan 1:2 (1 bagian bubuk : 2 bagian krim). Aplikasikan merata mulai dari batang hingga ujung rambut, hindari pangkal kulit kepala 1 cm.',
                'timer' => '35 - 45 Menit',
                'target_canvas' => "Target kanvas tercapai: Level {$requiredLevel}",
            ];

            $steps[] = [
                'step_number' => 2,
                'phase' => 'Anti-Brass Neutralization (Toning)',
                'badge' => 'Netralisir Kuning Kusam',
                'instruction' => 'Bilas bleaching dengan air hangat suam-kuku. Cuci rambut dengan LUMEN Silver Toning Purple Shampoo dan diamkan busanya selama 3–5 menit agar pigmen kuning tereliminasi sempurna.',
                'timer' => '3 - 5 Menit',
                'target_canvas' => 'Kanvas bersih bebas pantulan kuning tembaga',
            ];
        }

        $stepColorIndex = count($steps) + 1;
        $steps[] = [
            'step_number' => $stepColorIndex,
            'phase' => 'Aplikasi Pigmen Warna Utama',
            'badge' => 'Deposit Warna Vegan',
            'instruction' => "Keringkan rambut hingga 80-90% kering. Aplikasikan LUMEN Vivid Color Cream ({$targetVariant->name}) secara merata menggunakan kuas presisi. Pijat lembut helai rambut agar pigmen mengunci ke kutikula.",
            'timer' => '40 - 45 Menit',
            'target_canvas' => "Warna final {$targetVariant->name} menyala intens",
        ];

        $stepCareIndex = count($steps) + 1;
        $steps[] = [
            'step_number' => $stepCareIndex,
            'phase' => 'Cold Rinse & Keratin Sealing',
            'badge' => 'Penguncian Warna',
            'instruction' => 'Bilas bersih dengan air dingin (tanpa shampoo) hingga air bilasan jernih. Oleskan conditioner/masker rambut keratin untuk menutup kutikula agar kilau multidimensi bertahan 6-8 minggu.',
            'timer' => 'Bilas Tuntas',
            'target_canvas' => 'Rambut berkilau, lembut, dan terlindungi',
        ];

        return [
            'hair_analysis' => [
                'current_level' => $currentLevel,
                'current_level_name' => $this->levelNames[$currentLevel] ?? "Level {$currentLevel}",
                'current_undercoat' => $this->undercoats[$currentLevel] ?? '-',
                'required_level' => $requiredLevel,
                'required_level_name' => $this->levelNames[$requiredLevel] ?? "Level {$requiredLevel}",
                'delta_lift' => $deltaLift,
                'hair_length' => $hairLength,
                'hair_length_label' => match($hairLength) {
                    'short' => 'Pendek / Pixie (1 tube)',
                    'medium' => 'Sedang / Sebahu (1 tube)',
                    'long' => 'Panjang / Di Bawah Bahu (2 tube)',
                    default => 'Standar'
                },
                'target_variant_id' => $targetVariant->id,
                'target_name' => $targetVariant->name,
                'target_color_name' => $targetVariant->color_name ?? $targetVariant->name,
                'target_color_code' => $targetVariant->color_code,
                'bleach_sessions' => $bleachSessions,
            ],
            'steps' => $steps,
            'bundle' => [
                'items' => $bundleItems,
                'total_normal' => $totalNormal,
                'formatted_total_normal' => 'Rp ' . number_format($totalNormal, 0, ',', '.'),
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'formatted_discount_amount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
                'bundle_total' => $bundleTotal,
                'formatted_bundle_total' => 'Rp ' . number_format($bundleTotal, 0, ',', '.'),
            ],
        ];
    }

    protected function determineRequiredLevel(string $targetName): int
    {
        if (str_contains($targetName, 'ash') || str_contains($targetName, 'grey') || str_contains($targetName, 'titanium') || str_contains($targetName, 'silver') || str_contains($targetName, 'platinum')) {
            return 9;
        }

        if (str_contains($targetName, 'rose') || str_contains($targetName, 'pastel') || str_contains($targetName, 'lilac') || str_contains($targetName, 'pink')) {
            return 9;
        }

        if (str_contains($targetName, 'blue') || str_contains($targetName, 'sapphire') || str_contains($targetName, 'emerald') || str_contains($targetName, 'green')) {
            return 7;
        }

        if (str_contains($targetName, 'burgundy') || str_contains($targetName, 'velvet') || str_contains($targetName, 'wine') || str_contains($targetName, 'mahogany')) {
            return 5;
        }

        return 8;
    }
}
