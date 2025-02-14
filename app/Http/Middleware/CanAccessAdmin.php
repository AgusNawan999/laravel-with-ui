<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanAccessAdmin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $permission = config('app.admin_home_permission');
    if (!$permission)
      return redirect('/');

    if (!in_array($permission, session()->get('user.permissions')))
      return redirect('/');

    return $next($request);
  }
}
