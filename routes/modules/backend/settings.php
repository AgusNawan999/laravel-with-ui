<?php
// disini semua route dengan prefix backend tapi bisa diakses harus dengan auth

use App\Http\Controllers\Admin\Setting\{FeatureController, GroupController, UserController};

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'admin.page']], function () {

  // route with prefix setting
  Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    // feature-access
    Route::group(['prefix' => 'features', 'as' => 'features.'], function () {
      Route::get('/', [FeatureController::class, 'list'])->name('get');
      Route::get('menu', [FeatureController::class, 'menu'])->name('menu');
      Route::get('hierarcy', [FeatureController::class, 'hierarcy'])->name('hierarcy');
    });
    Route::group(['prefix' => 'feature', 'as' => 'feature.'], function() {
      Route::post('/', [FeatureController::class, 'store'])->name('post');
      Route::put('/', [FeatureController::class, 'edit'])->name('put');
      Route::delete('/', [FeatureController::class, 'drop'])->name('delete');
    });

    Route::group(['prefix' => 'groups', 'as' => 'groups.'], function () {
      Route::get('/', [GroupController::class, 'list'])->name('get');
    });

    Route::group(['prefix' => 'group', 'as' => 'group.'], function () {
      Route::get('{slug}/features', [GroupController::class, 'features'])->name('features');

      Route::post('/', [GroupController::class, 'store'])->name('post');
      Route::put('/', [GroupController::class, 'edit'])->name('put');
      Route::delete('/', [GroupController::class, 'drop'])->name('delete');
    });

  });
  Route::group(['prefix' => 'setting-management-users', 'as' => 'setting.management.'], function() {

    Route::get('management-users-get', [UserController::class, 'list'])->name('user.get');
    Route::get('get-group-users', [UserController::class, 'getGroupUsers'])->name('user.getGroupUsers');

    Route::group(['prefix' => 'users', 'as' => 'users.'], function() {
      Route::post('/', [UserController::class, 'store'])->name('post');
      Route::put('/', [UserController::class, 'edit'])->name('put');
      Route::delete('/', [UserController::class, 'drop'])->name('delete');
    });
  });


});


