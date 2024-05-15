<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VrefyUpdateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $model = $request->route('document') ?? $request->route('comment');

        if ($model->user_id !== Auth::user()->id) {
            return response()->json([
                'error' => 'Unauthorized'
            ],403);
        }

        return $next($request);
    }
}
