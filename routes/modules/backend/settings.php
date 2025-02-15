<?php
// disini semua route dengan prefix backend tapi bisa diakses harus dengan auth

use App\Http\Controllers\Admin\Setting\{FeatureController};

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'admin.page']], function () {

  // route with prefix setting
  Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    // feature-access
    Route::group(['prefix' => 'features', 'as' => 'features.'], function () {
      Route::get('/', [FeatureController::class, 'list'])->name('get');
      Route::get('menu', [FeatureController::class, 'menu'])->name('menu');
      Route::get('hierarcy', [FeatureController::class, 'hierarcy'])->name('hierarcy');
    });

  });


});


