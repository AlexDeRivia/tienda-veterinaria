@extends('layouts.home')

@section('title', 'El proyecto | Tienda de mascotas')

@section('content')
<main class="about-page container">
    <div class="about-copy">
        <h1>Una tienda pensada para explorar y probar.</h1>
        <p class="about-lead">Este proyecto muestra un recorrido de comercio electrónico para productos de mascotas, desde la búsqueda hasta el historial de compras.</p>
        <p>Puedes ver el catálogo sin cuenta. Si te registras, podrás añadir productos al carrito, confirmar una compra de demostración y consultar su detalle. También hay un panel para administrar productos y proveedores.</p>
        <p>Los pagos y pedidos son simulados: no se realizan cobros ni envíos reales.</p>
        <a class="btn btn-primary" href="{{ route('catalogo') }}">Explorar catálogo</a>
    </div>
    <div class="about-photo"><img src="{{ asset('img/asset/baner.png') }}" alt="Accesorios y juguetes para mascotas sobre una mesa" loading="lazy"></div>
</main>
@endsection
