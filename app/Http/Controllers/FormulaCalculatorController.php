<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductVariant;
use App\Services\ColorFormulaEngine;
use Illuminate\Http\Request;

class FormulaCalculatorController extends Controller
{
    public function index(ColorFormulaEngine $engine)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $colorCategory = Category::where('slug', 'semi-permanent-hair-dye')->first();
        $targetVariants = ProductVariant::with('product')
            ->whereHas('product', function ($q) use ($colorCategory) {
                $q->where('is_active', true);
                if ($colorCategory) {
                    $q->where('category_id', $colorCategory->id);
                }
            })
            ->where('is_active', true)
            ->whereNotNull('color_code')
            ->get();

        if ($targetVariants->isEmpty()) {
            $targetVariants = ProductVariant::with('product')
                ->where('is_active', true)
                ->whereNotNull('color_code')
                ->take(8)
                ->get();
        }

        $initialFormula = null;
        if ($targetVariants->isNotEmpty()) {
            $firstVariant = $targetVariants->first();
            $initialFormula = $engine->calculate(2, 'medium', $firstVariant->id);
        }

        return view('shop.formula-calculator', compact('categories', 'targetVariants', 'initialFormula'));
    }

    public function calculate(Request $request, ColorFormulaEngine $engine)
    {
        $validated = $request->validate([
            'current_level' => 'required|integer|min:1|max:10',
            'hair_length' => 'required|string|in:short,medium,long',
            'target_variant_id' => 'required|exists:product_variants,id',
        ]);

        $result = $engine->calculate(
            (int)$validated['current_level'],
            $validated['hair_length'],
            (int)$validated['target_variant_id']
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
