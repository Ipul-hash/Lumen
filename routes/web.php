<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FormulaCalculatorController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shade-guide', [ShopController::class, 'shadeGuide'])->name('shop.shadeGuide');
Route::get('/formula-calculator', [FormulaCalculatorController::class, 'index'])->name('shop.formulaCalculator');
Route::post('/formula-calculator/calculate', [FormulaCalculatorController::class, 'calculate'])->name('shop.formulaCalculator.calculate');
Route::get('/track', [ShopController::class, 'trackOrder'])->name('shop.track');

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

Route::prefix('admin')->name('admin.')->group(function () {
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
});
