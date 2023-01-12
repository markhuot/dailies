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

Route::get('login', App\Http\Controllers\Session\CreateController::class)->name('session.create');
Route::post('login', App\Http\Controllers\Session\StoreController::class)->name('session.store');

Route::get('register', App\Http\Controllers\User\CreateController::class)->name('user.create');
Route::post('register', App\Http\Controllers\User\StoreController::class)->name('user.store');

Route::group(['middleware' => 'auth'], fn () => require __DIR__.'/web.authenticated.php');
