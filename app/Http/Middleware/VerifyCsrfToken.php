<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
  /**
   * Indicates whether the XSRF-TOKEN cookie should be set on the response.
   *
   * @var bool
   */
  protected $addHttpCookie = true;

  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array<int, string>
   */
  protected $except = [
    //
  ];

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   *
   * @throws \Illuminate\Session\TokenMismatchException
   */
  public function handle($request, Closure $next)
  {
    if (Auth::check() && $request->route()->named('auth.logout')) {
      $this->except[] = route('auth.logout');
    }

    return parent::handle($request, $next);
  }
}
