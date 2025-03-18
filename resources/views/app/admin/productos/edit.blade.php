@extends('layouts.admin')

@section('title', 'Productos')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.productos') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container">

    <div class="row">

        <div class="col-lg-6 offset-lg-3">

            <form action="{{ route('admin.productos.update', $product) }}" method="post" class="needs-validation" novalidate>

                @csrf
                @method('put')

                <div class="card my-3">
                    <div class="card-header">Editar producto</div>
                    @error('price')
                    <div class="card-header">
                        <div class="alert alert-danger mb-0" role="alert">{{ $message }}</div>
                    </div>
                    @enderror
                    @error('stock')
                    <div class="card-header">
                        <div class="alert alert-danger mb-0" role="alert">{{ $message }}</div>
                    </div>
                    @enderror
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="name" class="form-label">Producto</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('name', $product->name) }}" type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="description" class="form-label">Descripción</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <textarea name="description" id="description" rows="3" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="price" class="form-label">Precio</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('price', $product->price) }}" type="text" class="form-control" id="price" name="price" required>
                                <div class="invalid-feedback">Este campo requiere un número válido</div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <label for="stock" class="form-label">Stock</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('stock', $product->stock) }}" type="number" class="form-control" id="stock" name="stock" min="0" required>
                                <div class="invalid-feedback">Este campo requiere un número válido</div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <label for="city" class="form-label">Proveedor</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <select class="form-select @error('supplier_id') is-invalid @enderror" aria-label="Default select example" id="supplier_id" name="supplier_id" required>
                                    <option value="" {{ old('supplier_id', $product->supplier_id) == '' ? 'selected' : '' }}></option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.productos') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="ms-auto btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection