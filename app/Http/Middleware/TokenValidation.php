<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidation
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $hasToken = $request->bearerToken();
    if (!$hasToken) {
      return response()->json([
        'status' => 'error',
        'message' => 'Permintaan harus disertakan token yang valid.'
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    return $next($request);
  }
}
