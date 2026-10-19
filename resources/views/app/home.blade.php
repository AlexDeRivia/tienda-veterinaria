@extends('layouts.home')

@section('title', 'Tienda de mascotas | Cuidado para cada día')

@section('content')
<main>
    <section class="home-hero" aria-labelledby="hero-title">
        <div class="home-hero-copy">
            <div class="home-hero-inner">
                <h1 id="hero-title">Para los días de juego, de paseo y de estar juntos.</h1>
                <p>Explora productos para acompañar el cuidado diario de tu perro o gato. Elige con calma; aquí puedes probar todo el recorrido de compra.</p>
                <a class="btn btn-sun btn-lg" href="{{ route('catalogo') }}">Explorar productos</a>
            </div>
            <p class="home-hero-note">Tienda de demostración · Sin cobros ni pedidos reales</p>
        </div>
        <div class="home-hero-photo">
            <img src="{{ asset('img/asset/nosotros.jpg') }}" alt="Un perro y un gato juntos" fetchpriority="high">
        </div>
    </section>

    <section class="home-steps container" aria-labelledby="steps-title">
        <div class="home-steps-heading">
            <h2 id="steps-title">Así funciona la tienda</h2>
            <p>Un recorrido completo para conocer el proyecto.</p>
        </div>
        <div class="steps-list">
            <div><span class="step-number">01</span><h3>Explora</h3><p>Consulta los productos y sus detalles sin iniciar sesión.</p></div>
            <div><span class="step-number">02</span><h3>Elige</h3><p>Crea una cuenta de prueba y agrega productos al carrito.</p></div>
            <div><span class="step-number">03</span><h3>Prueba la compra</h3><p>Confirma una compra simulada y revisa su detalle en tu historial.</p></div>
        </div>
    </section>

    <section class="home-invitation">
        <div class="container invitation-inner">
            <div><h2>Encuentra algo para tu compañero.</h2><p>Alimento, accesorios y productos de cuidado en un solo catálogo.</p></div>
            <a class="btn btn-primary btn-lg" href="{{ route('catalogo') }}">Ver catálogo</a>
        </div>
    </section>
</main>
@endsection
