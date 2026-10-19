@extends('layouts.cliente')

@section('title', 'Catálogo | Tienda de mascotas')

@section('content')
<main>
    <header class="catalog-intro">
        <div class="container catalog-intro-inner">
            <div>
                <h1>Productos para cuidarlos cada día.</h1>
                <p>Explora el catálogo o busca un producto por su nombre.</p>
            </div>
            <form action="{{ route('catalogo') }}" method="GET" class="catalog-search" role="search">
                <label for="catalog-query">Buscar en el catálogo</label>
                <div class="input-group">
                    <input id="catalog-query" type="search" name="query" value="{{ $query }}" placeholder="¿Qué estás buscando?" class="form-control">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </header>

    <section class="container catalog-section" aria-label="Resultados del catálogo">
        <div class="catalog-toolbar">
            <p>{{ $products->total() }} {{ $products->total() === 1 ? 'producto' : 'productos' }}{{ $query ? ' para «' . $query . '»' : ' disponibles' }}</p>
            <span>Compra de demostración · Sin cobro real</span>
        </div>
        @if ($products->count() > 0)
            <div class="product-grid">
                @foreach ($products as $product)
                    <article class="product-tile">
                        <a class="product-tile-photo" href="{{ route('catalogo.producto', $product) }}" aria-label="Ver {{ $product->name }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        <div class="product-tile-info">
                            <h2><a href="{{ route('catalogo.producto', $product) }}">{{ $product->name }}</a></h2>
                            <p class="product-tile-price">S/ {{ number_format((float) $product->price, 2) }}</p>
                            <a class="product-tile-link" href="{{ route('catalogo.producto', $product) }}">Ver producto <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="catalog-pagination">{{ $products->appends(['query' => $query])->links('pagination::bootstrap-4') }}</div>
        @else
            <div class="catalog-empty">
                <h2>No encontramos productos con ese nombre.</h2>
                <p>Prueba con una palabra más corta o vuelve a explorar todos los productos.</p>
                <a class="btn btn-primary" href="{{ route('catalogo') }}">Ver todo el catálogo</a>
            </div>
        @endif
    </section>
</main>
@endsection
