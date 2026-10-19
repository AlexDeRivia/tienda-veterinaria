@extends('layouts.cliente')

@section('title', $product->name . ' | Tienda de mascotas')

@section('content')
<main class="container product-page">
    <nav aria-label="Ruta de navegación" class="product-breadcrumb"><a href="{{ route('catalogo') }}">Catálogo</a><span aria-hidden="true">/</span><span aria-current="page">{{ $product->name }}</span></nav>
    <div class="product-layout">
        <div class="product-photo"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div>
        <div class="product-details">
            <h1>{{ $product->name }}</h1>
            <p class="product-price">S/ {{ number_format((float) $product->price, 2) }}</p>
            <div class="product-description"><h2>Sobre este producto</h2><p>{{ $product->description }}</p></div>
            <p class="product-stock">{{ $product->stock > 0 ? $product->stock . ' unidades disponibles' : 'Agotado por ahora' }}</p>
            @if (auth('users')->check() && (int) auth('users')->user()->role_id === 2)
                <form action="{{ route('cliente.carrito.add') }}" method="post" class="product-purchase">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <label for="inputQuantity">Cantidad</label>
                    <div class="product-purchase-controls">
                        <input class="form-control" name="cantidad" id="inputQuantity" type="number" value="{{ old('cantidad', 1) }}" min="1" max="{{ $product->stock }}" required @if($product->stock < 1) disabled @endif>
                        <button class="btn btn-primary" type="submit" @if($product->stock < 1) disabled @endif>Añadir al carrito</button>
                    </div>
                </form>
            @else
                <a class="btn btn-primary" href="{{ auth('users')->check() ? route('admin.home') : route('login') }}">{{ auth('users')->check() ? 'Volver al panel' : 'Ingresa para añadir al carrito' }}</a>
            @endif
            @if ($errors->has('stock_error') || $errors->has('cantidad') || $errors->has('id'))
                <div class="alert alert-danger mt-4" role="alert">{{ $errors->first() }}</div>
            @endif
            <p class="product-demo-note">Esta tienda es una demostración. La compra no genera un cobro real.</p>
        </div>
    </div>
</main>
@endsection
