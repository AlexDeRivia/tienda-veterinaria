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

            <form action="{{ route('admin.productos.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>

                @csrf

                <div class="card my-3">
                    <div class="card-header">Registrar nuevo producto</div>
                    @if ($errors->any())
                    <div class="card-header">
                        <div class="alert alert-danger mb-0" role="alert">{{ $errors->first() }}</div>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="name" class="form-label">Producto</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <input value="{{ old('name') }}" type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="description" class="form-label">Descripción</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <textarea name="description" id="description" rows="3" class="form-control" required>{{ old('description') }}</textarea>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="price" class="form-label">Precio</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('price') }}" type="text" class="form-control" id="price" name="price" required>
                                <div class="invalid-feedback">Este campo requiere un número válido</div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <label for="stock" class="form-label">Stock</label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <input value="{{ old('stock') }}" type="number" class="form-control" id="stock" name="stock" min="0" required>
                                <div class="invalid-feedback">Este campo requiere un número válido</div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <label for="supplier_id" class="form-label">Proveedor</label>
                            </div>
                            <div class="col-lg-8 mb-3">
                                <select class="form-select" aria-label="Default select example" id="supplier_id" name="supplier_id" required>
                                    <option selected></option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->business_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Este campo es obligatorio</div>
                            </div>
                            <div class="col-lg-4"><label for="is_active" class="form-label">Publicación</label></div>
                            <div class="col-lg-8 mb-3">
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1" @selected(old('is_active', '1') == '1')>Visible en el catálogo</option>
                                    <option value="0" @selected(old('is_active') == '0')>Oculto del catálogo</option>
                                </select>
                            </div>
                            <div class="col-lg-4"><label for="image" class="form-label">Imagen</label></div>
                            <div class="col-lg-8 mb-3">
                                <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">JPG, PNG o WebP, hasta 512 KB. Si no eliges una imagen, se mostrará la predeterminada.</small>
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
