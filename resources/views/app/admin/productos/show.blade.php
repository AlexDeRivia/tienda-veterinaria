@extends('layouts.admin')

@section('title', 'Productos')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a class="ms-auto btn btn-secondary" href="{{ route('admin.productos') }}">Volver</a>
    </div>
</nav>

<div class="container">
    @error('product')
        <div class="alert alert-danger mt-3" role="alert">{{ $message }}</div>
    @enderror

    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            @if ($hasCartHistory)
                <div class="alert alert-info my-3" role="status">
                    Este producto forma parte de un carrito o una compra anterior. Para retirarlo del catálogo sin perder el historial, cambia su publicación a «Oculto».
                </div>
                <a class="btn btn-primary" href="{{ route('admin.productos.edit', $product) }}">Editar publicación</a>
            @else
            <form action="{{ route('admin.productos.destroy', $product) }}" method="post">
                @csrf
                @method('delete')

                <div class="card my-3">
                    <div class="card-header">Eliminar producto</div>
                    <div class="card-body">
                        <p>El siguiente producto será eliminado definitivamente:</p>
                        <p class="mb-0"><strong>{{ $product->name }}</strong></p>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.productos') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@endsection
