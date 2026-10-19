<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    private const MAX_CLIENT_ACCOUNTS = 50;

    private const MAX_CLIENT_ACCOUNTS_PER_DAY = 10;

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
        $validated = $request->validate([
            'correo' => 'required|email|max:255|unique:users,email',
            'contrasena' => 'required|string|min:12|max:72',
            'nombres' => 'required|string|max:255',
        ]);

        $user = DB::transaction(function () use ($validated) {
            // The role row serializes concurrent registrations so both hard caps hold.
            Role::whereKey(2)->lockForUpdate()->firstOrFail();

            if (User::where('role_id', 2)->count() >= self::MAX_CLIENT_ACCOUNTS) {
                throw ValidationException::withMessages([
                    'correo' => 'Los cupos de esta demostración están completos.',
                ]);
            }
            if (User::where('role_id', 2)->where('created_at', '>=', now()->subDay())->count()
                >= self::MAX_CLIENT_ACCOUNTS_PER_DAY) {
                throw ValidationException::withMessages([
                    'correo' => 'Se alcanzó el límite diario de registros de esta demostración. Intenta más tarde.',
                ]);
            }

            $user = new User();
            $user->email = $validated['correo'];
            $user->password = $validated['contrasena'];
            $user->names = $validated['nombres'];
            $user->save();

            return $user;
        });

        $user->refresh();
        auth('users')->login($user);
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
    }
}
