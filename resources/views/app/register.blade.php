@extends('layouts.home')

@section('title', 'Crear cuenta | Tienda de mascotas')

@section('back', 'bg-light')

@section('content')

<div class="container py-lg-5 py-3">

    <div class="row">

        <div class="col-lg-8 offset-lg-2">

            <div class="card shadow-sm">

                <div class="card-header bg-uns"><a class="auth-brand" href="{{ route('home') }}">Tienda de mascotas</a></div>

                @error('correo')
                <div class="card-header">
                    <div class="alert alert-danger mb-0" role="alert">{{ $message }}</div>
                </div>
                @enderror

                @if ($errors->hasAny(['contrasena', 'nombres']))
                <div class="card-header">
                    <div class="alert alert-danger mb-0" role="alert">Revisa los datos del formulario.</div>
                </div>
                @endif

                <form method="post" action="{{ route('register.store') }}" id="clearForm" class="needs-validation" novalidate>
                    
                    @csrf

                    <div class="card-body">
                        <h1 class="auth-heading">Crea tu cuenta</h1>
                        <p class="auth-subtitle">Guarda productos en el carrito y prueba el recorrido de compra simulada. Usa un nombre de demostración; no necesitamos DNI, dirección ni teléfono.</p>
                        <h6>Datos de registro</h6>
                        <hr>
                        <div class="row text-start text-lg-end align-items-start">
                            <div class="col-lg-2">
                                <label for="correo" class="form-label">Correo</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('correo') }}" type="email" class="form-control" id="correo" name="correo" required>
                                <div class="invalid-feedback">Ingrese un correo electrónico válido</div>
                            </div>
                            <div class="col-lg-2">
                                <label for="contrasena" class="form-label">Contraseña</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" minlength="12" maxlength="72" required>
                                <div class="invalid-feedback">La contraseña es insegura</div>
                            </div>
                        </div>
                        <h6>Cómo quieres que te llamemos</h6>
                        <hr>
                        <div class="row text-start text-lg-end align-items-start">
                            <div class="col-lg-2">
                                <label for="nombres" class="form-label">Nombre o alias</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('nombres') }}" type="text" class="form-control" id="nombres" name="nombres" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="my-1">
                            <button type="button" class="btn btn-outline-primary" onclick="limpiarFormulario()">Limpiar</button>
                            <button type="submit" class="btn btn-primary ms-2">Registrarse</button>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <span>¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="link-primary">Logueate aquí</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('dist/js/clear.js') }}"></script>

@endsection
