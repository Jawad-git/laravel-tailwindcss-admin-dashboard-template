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

Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fa'])) { // Add your supported locales here
        session::put(['locale' => $locale]);
        Log::info("the locale is: $locale");
    }
    return redirect()->back();
})->name('locale.switch');



Route::get('/ecommerce/shop', function () {
    Log::info('Shop page loaded');
})->name('shop');
Route::get('/ecommerce/shop-2', function () {
    Log::info('Shop 2 page loaded');
})->name('shop-2');
Route::get('/ecommerce/product', function () {
    Log::info('Product page loaded');
})->name('product');
Route::get('/ecommerce/cart', function () {
    Log::info('Cart page loaded');
})->name('cart');
Route::get('/ecommerce/cart-2', function () {
    Log::info('Cart 2 page loaded');
})->name('cart-2');
Route::get('/ecommerce/cart-3', function () {
    Log::info('Cart 3 page loaded');
})->name('cart-3');
Route::get('/ecommerce/pay', function () {
    Log::info('Pay page loaded');
})->name('pay');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
Route::get('/community/users-tabs', [MemberController::class, 'indexTabs'])->name('users-tabs');
Route::get('/community/users-tiles', [MemberController::class, 'indexTiles'])->name('users-tiles');
Route::get('/community/profile', function () {
    Log::info('Profile page loaded');
})->name('profile');
Route::get('/community/feed', function () {
    Log::info('Feed page loaded');
})->name('feed');
Route::get('/community/forum', function () {
    Log::info('Forum page loaded');
})->name('forum');
Route::get('/community/forum-post', function () {
    Log::info('Forum post page loaded');
})->name('forum-post');
Route::get('/community/meetups', function () {
    Log::info('Meetups page loaded');
})->name('meetups');
Route::get('/community/meetups-post', function () {
    Log::info('Meetups post page loaded');
})->name('meetups-post');
Route::get('/finance/cards', function () {
    Log::info('Credit cards page loaded');
})->name('credit-cards');
Route::get('/finance/transactions', [TransactionController::class, 'index01'])->name('transactions');
Route::get('/finance/transaction-details', [TransactionController::class, 'index02'])->name('transaction-details');
Route::get('/job/job-listing', [JobController::class, 'index'])->name('job-listing');
Route::get('/job/job-post', function () {
    Log::info('Job post page loaded');
})->name('job-post');
Route::get('/job/company-profile', function () {
    Log::info('Company profile page loaded');
})->name('company-profile');
Route::get('/messages', function () {
    Log::info('Messages page loaded');
})->name('messages');
Route::get('/tasks/kanban', function () {
    Log::info('Kanban tasks page loaded');
})->name('tasks-kanban');
Route::get('/tasks/list', function () {
    Log::info('Task list page loaded');
})->name('tasks-list');
Route::get('/inbox', function () {
    Log::info('Inbox page loaded');
})->name('inbox');
Route::get('/calendar', function () {
    Log::info('Calendar page loaded');
})->name('calendar');
Route::get('/settings/account', function () {
    Log::info('Account page loaded');
})->name('account');
Route::get('/settings/notifications', function () {
    Log::info('Notifications page loaded');
})->name('notifications');
Route::get('/settings/apps', function () {
    Log::info('Apps page loaded');
})->name('apps');
Route::get('/settings/plans', function () {
    Log::info('Plans page loaded');
})->name('plans');
Route::get('/settings/billing', function () {
    Log::info('Billing page loaded');
})->name('billing');
Route::get('/settings/feedback', function () {
    Log::info('Feedback page loaded');
})->name('feedback');
Route::get('/utility/changelog', function () {
    Log::info('Changelog page loaded');
})->name('changelog');
Route::get('/utility/roadmap', function () {
    Log::info('Roadmap page loaded');
})->name('roadmap');
Route::get('/utility/faqs', function () {
    Log::info('Faqs page loaded');
})->name('faqs');
Route::get('/utility/empty-state', function () {
    Log::info('Empty state page loaded');
})->name('empty-state');
Route::get('/utility/404', function () {
    Log::info('404 page loaded');
})->name('404');
Route::get('/utility/knowledge-base', function () {
    Log::info('Knowledge base page loaded');
})->name('knowledge-base');
Route::get('/onboarding-01', function () {
    Log::info('Onboarding 01 page loaded');
})->name('onboarding-01');
Route::get('/onboarding-02', function () {
    Log::info('Onboarding 02 page loaded');
})->name('onboarding-02');
Route::get('/onboarding-03', function () {
    Log::info('Onboarding 03 page loaded');
})->name('onboarding-03');
Route::get('/onboarding-04', function () {
    Log::info('Onboarding 04 page loaded');
})->name('onboarding-04');
Route::get('/component/button', function () {
    Log::info('Button page loaded');
})->name('button-page');
Route::get('/component/form', function () {
    Log::info('Form page loaded');
})->name('form-page');
Route::get('/component/dropdown', function () {
    Log::info('Dropdown page loaded');
})->name('dropdown-page');
Route::get('/component/alert', function () {
    Log::info('Alert page loaded');
})->name('alert-page');
Route::get('/component/modal', function () {
    Log::info('Modal page loaded');
})->name('modal-page');
Route::get('/component/pagination', function () {
    Log::info('Pagination page loaded');
})->name('pagination-page');
Route::get('/component/tabs', function () {
    Log::info('Tabs page loaded');
})->name('tabs-page');
Route::get('/component/breadcrumb', function () {
    Log::info('Breadcrumb page loaded');
})->name('breadcrumb-page');
Route::get('/component/badge', function () {
    Log::info('Badge page loaded');
})->name('badge-page');
Route::get('/component/avatar', function () {
    Log::info('Avatar page loaded');
})->name('avatar-page');
Route::get('/component/tooltip', function () {
    Log::info('Tooltip page loaded');
})->name('tooltip-page');
Route::get('/component/accordion', function () {
    Log::info('Accordion page loaded');
})->name('accordion-page');
Route::get('/component/icons', function () {
    Log::info('Icons page loaded');
})->name('icons-page');

//    Route::fallback(function () {
//        return view('pages/utility/404');
//    });
//});
