<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    if ($request->is('api/*')) {
      [,$getVersion] = explode('/', Str::after($request->url(), config('app.url') . '/'));
      return route($getVersion .'.invalid.token');
    }

    return $request->expectsJson() ? null : route('home');
  }
}
