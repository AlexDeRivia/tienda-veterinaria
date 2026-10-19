@extends('layouts.home')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="container py-5" style="max-width: 620px;">
    <div class="card shadow-sm">
        <div class="card-header"><h1 class="h4 mb-0">Recuperar contraseña</h1></div>
        <div class="card-body">
            <p>Escribe el correo de tu cuenta. Te enviaremos un enlace para crear una contraseña nueva.</p>
            @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
            @error('email') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <label class="form-label" for="email">Correo electrónico</label>
                <input class="form-control mb-3" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                <button class="btn btn-primary" type="submit">Enviar enlace</button>
                <a class="btn btn-link" href="{{ route('login') }}">Volver a ingresar</a>
            </form>
        </div>
    </div>
</div>
@endsection
