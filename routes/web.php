<?php

use App\Http\Controllers\CustomerOrdersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApiController;
use App\Http\Livewire\CheckoutPage;
use App\Http\Livewire\CheckoutSuccessPage;
use App\Http\Livewire\CollectionPage;
use App\Http\Livewire\CustomerOrdersPage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;
use App\Http\Livewire\ProductPage;
use App\Http\Livewire\SearchPage;

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


Route::get('/', Home::class)->name('home');


Route::middleware('auth', 'verified')->group(function () {
    Route::get('orders', CustomerOrdersPage::class)->name('orders');
});
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//storefront
Route::get('/collections/{slug}', CollectionPage::class)->name('collection.view');

Route::get('/products/{slug}', ProductPage::class)->name('product.view');

Route::get('search', SearchPage::class)->name('search.view');

Route::middleware('auth', 'verified')->group(function () {

    Route::get('checkout', CheckoutPage::class)->name('checkout.view');

    Route::get('checkout/success', CheckoutSuccessPage::class)->name('checkout-success.view');
});



require __DIR__ . '/auth.php';
