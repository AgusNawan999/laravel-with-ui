<?php
use App\Http\Controllers\Admin\Content\BannerController;

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'admin.page']], function (){

  // Route with prefix content
  Route::group(['prefix' => 'content', 'as' => 'content.'], function () {
    // banner
    Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
      Route::get('/', [BannerController::class, 'list'])->name('get');
    });
    // banner
    Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
      Route::post('/', [BannerController::class, 'store'])->name('post');
      Route::put('/', [BannerController::class, 'edit'])->name('put');
      Route::delete('/', [BannerController::class, 'drop'])->name('delete');
    });
  });
});
