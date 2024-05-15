<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::beginTransaction();

            $response = $next($request);
            
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['message' => 'Something Error !'], 500);
        }

        return $response;
    }
}
