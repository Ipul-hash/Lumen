<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FormulaCalculatorController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shade-guide', [ShopController::class, 'shadeGuide'])->name('shop.shadeGuide');
Route::get('/formula-calculator', [FormulaCalculatorController::class, 'index'])->name('shop.formulaCalculator');
Route::post('/formula-calculator/calculate', [FormulaCalculatorController::class, 'calculate'])->name('shop.formulaCalculator.calculate');
Route::get('/track', [ShopController::class, 'trackOrder'])->name('shop.track');
Route::get('/refund/request', [RefundController::class, 'create'])->name('refund.create');
Route::get('/refund/lookup-order', [RefundController::class, 'lookupOrder'])->name('refund.lookupOrder');
Route::post('/refund/request', [RefundController::class, 'store'])->name('refund.store');
Route::get('/refund/track', [RefundController::class, 'track'])->name('refund.track');
Route::get('/refund/{refundNumber}/success', [RefundController::class, 'success'])->name('refund.success');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/drawer', [CartController::class, 'drawerData'])->name('cart.drawer');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add-bundle', [CartController::class, 'addBundle'])->name('cart.addBundle');
Route::put('/cart/items/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/shipping-rates', [CheckoutController::class, 'getShippingRates'])->name('checkout.shippingRates');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/{orderNumber}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::post('/payments/{payment}/simulate-success', [CheckoutController::class, 'simulateSuccess'])->name('payments.simulateSuccess');
Route::post('/midtrans/webhook', [CheckoutController::class, 'midtransWebhook'])->name('midtrans.webhook');
Route::get('/checkout/{orderNumber}/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/check-ip', function (\Illuminate\Http\Request $request, \App\Services\KiriminAjaService $service) {
    $proxy = env('FIXIE_URL') ?: (env('HTTP_PROXY') ?: \App\Models\StoreSetting::get('kiriminaja_proxy'));
    $client = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(6);
    if (!empty($proxy)) {
        $client = $client->withOptions(['proxy' => $proxy]);
    }
    $outboundIp = null;
    try {
        $ipRes = $client->get('https://api.ipify.org?format=json');
        if ($ipRes->successful()) {
            $outboundIp = $ipRes->json('ip');
        }
    } catch (\Throwable $e) {
        $outboundIp = 'Error: ' . $e->getMessage();
    }
    $data = [
        'status' => 'success',
        'server_outbound_ip' => $outboundIp,
        'proxy_configured' => !empty($proxy),
        'environment' => \App\Models\StoreSetting::get('kiriminaja_mode', 'sandbox'),
        'timestamp' => now()->timezone('Asia/Jakarta')->toDateTimeString() . ' WIB',
        'debug_endpoint' => url('/debug-kiriminaja'),
    ];
    if ($request->has('test')) {
        $data['kiriminaja_test'] = $service->testConnection();
    }
    return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->name('check-ip');

Route::get('/debug-kiriminaja', function () {
    $apiKey = (string) \App\Models\StoreSetting::get('kiriminaja_api_key', env('KIRIMINAJA_API_KEY', ''));
    $proxy = env('FIXIE_URL') ?: (env('HTTP_PROXY') ?: \App\Models\StoreSetting::get('kiriminaja_proxy'));
    $client = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10);
    if (!empty($proxy)) {
        $client = $client->withOptions(['proxy' => $proxy]);
    }
    $ipifyOutbound = null;
    try {
        $ipRes = $client->get('https://api.ipify.org?format=json');
        if ($ipRes->successful()) {
            $ipifyOutbound = $ipRes->json('ip');
        }
    } catch (\Throwable $e) {
        $ipifyOutbound = 'Error: ' . $e->getMessage();
    }
    $kaUrl = 'https://tdev.kiriminaja.com/api/mitra/v2/shipping_price';
    $kaStatus = null;
    $kaBody = null;
    $kaJson = null;
    $kaDetectedIp = null;
    $errorException = null;
    try {
        $res = $client->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($kaUrl, [
            'origin' => 2105,
            'destination' => 2108,
            'weight' => 1000,
            'courier' => ['jne'],
        ]);
        $kaStatus = $res->status();
        $kaBody = $res->body();
        $kaJson = $res->json();
        $kaDetectedIp = $kaJson['your_ip'] ?? null;
    } catch (\Throwable $e) {
        $errorException = $e->getMessage();
    }
    $dashboardWhitelistedIp = '74.220.52.132';
    $isMatching = ($kaDetectedIp === $dashboardWhitelistedIp);
    if ($kaDetectedIp) {
        if ($isMatching) {
            $conclusion = "IP yang terbaca oleh KiriminAja ({$kaDetectedIp}) SAMA PERSIS dengan IP ({$dashboardWhitelistedIp}) di dashboard KiriminAja. Artinya Render konsisten menggunakan IP tersebut, dan error ini disebabkan oleh keterlambatan sinkronisasi / cache internal KiriminAja.";
        } else {
            $conclusion = "IP yang terbaca oleh KiriminAja ({$kaDetectedIp}) BERBEDA dari IP ({$dashboardWhitelistedIp}) di dashboard KiriminAja. Artinya Render memakai IP outbound lain dari pool jaringannya. Masukkan IP ({$kaDetectedIp}) ke dashboard KiriminAja.";
        }
    } else {
        $conclusion = "Tidak ada field your_ip dalam respon KiriminAja. Periksa raw_response di bawah.";
    }
    return response()->json([
        'status' => 'success',
        'timestamp' => now()->timezone('Asia/Jakarta')->toDateTimeString() . ' WIB',
        'server_outbound_ip_via_ipify' => $ipifyOutbound,
        'kiriminaja_detected_ip' => $kaDetectedIp,
        'dashboard_whitelisted_ip' => $dashboardWhitelistedIp,
        'is_matching' => $isMatching,
        'conclusion' => $conclusion,
        'proxy_used' => !empty($proxy) ? $proxy : null,
        'kiriminaja_target_url' => $kaUrl,
        'kiriminaja_http_status' => $kaStatus,
        'kiriminaja_raw_json' => $kaJson,
        'kiriminaja_raw_body' => $kaBody,
        'exception_if_any' => $errorException,
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->name('debug-kiriminaja');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{order}/pickup', [OrderController::class, 'requestPickup'])->name('orders.requestPickup');
    Route::post('orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('orders/{order}/shipping-label', [OrderController::class, 'printShippingLabel'])->name('orders.shippingLabel');
    Route::get('orders/{order}/invoice', [OrderController::class, 'printInvoice'])->name('orders.invoice');

    Route::get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('shipments/calculator', [ShipmentController::class, 'calculator'])->name('shipments.calculator');
    Route::post('shipments/calculator/rates', [ShipmentController::class, 'calculateRatesAjax'])->name('shipments.calculateRatesAjax');
    Route::get('shipments/settings', [ShipmentController::class, 'settings'])->name('shipments.settings');
    Route::put('shipments/settings', [ShipmentController::class, 'updateSettings'])->name('shipments.updateSettings');
    Route::post('shipments/test-connection', [ShipmentController::class, 'testConnection'])->name('shipments.testConnection');
    Route::post('shipments/batch-pickup', [ShipmentController::class, 'batchPickup'])->name('shipments.batchPickup');
    Route::get('shipments/{shipment}/track', [ShipmentController::class, 'track'])->name('shipments.track');

    Route::get('refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
    Route::get('refunds/{refund}', [AdminRefundController::class, 'show'])->name('refunds.show');
    Route::post('refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
    Route::post('refunds/{refund}/reject', [AdminRefundController::class, 'reject'])->name('refunds.reject');
    Route::post('refunds/{refund}/disburse', [AdminRefundController::class, 'disburse'])->name('refunds.disburse');

    Route::middleware(['superadmin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});
