@extends('layouts.home')

@section('title', 'Nueva contraseña')

@section('content')
<div class="container py-5" style="max-width: 620px;">
    <div class="card shadow-sm">
        <div class="card-header"><h1 class="h4 mb-0">Crear nueva contraseña</h1></div>
        <div class="card-body">
            @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
            <form action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label class="form-label" for="email">Correo electrónico</label>
                <input class="form-control mb-3" id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required>
                <label class="form-label" for="password">Nueva contraseña</label>
                <input class="form-control mb-3" id="password" name="password" type="password" minlength="12" maxlength="72" autocomplete="new-password" required>
                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                <input class="form-control mb-3" id="password_confirmation" name="password_confirmation" type="password" minlength="12" maxlength="72" autocomplete="new-password" required>
                <button class="btn btn-primary" type="submit">Guardar contraseña</button>
            </form>
        </div>
    </div>
</div>
@endsection
