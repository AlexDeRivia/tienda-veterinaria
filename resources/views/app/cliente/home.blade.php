@extends('layouts.cliente')

@section('title', 'Tienda Virtual')

@section('content')

<img src="{{ asset('img/asset/baner.png') }}" alt="" width="100%">

<nav class="navbar bg-dark">

    <div class="container">
        <form action="{{ route('cliente.home') }}" method="GET" class="ms-auto">
            <div class="input-group">
                <input type="text" name="query" value="{{ $query }}" placeholder="Buscar por nombre" class="form-control">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</nav>

<div class="container py-5">

    <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

        @if ($products->count() > 0)
            @foreach ($products as $product)
            <div class="col mb-5">
                <div class="card h-100">
                    @php
                        $imagePath = 'productos/' . $product->id . '.jpg';
                        $imageUrl = asset($imagePath);
                    @endphp
                    <img class="card-img-top" src="{{ file_exists(public_path($imagePath)) ? $imageUrl : asset('productos/default.png') }}" alt="{{ $product->name }}">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h5 class="fw-bolder">{{ $product->name }}</h5>
                            S/ {{ $product->price }}
                        </div>
                    </div>
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center">
                            <a class="btn btn-outline-dark mt-auto" href="{{ route('cliente.producto.show', $product) }}">Ver más opciones</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                <p class="lead mb-0">Lo sentimos, no se encontraron resultados.</p>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->appends(['query' => $query])->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection