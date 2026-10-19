<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth('users')->user();
        $validated = $request->validate([
            'dni' => ['nullable', 'digits:8', Rule::unique('users', 'dni')->ignore($user->id)],
            'names' => 'required|string|max:255',
            'surnames' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date|before:today',
            'phone' => 'nullable|digits:9',
            'address' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => 'nullable|string',
        ]);

        $identityChanged = $validated['email'] !== $user->email
            || ($validated['dni'] ?? $user->dni) !== $user->dni;
        if ($identityChanged && !Hash::check($validated['current_password'] ?? '', $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Escribe tu contraseña actual para cambiar el correo o el DNI.',
            ]);
        }

        unset($validated['current_password']);
        foreach ($validated as $field => $value) {
            $user->{$field} = $value;
        }
        $user->save();

        return back()->with('status', 'Datos actualizados correctamente.');
    }
}
