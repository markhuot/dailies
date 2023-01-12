<?php

use Illuminate\Support\Facades\Route;

Route::get('', App\Http\Controllers\Dashboard\ShowController::class)->name('dashboard.show');

Route::post('task', App\Http\Controllers\Task\StoreController::class)->name('task.store');
Route::put('task/{task}', App\Http\Controllers\Task\UpdateController::class)->name('task.update');
Route::get('task/{task}/notes', App\Http\Controllers\Note\EditController::class)->name('note.edit');
Route::put('task/{task}/notes', App\Http\Controllers\Note\UpdateController::class)->name('note.update');

Route::post('task/{task}/timer', App\Http\Controllers\Timer\ToggleController::class)->name('timer.toggle');

Route::get('settings', App\Http\Controllers\Settings\IndexController::class)->name('settings.index');
Route::put('settings', App\Http\Controllers\Settings\UpdateController::class)->name('settings.update');

Route::get('oauth/google-calendar/redirect', App\Http\Controllers\Oauth\GoogleCalendar\RedirectController::class)->name('oauth.google-calendar.redirect');
Route::get('oauth/google-calendar/callback', App\Http\Controllers\Oauth\GoogleCalendar\CallbackController::class)->name('oauth.google-calendar.callback');

