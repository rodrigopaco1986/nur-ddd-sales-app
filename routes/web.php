<?php

use App\Http\Controllers\PactStateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/pact-state', PactStateController::class);
