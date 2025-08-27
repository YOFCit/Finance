<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DeviceTokenMiddleware
{
  public function handle($request, Closure $next)
  {
    if (!$request->hasCookie('device_token')) {
      $token = Str::uuid()->toString();
      cookie()->queue(cookie('device_token', $token, 60 * 24 * 365)); // 1 año
    } else {
      $token = $request->cookie('device_token');
    }

    $request->merge(['device_token' => $token]);

    return $next($request);
  }
}
