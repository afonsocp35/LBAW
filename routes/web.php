<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameCatalogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ViewPurchaseHistoryController;
use App\Http\Controllers\PlatformController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

// MO1: Authentication and profiles

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profiles
Route::controller(UserController::class)->group(function () {
    Route::get('/profile/{id}', 'show')->name('profile.show');
    Route::get('/profile/{id}/edit', 'edit')->name('profile.edit');
    Route::put('/profile/{id}', 'update')->name('profile.update');
    Route::delete('/profile/{id}', 'destroy')->name('user.destroy');
});


// MO2: Product Listings

// Product page
Route::controller(ProductController::class)->group(function () {
    Route::get('/product/{id}', 'show')->name('product.show');
});

// Catalog
Route::get('/catalog', [GameCatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/search', [GameCatalogController::class, 'search']);

// misc?
Route::get('/platform/{platformName}', [GameCatalogController::class, 'filterByPlatform'])->name('platform.filter');


// MO3: Purchases

// Shopping cart
Route::middleware('auth')->group(function () {
    Route::get('/shopping-cart', [ShoppingCartController::class, 'show'])->name('shopping-cart.show');
    Route::patch('/shopping-cart', [ShoppingCartController::class, 'update'])->name('shopping-cart.update');
    Route::post('/shopping-cart/remove', [ShoppingCartController::class, 'remove'])->name('shopping-cart.remove');
    Route::post('/shopping-cart/add/{product_id}', [ShoppingCartController::class, 'add'])->name('shopping-cart.add');
});

// Purchase History
Route::middleware('auth')->group(function () {
    Route::get('/purchase-history', [ViewPurchaseHistoryController::class, 'index'])->name('purchase.history');
});

// Checkout
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// MO5: Administration and static pages

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->middleware('auth')->name('admin.index');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->middleware('auth')->name('admin.destroy');

    Route::get('/purchase-history/{id}', [ViewPurchaseHistoryController::class, 'show'])->name('purchase.history.show');

    Route::get('/add-product', [ProductController::class, 'create'])->name('product.create');
    Route::post('/add-product', [ProductController::class, 'store'])->name('product.store');

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
});


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/users/{id}/block', [AdminController::class, 'blockUser'])->name('users.block');
    Route::post('/users/{id}/unblock', [AdminController::class, 'unblockUser'])->name('users.unblock');
});

Route::prefix('platforms')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/', [PlatformController::class, 'index'])->name('platform.index');
    Route::get('/add', [PlatformController::class, 'create'])->name('platform.create'); // Renamed "create" to "add"
    Route::post('/store', [PlatformController::class, 'store'])->name('platform.store');
    Route::delete('/delete/{platformName}', [PlatformController::class, 'destroy'])->name('platform.destroy'); // Renamed to "delete"
});
