<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDosen
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isDosen(), 403);

        return $next($request);
    }
}
