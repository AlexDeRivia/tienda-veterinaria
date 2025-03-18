<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function login()
    {
        return view('app.login');
    }

    public function register()
    {
        return view('app.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo' => "required|unique:users,email",
            'dni' => "required|numeric|digits:8|unique:users,dni",
            'celular' => "required|numeric|digits:9"
        ]);

        $user = new User();
        $user->email = $request->correo;
        $user->password = $request->contrasena;
        $user->dni = $request->dni;
        $user->names = $request->nombres;
        $user->surnames = $request->apellidos;
        $user->address = $request->direccion;
        $user->phone = $request->celular;
        $user->birthdate = $request->fech_nac;
        $user->save();

        $credentials = [
            'email' => $request->correo,
            'password' => $request->contrasena,
        ];

        auth('users')->attempt($credentials, true);

        $role_id = auth('users')->user()->role_id;
        
        switch ($role_id) {
            case 1:
                return redirect()->route('admin.home');
            case 2:
                return redirect()->route('cliente.home');
            default:
                return redirect()->route('login');
        }
    }
}
