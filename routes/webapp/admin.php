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

use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminNguyenLieuThoController;
use App\Http\Controllers\admin\AdminNhaCungCapController;
use App\Http\Controllers\admin\AdminSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.home');

Route::group(['prefix' => 'app-settings'], function () {
    Route::get('/index', [AdminSettingController::class, 'index'])->name('admin.app.setting.index');
    Route::post('/store', [AdminSettingController::class, 'appSetting'])->name('admin.app.setting.store');
});

Route::group(['prefix' => 'nha-cung-cap'], function () {
    Route::get('/index', [AdminNhaCungCapController::class, 'index'])->name('admin.nha.cung.cap.index');
    Route::get('/detail/{id}', [AdminNhaCungCapController::class, 'detail'])->name('admin.nha.cung.cap.detail');
    Route::post('/store', [AdminNhaCungCapController::class, 'store'])->name('admin.nha.cung.cap.store');
    Route::put('/update/{id}', [AdminNhaCungCapController::class, 'update'])->name('admin.nha.cung.cap.update');
    Route::delete('/delete/{id}', [AdminNhaCungCapController::class, 'delete'])->name('admin.nha.cung.cap.delete');
});

Route::group(['prefix' => 'nguyen-lieu-tho'], function () {
    Route::get('/index', [AdminNguyenLieuThoController::class, 'index'])->name('admin.nguyen.lieu.tho.index');
    Route::get('/detail/{id}', [AdminNguyenLieuThoController::class, 'detail'])->name('admin.nguyen.lieu.tho.detail');
    Route::post('/store', [AdminNguyenLieuThoController::class, 'store'])->name('admin.nguyen.lieu.tho.store');
    Route::put('/update/{id}', [AdminNguyenLieuThoController::class, 'update'])->name('admin.nguyen.lieu.tho.update');
    Route::delete('/delete/{id}', [AdminNguyenLieuThoController::class, 'delete'])->name('admin.nguyen.lieu.tho.delete');
});

Route::group(['prefix' => 'api'], function () {

});
