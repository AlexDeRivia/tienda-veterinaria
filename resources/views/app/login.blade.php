@extends('layouts.home')

@section('title', 'Ingresar | Tienda de mascotas')

@section('back', 'bg-light')

@section('content')

<link rel="stylesheet" href="{{ asset('dist/css/login.css') }}">

<div class="container py-5 my-3">

    <div class="row">

        <div class="col-lg-6 offset-lg-3">

            <div class="card shadow-sm">

                <div class="card-header bg-uns"><a class="auth-brand" href="{{ route('home') }}">Tienda de mascotas</a></div>
                @if (session('status'))
                    <div class="card-header"><div class="alert alert-success mb-0" role="status">{{ session('status') }}</div></div>
                @endif

                @if ($errors->has('correo'))
                <div class="card-header">
                    <div class="alert alert-danger mb-0" role="alert">{{ $errors->first('correo') }}</div>
                </div>
                @endif

                <form action="{{ route('login.auth') }}" method="post" id="clearForm" class="needs-validation" novalidate>

                    @csrf

                    <div class="card-body">
                        <h1 class="auth-heading">Ingresa a tu cuenta</h1>
                        <p class="auth-subtitle">Continúa con tu carrito y revisa tus compras de demostración.</p>
                        <div class="form-floating mb-3">
                            <input value="{{ old('correo') }}" type="email" class="form-control" placeholder="Correo" id="correo" name="correo" aria-describedby="emailHelp" required>
                            <label for="correo" class="form-label">Correo</label>
                            <div class="invalid-feedback">Ingrese un correo electrónico válido</div>
                        </div>
                        <div class="form-floating pass-show">
                            <input value="{{ old('contrasena') }}" type="password" class="form-control" placeholder="Contraseña" id="contrasena" name="contrasena" required>
                            <button class="password-toggle" type="button" aria-label="Mostrar contraseña" aria-pressed="false"><i class="bi bi-eye-fill" id="eye" aria-hidden="true"></i></button>
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <div class="invalid-feedback">Ingrese su contraseña</div>
                        </div>
                        <div class="col mt-3 text-start">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recordar mi inicio de sesión
                            </label>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="row my-1">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary ms-2" onclick="limpiarFormulario()">
                                    Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary ms-2" {{ session('disabled') ? 'disabled' : '' }}>
                                    Ingresar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-footer text-center">
                    <span>¿No tienes una cuenta? <a href="{{ route('register') }}" class="link-primary">Registrarte aquí</a></span>
                    <div><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('dist/js/login.js') }}"></script>
<script src="{{ asset('dist/js/clear.js') }}"></script>

@endsection
