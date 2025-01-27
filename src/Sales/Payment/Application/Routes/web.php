<?php

use Illuminate\Support\Facades\Route;
//use Src\Sales\Payment\Presentation\Controllers\CreateOrderController;
use Src\Sales\Payment\Presentation\Controllers\ViewPaymentController;
use Src\Sales\Payment\Presentation\Controllers\ViewPaymentsByOrderController;
use Src\Sales\Payment\Presentation\Controllers\MakePaymentController;

Route::get('/view/{id}', ViewPaymentController::class);
Route::get('/view-by-order/{orderId}', ViewPaymentsByOrderController::class);
Route::post('/make', MakePaymentController::class);