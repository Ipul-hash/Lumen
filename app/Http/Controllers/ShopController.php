<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Services\KiriminAjaService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('sort_order')->get();

        $featuredProducts = Product::with(['primaryImage', 'activeVariants', 'category'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->get();

        return view('shop.index', compact('categories', 'featuredProducts', 'allProducts'));
    }

    public function category(Category $category)
    {
        $products = Product::with(['primaryImage', 'activeVariants'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);

        $categories = Category::orderBy('sort_order')->get();

        return view('shop.category', compact('category', 'products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'images', 'activeVariants']);

        $relatedProducts = Product::with(['primaryImage', 'activeVariants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function shadeGuide()
    {
        $products = Product::with(['activeVariants'])
            ->where('is_active', true)
            ->get();

        return view('shop.shade-guide', compact('products'));
    }

    public function trackOrder(Request $request, KiriminAjaService $kiriminAja)
    {
        $order = null;
        $shipment = null;
        $trackingCheckpoints = [];

        if ($request->filled('q')) {
            $search = trim($request->q);

            $order = Order::with(['items', 'shipment', 'latestPayment'])
                ->where('order_number', $search)
                ->orWhereHas('shipment', function ($sq) use ($search) {
                    $sq->where('waybill_number', $search)
                       ->orWhere('booking_id', $search);
                })
                ->first();

            if ($order && $order->shipment) {
                $shipment = $order->shipment;
                if ($shipment->hasWaybill()) {
                    $trackingCheckpoints = $kiriminAja->getTracking(
                        $shipment->waybill_number,
                        $shipment->courier_code
                    );
                }
            }
        }

        return view('shop.track', compact('order', 'shipment', 'trackingCheckpoints'));
    }
}
