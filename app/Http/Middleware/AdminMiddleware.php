<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->isAdmin == true)
                    return $next($request);
            }
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }
    }
}
