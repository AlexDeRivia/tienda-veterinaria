@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.proveedores') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container">

    <div class="row">

        <div class="col-lg-6 offset-lg-3">

            <form action="{{ route('admin.proveedores.update', $supplier) }}" method="post" class="needs-validation" novalidate>

                @csrf
                @method('put')

                <div class="card my-3">
                    <div class="card-header">Editar proveedor</div>
                    @error('ruc')
                    <div class="card-header">
                        <div class="alert alert-danger mb-0" role="alert">{{ $message }}</div>
                    </div>
                    @enderror
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ruc" class="form-label">RUC</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('ruc', $supplier->ruc) }}" type="text" class="form-control" id="ruc" name="ruc" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="business_name" class="form-label">Razón social</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('business_name', $supplier->business_name) }}" type="text" class="form-control" id="business_name" name="business_name" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="email" class="form-label">Correo de contacto</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('email', $supplier->email) }}" type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Este campo requiere un correo electrónico válido</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="country" class="form-label">País</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('country', $supplier->country) }}" type="text" class="form-control" id="country" name="country" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="city" class="form-label">Ciudad</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('city', $supplier->city) }}" type="text" class="form-control" id="city" name="city" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="address" class="form-label">Dirección</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('address', $supplier->address) }}" type="text" class="form-control" id="address" name="address" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.proveedores') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="ms-auto btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection