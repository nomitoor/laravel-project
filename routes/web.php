<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\CustomerPackagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SIPUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();
Auth::routes();


Route::group(['middleware' => ['auth']], function() {
    
    // Admin panel routes
    Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
    Route::get('layouts/boxed', [StaterkitController::class, 'layout_boxed'])->name('layout-boxed');
    Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
    Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
    Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

    // My routes
    Route::get('/', [PackagesController::class, 'index'])->name('index');
    
    Route::get('packages/all', [PackagesController::class, 'get_all_package'] );
    Route::resource('packages', PackagesController::class);
    
    Route::get('customer-packages/all', [CustomerPackagesController::class, 'get_all_customer_pack'] );
    Route::resource('customer-packages', CustomerPackagesController::class);
    
    Route::get('users/all', [UsersController::class, 'get_all_users'] );
    Route::resource('users', UsersController::class);
    
    
    Route::get('sip-users/all', [SIPUserController::class, 'get_all_sip_users'] );
    
    Route::get('sip-users/index', [App\Http\Controllers\SIPUserController::class, 'index'])->name('index');
    Route::get('sip-users/create', [App\Http\Controllers\SIPUserController::class, 'create'])->name('create');
    
    Route::get('sip-users/{id}', [App\Http\Controllers\SIPUserController::class, 'edit'])->name('edit');
    
    Route::resource('sip-users', SIPUserController::class);
    
    Route::post('/add_sip_users', [SIPUserController::class, 'add_sip_users'] );

});