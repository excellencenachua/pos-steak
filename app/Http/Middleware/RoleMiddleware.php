<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login (memiliki token)
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized. Silakan login.'], 401);
        }

        // Cek apakah role user ada di dalam daftar role yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak memiliki akses ke halaman ini.'
            ], 403);
        }

        return $next($request);
    }
}