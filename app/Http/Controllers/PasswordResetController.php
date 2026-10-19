<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PasswordResetEmailQuota;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('app.password-forgot');
    }

    public function sendLink(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $user = User::where('email', $validated['email'])->first();
        if ($user && app(PasswordResetEmailQuota::class)->claim($user)) {
            Password::broker('users')->sendResetLink($validated);
        }

        return back()->with('status', 'Si existe una cuenta con ese correo, recibirás un enlace para restablecer la contraseña.');
    }

    public function resetForm(string $token, Request $request)
    {
        return view('app.password-reset', ['token' => $token, 'email' => $request->query('email', '')]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:12|max:72|confirmed',
        ]);

        $status = Password::broker('users')->reset($validated, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])
                ->setRememberToken(Str::random(60));
            $user->save();
            if (config('session.driver') === 'database') {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            event(new PasswordReset($user));
        });

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => 'El enlace no es válido o ha caducado. Solicita uno nuevo.'])->withInput($request->only('email'));
        }

        return redirect()->route('login')->with('status', 'Contraseña actualizada. Ya puedes ingresar.');
    }
}
