<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')->with('warning', 'Silakan masuk terlebih dahulu untuk mengakses Portal Admin.');
        }

        $user = Auth::user();

        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Admin access required.'], 403);
            }
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Akun Anda tidak memiliki hak akses administrator.');
        }

        return $next($request);
    }
}
