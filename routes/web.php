<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CourierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKitchen()) {
            return redirect()->route('kitchen.dashboard');
        } elseif ($user->isCourier()) {
            return redirect()->route('courier.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
    
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('password.verify_otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify_otp.post');
    
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    
    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    
    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show')->withTrashed();
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('/orders/{order}/confirm-cash', [\App\Http\Controllers\Admin\OrderController::class, 'confirmCashPayment'])->name('admin.orders.confirmCash');
    Route::post('/orders/{order}/assign-courier', [\App\Http\Controllers\Admin\OrderController::class, 'assignCourier'])->name('admin.orders.assignCourier');
    
    // Financial Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
    
    // Staff Management
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class)->names([
        'index' => 'admin.staff.index',
        'create' => 'admin.staff.create',
        'store' => 'admin.staff.store',
        'edit' => 'admin.staff.edit',
        'update' => 'admin.staff.update',
        'destroy' => 'admin.staff.destroy',
    ]);
    
    // Reset Data
    Route::post('/reset-transactions', [AdminController::class, 'resetTransactions'])->name('admin.reset_transactions');
});

// Kitchen Routes
Route::middleware(['auth', 'role:kitchen'])->prefix('kitchen')->group(function () {
    Route::get('/dashboard', [KitchenController::class, 'dashboard'])->name('kitchen.dashboard');
    Route::get('/orders/check-new', [KitchenController::class, 'checkNewOrders'])->name('kitchen.orders.checkNew');
    Route::post('/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.orders.updateStatus');
    Route::get('/orders/{order}/print', [KitchenController::class, 'print'])->name('kitchen.orders.print');
});

// Courier Routes
Route::middleware(['auth', 'role:courier'])->prefix('courier')->group(function () {
    Route::get('/dashboard', [CourierController::class, 'dashboard'])->name('courier.dashboard');
    Route::post('/orders/{order}/complete', [CourierController::class, 'completeDelivery'])->name('courier.orders.complete');
});

// Customer Routes
Route::middleware(['auth'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('customer.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('customer.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('customer.password.update');
    
    // Order Routes
    Route::get('/orders', [\App\Http\Controllers\CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\CustomerOrderController::class, 'show'])->name('customer.orders.show')->withTrashed();
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\CustomerOrderController::class, 'invoice'])->name('customer.orders.invoice')->withTrashed();
    
    // Product Catalog Routes
    Route::get('/products', [\App\Http\Controllers\CustomerProductController::class, 'index'])->name('customer.products.index');
    Route::get('/products/{product:slug}', [\App\Http\Controllers\CustomerProductController::class, 'show'])->name('customer.products.show');
    
    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\CustomerCartController::class, 'index'])->name('customer.cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CustomerCartController::class, 'add'])->name('customer.cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\CustomerCartController::class, 'update'])->name('customer.cart.update');
    Route::post('/cart/remove', [\App\Http\Controllers\CustomerCartController::class, 'remove'])->name('customer.cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CustomerCartController::class, 'clear'])->name('customer.cart.clear');
    
    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\CustomerCheckoutController::class, 'index'])->name('customer.checkout.index');
    Route::post('/checkout/store', [\App\Http\Controllers\CustomerCheckoutController::class, 'store'])->name('customer.checkout.store');
    
    // Notification Routes
    Route::put('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('customer.notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('customer.notifications.readAll');
});

// Midtrans Webhook Notification Route
Route::post('/payment/notification', [\App\Http\Controllers\PaymentNotificationController::class, 'handle'])->name('payment.notification');

