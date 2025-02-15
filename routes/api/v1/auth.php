<?php

use App\Http\Controllers\Auth\GenerateTokenController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
  Route::post('login', [GenerateTokenController::class, 'login'])->name('login');
});
