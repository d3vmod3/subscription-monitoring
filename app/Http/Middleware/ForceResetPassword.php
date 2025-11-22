<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user must reset password, redirect them
        if ($user && $user->is_password_reset) {
            if (!$request->routeIs('user.reset.password')) {
                return redirect()->route('user.reset.password');
            }
        }

        return $next($request);
    }
}
