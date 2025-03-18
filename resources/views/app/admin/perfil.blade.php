@extends('layouts.admin')

@section('title', 'Mi perfil')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.home') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container">

    <div class="card mb-3" style="max-width: 750px;">
        <div class="card-header">
            Información Personal
        </div>
        <div class="row g-0">
            <div class="col-md-4 p-3">
                <img src="{{ asset('img/perfil/user.png') }}" class="img-fluid" alt="perfil">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                            <label for="">DNI: </label>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" value="{{ $user->dni }}" aria-label="Disabled input example" disabled readonly>
                                <label class="form-label"><small>DNI</small></label>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                            <label for="">Nombres: </label>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" value="{{ $user->names }}" aria-label="Disabled input example" disabled readonly>
                                <label class="form-label"><small>Nombres</small></label>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                            <label for="">Apellidos: </label>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" value="{{ $user->surnames }}" aria-label="Disabled input example" disabled readonly>
                                <label class="form-label"><small>Apellidos</small></label>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                            <label for="">Fecha de nacimiento: </label>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" value="{{ $user->birthdate }}" aria-label="Disabled input example" disabled readonly>
                                <label class="form-label"><small>Fecha de nacimiento</small></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr style="max-width: 750px;">

    <div class="card mb-3" style="max-width: 750px;">
        <div class="card-header">
            Información de Contacto
        </div>
        <div class="row g-0">

            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                        <label for="">Celular: </label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" value="{{ $user->phone }}" aria-label="Disabled input example" disabled readonly>
                            <label class="form-label"><small>Celular</small></label>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                        <label for="">Dirección: </label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" value="{{ $user->address }}" aria-label="Disabled input example" disabled readonly>
                            <label class="form-label"><small>Dirección</small></label>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 col-lg-3 text-end d-none d-lg-block">
                        <label for="">Correo: </label>
                    </div>
                    <div class="col-12 col-lg-9">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" value="{{ $user->email }}" aria-label="Disabled input example" disabled readonly>
                            <label class="form-label"><small>Correo</small></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection