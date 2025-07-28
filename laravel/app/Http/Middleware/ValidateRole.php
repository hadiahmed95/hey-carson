<?php

namespace App\Http\Middleware;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\HttpFoundation\Response;

class ValidateRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @param int $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, int $role): Response
    {
        if (Auth::user()->role_id !== $role) {
            return $this->forbidden();
        }
        return $next($request);
    }

    /**
     * @return JsonResponse
     */
    private function forbidden(): JsonResponse
    {
        return response()
            ->json()
            ->setData(
                [
                    'code' => 'forbidden',
                    'message' => 'Forbidden'
                ]
            )
            ->setStatusCode(403);
    }
}
