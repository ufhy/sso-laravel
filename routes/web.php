<?php

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

Route::get('/auth/ess', [\App\Http\Controllers\EssLoginController::class, 'redirect']);

Route::get('/auth/ess/callback', [\App\Http\Controllers\EssLoginController::class, 'callback']);

Route::get('/', function () {
    return view('welcome');
});
