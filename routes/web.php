<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

include_route_files(__DIR__ . '/modules/');

Route::get('/media/download/{media}', [DownloadController::class, 'show'])->name('download.show');
Route::get('/media/downloadcustomname/{media}/{custom_name}', [DownloadController::class, 'showCustomName'])->name('download.custom-name.show');
Route::get('/media/download-alt/{params}', [DownloadController::class, 'showNonMedia'])->name('download.non-media.show');


Route::get('/{vue_capture?}', [HomeController::class, 'home'])
->where('vue_capture', '[\/\w\.-]*')
->name('home');
