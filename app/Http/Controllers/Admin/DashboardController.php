<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $totalEarnings = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total_amount');

        $thisMonthEarnings = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalOrdersCount = Order::count();
        $pendingPickupCount = Shipment::where('status', 'pending_pickup')->count();
        $inTransitCount = Shipment::whereIn('status', ['picked_up', 'in_transit'])->count();
        $deliveredCount = Shipment::where('status', 'delivered')->count();

        $qrisSettledAmount = Payment::where('payment_type', 'qris')
            ->where('status', 'settlement')
            ->sum('amount');

        $vaSettledAmount = Payment::where('payment_type', 'virtual_account')
            ->where('status', 'settlement')
            ->sum('amount');

        $pendingPaymentsCount = Payment::where('status', 'pending')->count();

        $totalProducts = Product::count();
        $totalVariants = ProductVariant::count();
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $recentOrders = Order::with(['items', 'latestPayment', 'shipment'])
            ->latest()
            ->take(8)
            ->get();

        $recentShipments = Shipment::with('order')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEarnings',
            'thisMonthEarnings',
            'ordersThisMonth',
            'totalOrdersCount',
            'pendingPickupCount',
            'inTransitCount',
            'deliveredCount',
            'qrisSettledAmount',
            'vaSettledAmount',
            'pendingPaymentsCount',
            'totalProducts',
            'totalVariants',
            'lowStockVariants',
            'recentOrders',
            'recentShipments'
        ));
    }
}
