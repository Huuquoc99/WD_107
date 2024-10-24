<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Kiểm tra xem người dùng có phải admin hay không
        if ($request->user()->type !== 1) { // 1 là admin
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
