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
        $ruta = $this->validar();

        if ($ruta !== null) {
            return redirect()->route($ruta);
        }

        return $next($request);
    }

    private function validar()
    {
        $user = auth('users')->user();

        if (isset($user)) {
            $role_id = $user->role_id;
            switch ($role_id) {
                case 1:
                    return 'admin.home';
                    break;
                case 2:
                    break;
            }
        } else {
            return 'login';
        }
        return null;
    }
}
