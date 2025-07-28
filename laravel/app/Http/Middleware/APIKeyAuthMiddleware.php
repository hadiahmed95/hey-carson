<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APIKeyAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $enforce
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $enforce = '1'): mixed
    {
        $sharedSecret = env('PARTNERS_SECRET_KEY');
        $receivedSignature = $request->header('X-Signature');

        $data = $request->json()->all();
        $expectedSignature = hash_hmac('sha256', json_encode($data), $sharedSecret);

        if (!hash_equals($expectedSignature, $receivedSignature)) {
            return $this->unauthorized();
        }

        return $next($request);
    }

    /**
     * @return JsonResponse
     */
    protected function unauthorized(): \Illuminate\Http\JsonResponse
    {
        return response()
            ->json()
            ->setData(
                [
                    'status' => 401,
                    'message' => 'Unauthorized'
                ]
            )
            ->setStatusCode(401);
    }
}
