@extends('layouts.cliente')

@section('title', 'Mi carrito')

@section('content')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('cliente.compras.pay') }}" method="post" class="needs-validation" novalidate>
            @csrf
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Efectuar compra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <label for="" class="form-control">Método de pago</label>
                    <select name="method" class="form-select" aria-label="Default select example" required>
                        <option selected></option>
                        @foreach ($methods as $method)
                        <option value="{{ $method->id }}">{{ $method->description }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Seleccione un método de pago</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <input type="submit" value="Aceptar" class="btn btn-primary">
            </div>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">

    <div class="row">
        <div class="col-md-10">
            <ul class="list-group list-group-flush px-3">
                <li class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-4">Producto</div>
                        <div class="col-2">Cantidad</div>
                        <div class="col-2 text-end">Precio unitario</div>
                        <div class="col-2 text-end">Subtotal</div>
                    </div>
                </li>
            </ul>
            <hr>
            @if ($cartDetails->count() > 0)
                @foreach ($cartDetails as $detail)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <p class="mb-0">
                                            <a href="{{ route('cliente.producto.show', $detail->product_id) }}" class="link-underline-light link-dark">{{ $detail->name }}</a>
                                        </p>
                                    </div>
                                    <div class="col-2">
                                        <form action="{{ route('cliente.carrito.update') }}" method="post">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="id" value="{{ $detail->id }}">
                                            <input type="hidden" name="product_id" value="{{ $detail->product_id }}">
                                            <div class="input-group">
                                                <input type="text" class="form-control border-dark" name="cantidad" value="{{ $detail->quantity }}" readonly>
                                                <button class="btn btn-outline-dark" type="submit" name="accion" value="menos"><i class="fa-solid fa-minus"></i></button>
                                                <button class="btn btn-outline-dark" type="submit" name="accion" value="mas"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-2 text-end">
                                        <p class="mb-0">S/ {{ $detail->unit_price }}</p>
                                    </div>
                                    <div class="col-2 text-end">
                                        <p class="mb-0">S/ {{ $detail->subtotal }}</p>
                                    </div>
                                    <div class="col-2 text-end">
                                        <form action="{{ route('cliente.carrito.delete', $detail->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="id" value="{{ $detail->id }}">
                                            <button class="btn btn-danger" type="submit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @if(session('error') && session('error_for_detail') == $detail->id)
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @endforeach
                <div class="row">
                    <div class="col-4 offset-8">
                        <hr>
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-4">Monto total:</div>
                                <div class="col-8 text-end">S/ {{$total}}</div>
                            </div>
                        </li>
                    </div>
                    <div class="col-4 offset-8 text-end mt-5">
                        <a type="button" class="btn btn-primary me-2" href="{{ route('cliente.home') }}">
                            Seguir comprando
                        </a>
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Efectuar compra
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-cart-shopping fa-3x text-muted mb-3"></i>
                    <p class="lead mb-0">Tu carrito está vacío, ingresa productos para empezar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
