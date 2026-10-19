<?php

namespace App\Http\Middleware\Rol;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('users')->user();
        if ($user === null) {
            return redirect()->route('login');
        }

        if ((int) $user->role_id === 1) {
            return redirect()->route('admin.home');
        }

        abort_unless((int) $user->role_id === 2, 403);
        return $next($request);
    }
}
