@extends('layouts.cliente')

@section('title', $product->name)

@section('content')

<div class="container px-4 px-lg-5 my-5">
    <div class="row gx-4 gx-lg-5 align-items-center">
        @php
            $imagePath = 'productos/' . $product->id . '.jpg';
            $imageUrl = asset($imagePath);
        @endphp
        <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="{{ file_exists(public_path($imagePath)) ? $imageUrl : asset('productos/default.png') }}"/></div>
        <div class="col-md-6">
            <h1 class="display-5 fw-bolder">{{ $product->name }}</h1>
            <div class="fs-5 mb-5">
                <span>S/ {{ $product->price }}</span>
            </div>
            <p class="lead">{{ $product->description }}</p>
            <p class="mb-5">Stock actual: {{ $product->stock }}</p>
            <form action="{{ route('cliente.carrito.add') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="d-flex">
                    <input class="form-control text-center me-3" name="cantidad" id="inputQuantity" type="number" value="{{ old('cantidad', 1) }}" style="max-width: 5rem" min="1"/>
                    <button class="btn btn-dark flex-shrink-0" type="submit">
                        <i class="bi-cart-fill me-1"></i>Añadir al carrito
                    </button>
                </div>
            </form>
            @if ($errors->has('stock_error'))
                <div class="alert alert-danger mt-5">{{ $errors->first('stock_error') }}</div>
            @endif
        </div>
    </div>
</div>

@endsection