<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


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

//Route::redirect(uri: '/', 'login');

//Route::middleware(['auth:sanctum', 'verified'])->group(function () {

// Route for the getting the data feed
Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

Route::get('/login', function () {
    return redirect('sign-in');
})->name('login');

Route::get('/dashboard', action: Dashboard::class)->name('dashboard');
Route::get('/dashboard/analytics', function () {
    Log::info('Analytics page loaded');
})->name('analytics');
Route::get('/dashboard/fintech', function () {
    Log::info('fintech page loaded');
})->name('fintech');
Route::get('/ecommerce/customers', function () {
    Log::info('customers page loaded');
})->name('customers');
Route::get('/ecommerce/orders', function () {
    Log::info('orders page loaded');
})->name('orders');
Route::get('/ecommerce/invoices', function () {
    Log::info('invoices page loaded');
})->name('invoices');
Route::get('sign-in', action: Login::class)->name('login');
Route::get('sign-up', action: Register::class)->name('register');
Route::get('users/roles', action: Login::class)->name('roles');
Route::get('users/admins', action: Login::class)->name('admins');
Route::get('accomodation/roles', action: Login::class)->name('rooms');
Route::get('accomodation/rooms/categories', action: Login::class)->name('categories');
Route::get('accomodation/rooms/amenities', action: Login::class)->name('amenities');
Route::get('restaurant', action: Login::class)->name('restaurant');
Route::get('pool', action: Login::class)->name('pool');
Route::get('about-us', action: Login::class)->name('about');
Route::get('social-media', action: Login::class)->name('socials');





Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fa'])) { // Add your supported locales here
        session::put(['locale' => $locale]);
        Log::info("the locale is: $locale");
    }
    return redirect()->back();
})->name('locale.switch');




Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
Route::get('/community/users-tabs', [MemberController::class, 'indexTabs'])->name('users-tabs');
Route::get('/community/users-tiles', [MemberController::class, 'indexTiles'])->name('users-tiles');
Route::get('/community/profile', function () {
    Log::info('Profile page loaded');
})->name('profile');

Route::get('/settings/account', function () {
    Log::info('Account page loaded');
})->name('account');
Route::get('/settings/billing', function () {
    Log::info('Billing page loaded');
})->name('billing');
Route::get('/utility/changelog', function () {
    Log::info('Changelog page loaded');
})->name('changelog');

//    Route::fallback(function () {
//        return view('pages/utility/404');
//    });
//});
