<?php

/*
|--------------------------------------------------------------------------
| Admin Routes Web
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\admin\AdminAttributeController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminConsultantController;
use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminOrderController;
use App\Http\Controllers\admin\AdminProductController;
use App\Http\Controllers\admin\AdminPropertyController;
use App\Http\Controllers\admin\AdminPurchaseController;
use App\Http\Controllers\admin\AdminSettingController;
use App\Http\Controllers\admin\AdminUserController;

Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.home');

Route::group(['prefix' => 'app-settings'], function () {
    Route::get('/index', [AdminSettingController::class, 'index'])->name('admin.app.setting.index');
    Route::post('/store', [AdminSettingController::class, 'appSetting'])->name('admin.app.setting.store');
});

Route::group(['prefix' => 'api'], function () {

});
