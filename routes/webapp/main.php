<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Main Routes Web
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'api'], function () {
    Route::get('/nguyen-lieu-tinh/list', [HomeController::class, 'listNguyenLieuTinh'])->name('api.nguyen.lieu.tinh.list');
    Route::get('/nguyen-lieu-phan-loai/list', [HomeController::class, 'listNguyenLieuPhanLoai'])->name('api.nguyen.lieu.phan.loai.list');
});

