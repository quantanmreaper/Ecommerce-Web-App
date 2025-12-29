<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CartPage;
use App\Livewire\MyOrdersPage;
use Illuminate\Support\Facades\Route;
use \App\Livewire\HomePage;
use \App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\CancelPage;
use App\Livewire\SuccessPage;

/* Route::get('/', function () {
    return view('livewire.home-page');
});  */

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/products/{slug}',ProductDetailPage::class);




Route::middleware('guest')->group(function () {
    Route::get('/login',LoginPage::class);
    Route::get('/register',RegisterPage::class);
    Route::get('/forgot',ForgotPasswordPage::class);
    Route::get('/reset',ResetPasswordPage::class);
});

Route::middleware('auth')->group(function () {
    //protected routes go here

    Route::get('/logout', function(){
        auth()->guard()->logout();
        return redirect()->to('/');
    })->name('logout');

    Route::get('/checkout',CheckoutPage::class);
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order}', MyOrderDetailPage::class);
    Route::get('/cancel', CancelPage::class);
    Route::get('/success', SuccessPage::class);

});
