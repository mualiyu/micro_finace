<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function () {
    return view('about');
})->name('about');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/test', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/verification', [\App\Http\Controllers\VerificationController::class, 'index'])->name('verification');
Route::get('/verification', [\App\Http\Controllers\VerificationController::class, 'show'])->name('show_verification');
Route::post('/verify', [\App\Http\Controllers\VerificationController::class, 'verify_phone'])->name('verify_phone');


Route::get('/transfer', [\App\Http\Controllers\TransferController::class, 'index'])->name('show_transfer');
Route::post('/transfer', [\App\Http\Controllers\TransferController::class, 'transfer'])->name('transfer_m');
Route::get('/transfer/getbendata', [\App\Http\Controllers\TransferController::class, 'get_ben_data'])->name('get_ben_data');
