<?php

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin.page']], function () {
  Route::get('/{vue_capture?}', [App\Http\Controllers\Admin\HomeController::class, 'admin'])
  ->where('vue_capture', '[\/\w\.-]*')
  ->name('home');
});

Route::get('timestamp', function () {
  return Str::getCurrentDate('l, d F Y H:m:s');
})->name('timestamp');
