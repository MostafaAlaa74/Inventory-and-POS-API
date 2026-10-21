<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckoutIdempotencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idempotencyKey = $request->header('X-IDEMPOTENCY-KEY');

        if (!$idempotencyKey) {
            return $next($request);
        }

        $cacheKey = "idempotency_{$idempotencyKey}";

        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            return response()->json($cachedResponse, 200, ['X-Cache' => 'HIT_IDEMPOTENT']);
        }

        
        $lock = Cache::lock("lock_{$cacheKey}", 15);

        if (!$lock->get()) {
            return response()->json([
                'message' => 'Request is already in progress. Please wait or do not double click.'
            ], 409); 
        }

        try {
            $response = $next($request);

            if ($response->isSuccessful()) {
                $responseData = json_decode($response->getContent(), true);
                Cache::put($cacheKey, $responseData, now()->addHours(10));
            }

            return $response;
        } finally {
            $lock->release();
        }
    }
}
