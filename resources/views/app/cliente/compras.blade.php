@extends('layouts.cliente')

@section('title', 'Mis compras | Tienda de mascotas')

@section('content')
<main class="container purchases-page">
    <header class="inner-page-heading">
        <a href="{{ route('catalogo') }}">Volver al catálogo</a>
        <h1>Mis compras</h1>
        <p>Consulta las compras que registraste en esta demostración.</p>
    </header>
    @if ($purchases->count() > 0)
        <div class="purchases-list">
            @foreach ($purchases as $purchase)
                <article class="purchase-row">
                    <div><span>Compra</span><h2>N.º {{ $purchase->purchase_id }}</h2></div>
                    <div><span>Fecha</span><p>{{ $purchase->created_at }}</p></div>
                    <div><span>Total</span><p class="purchase-total">S/ {{ number_format((float) $purchase->total, 2) }}</p></div>
                    <a class="btn btn-outline-primary" href="{{ route('cliente.factura', $purchase->purchase_id) }}">Ver detalle</a>
                </article>
            @endforeach
        </div>
    @else
        <div class="cart-empty">
            <h2>Aún no registraste compras.</h2>
            <p>Elige un producto del catálogo para probar el recorrido.</p>
            <a class="btn btn-primary" href="{{ route('catalogo') }}">Ver productos</a>
        </div>
    @endif
</main>
@endsection
