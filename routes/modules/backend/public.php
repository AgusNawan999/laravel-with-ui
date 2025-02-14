<?php

// disini semua route dengan prefix backend tapi bisa diakses tanpa auth

use App\Http\Controllers\Reference\ComplaintCategoryController;

Route::group(['prefix' => 'backend', 'as' => 'backend.'], function () {
  Route::get('/captcha', [App\Http\Controllers\CaptchaController::class, 'index'])->name('captcha.generate');
  Route::get('/audio-captcha', [App\Http\Controllers\CaptchaController::class, 'read'])->name('captcha.audio');
  Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

  Route::group(['prefix' => 'reference', 'as' => 'reference.'], function() {
    Route::group(['prefix' => 'complaint', 'as' => 'complaint.'], function() {
      Route::get('category/{level}', [ComplaintCategoryController::class, 'listByLevel'])->name('categories');
    });
  });
});
