<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Batasi akses hanya untuk pengguna ber-role "admin".
 * Editor mendapat 403 pada area konfigurasi & sistem.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403, 'Halaman ini hanya untuk Administrator.');

        return $next($request);
    }
}
