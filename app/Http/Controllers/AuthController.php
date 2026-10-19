<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);
        $email = $request->correo;
        $password = $request->contrasena;
        $remember = $request->has('remember');

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (auth('users')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $role_id = auth('users')->user()->role_id;
            switch ($role_id) {
                case 1:
                    return redirect()->route('admin.home');
                case 2:
                    return redirect()->route('cliente.home');
                default:
                    return redirect()->route('login');
            }
        } else {
            return back()->withErrors(['correo' => 'Credenciales incorrectas'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        auth('users')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
