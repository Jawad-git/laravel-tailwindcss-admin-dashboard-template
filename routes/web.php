<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Livewire\auth\Login;

use App\Livewire\admins\AdminView;
use App\Livewire\admins\AdminAdd;
use App\Livewire\admins\AdminEdit;

use App\Livewire\roles\RoleView;
use App\Livewire\roles\RoleAdd;
use App\Livewire\roles\RoleEdit;

use App\Livewire\amenities\AmenityView;
use App\Livewire\amenities\AmenityAdd;
use App\Livewire\amenities\AmenityEdit;

use App\Livewire\roomCategories\CategoryView;
use App\Livewire\roomCategories\CategoryAdd;
use App\Livewire\roomCategories\CategoryEdit;

use App\Livewire\rooms\RoomView;
use App\Livewire\rooms\RoomAdd;
use App\Livewire\rooms\RoomEdit;

use App\Livewire\restaurant\menus\MenuView;
use App\Livewire\restaurant\menus\MenuAdd;
use App\Livewire\restaurant\menus\MenuEdit;

use App\Livewire\restaurant\foods\FoodView;
use App\Livewire\restaurant\foods\FoodAdd;
use App\Livewire\restaurant\foods\FoodEdit;

use App\Livewire\RestaurantView;
use App\Livewire\RestaurantAdd;
use App\Livewire\RestaurantEdit;

use App\Livewire\restaurant\RestaurantManagement;

use App\Livewire\AboutUs;
use App\Livewire\SocialMediaManagement;
use App\Livewire\SwimmingPoolManagement;


use App\Livewire\auth\Register;
use App\Livewire\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use App\Livewire\AboutUsManagement;
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
Route::get('/dashboard/fintech', function () {
    Log::info('fintech page loaded');
})->name('fintech');

Route::get('sign-in', action: Login::class)->name('login');
Route::get('sign-up', action: Register::class)->name('register');
Route::get('restaurant', action: Login::class)->name('restaurant');


Route::get('pool', action: SwimmingPoolManagement::class)->name('pool');
Route::get('about-us', action: AboutUsManagement::class)->name('about');
Route::get('social-media', action: SocialMediaManagement::class)->name('socials');





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
        Route::get('/add', RoomAdd::class)->name('room-create');
        Route::get('/edit/{id}', RoomEdit::class)->name('room-edit');
    });

    // accomodation/categories/
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', CategoryView::class)->name('categories');
        Route::get('/add', CategoryAdd::class)->name('category-create');
        Route::get('/edit/{id}', CategoryEdit::class)->name('category-edit');
    });

    // accomodation/amenities/
    Route::group(['prefix' => 'amenities'], function () {
        Route::get('/', AmenityView::class)->name('amenities');
        Route::get('/add', AmenityAdd::class)->name('amenity-create');
        Route::get('/edit/{id}', AmenityEdit::class)->name('amenity-edit');
    });
});

Route::group(['prefix' => 'restaurant'], function () {

    Route::get('/', RestaurantManagement::class)->name('restaurant');

    // restaurant/foods/
    Route::group(['prefix' => 'foods'], function () {
        Route::get('/', FoodView::class)->name('foods');
        Route::get('/add', FoodAdd::class)->name('food-create');
        Route::get('/edit/{id}', FoodEdit::class)->name('food-edit');
    });

    // restaurant/menus/
    Route::group(['prefix' => 'menus'], function () {
        Route::get('/', MenuView::class)->name('menus');
        Route::get('/add', MenuAdd::class)->name('menu-create');
        Route::get('/edit/{id}', MenuEdit::class)->name('menu-edit');
    });
});
