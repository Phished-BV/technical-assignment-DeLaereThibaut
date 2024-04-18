<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

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

Route::post('/receive-email', [OrderController::class, 'receiveEmail']);

Route::get('/orders/{order}/edit-status', [OrderController::class, 'editStatus'])->name('orders.edit-status');
Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

Route::get('/', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
