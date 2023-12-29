<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::resource('orders', OrderController::class);

Route::post('token', [PaymentController::class, 'token'])->name('bkash.token');

Route::get('createpayment', [PaymentController::class, 'createpayment'])->name('bkash.createpayment');
Route::get('executepayment', [PaymentController::class, 'executepayment'])->name('bkash.executepayment');
