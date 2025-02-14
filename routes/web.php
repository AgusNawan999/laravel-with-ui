<?php

use App\Http\Controllers\HomeController;

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
include_route_files(__DIR__ . '/modules/');

Route::get('/{vue_capture?}', [HomeController::class, 'index'])
->where('vue_capture', '[\/\w\.-]*')
->name('home');
