<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Livewire\Login;

use App\Livewire\AdminView;
use App\Livewire\AdminAdd;
use App\Livewire\AdminEdit;

use App\Livewire\RoleView;
use App\Livewire\RoleAdd;
use App\Livewire\RoleEdit;

use App\Livewire\AmenityView;
use App\Livewire\AmenityAdd;
use App\Livewire\AmenityEdit;

use App\Livewire\CategoryView;
use App\Livewire\CategoryAdd;
use App\Livewire\CategoryEdit;

use App\Livewire\RoomView;
use App\Livewire\RoomAdd;
use App\Livewire\RoomEdit;

use App\Livewire\RestaurantView;
use App\Livewire\RestaurantAdd;
use App\Livewire\RestaurantEdit;

use App\Livewire\MenuView;
use App\Livewire\MenuAdd;
use App\Livewire\MenuEdit;

use App\Livewire\AboutUs;
use App\Livewire\SocialMedia;
use App\Livewire\SwimmingPool;


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

Route::get('sign-in', action: Login::class)->name('login');
Route::get('sign-up', action: Register::class)->name('register');
Route::get('restaurant', action: Login::class)->name('restaurant');


Route::get('pool', action: SwimmingPool::class)->name('pool');
Route::get('about-us', action: AboutUs::class)->name('about');
Route::get('social-media', action: SocialMedia::class)->name('socials');





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


Route::group(['prefix' => 'users'], function () {

    // users/admins/
    Route::group(['prefix' => 'admins'], function () {
        Route::get('/', AdminView::class)->name('admins');
        Route::get('/add', AdminAdd::class)->name('add-admin');
        Route::get('/edit/{id}', AdminEdit::class)->name('edit-admin');
    });

    // users/roles/
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', RoleView::class)->name('roles');
        Route::get('/add', RoleAdd::class)->name('add-role');
        Route::get('/edit/{id}', RoleEdit::class)->name('edit-role');
    });
});

Route::group(['prefix' => 'accomodation'], function () {

    // accomodation/rooms/
    Route::group(['prefix' => 'rooms'], function () {
        Route::get('/', RoomView::class)->name('rooms');
        Route::get('/add', RoomAdd::class)->name('add-room');
        Route::get('/edit/{id}', RoomEdit::class)->name('edit-room');
    });

    // accomodation/categories/
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', CategoryView::class)->name('categories');
        Route::get('/add', CategoryAdd::class)->name('add-category');
        Route::get('/edit/{id}', CategoryEdit::class)->name('edit-category');
    });

    // accomodation/amenities/
    Route::group(['prefix' => 'amenities'], function () {
        Route::get('/', AmenityView::class)->name('amenities');
        Route::get('/add', AmenityAdd::class)->name('add-amenity');
        Route::get('/edit/{id}', AmenityEdit::class)->name('edit-amenity');
    });
});
