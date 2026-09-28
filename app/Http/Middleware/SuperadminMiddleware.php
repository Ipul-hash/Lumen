<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')->with('warning', 'Silakan masuk sebagai Superadmin.');
        }

        $user = Auth::user();

        if (!$user->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Superadmin privileges required.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Menu manajemen pengguna hanya dapat diakses oleh Superadmin.');
        }

        return $next($request);
    }
}
