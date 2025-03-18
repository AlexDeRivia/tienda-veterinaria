@extends('layouts.home')

@section('title', 'Tienda Virtual')

@section('content')

<div class="container py-lg-5">

    <div class="row">

        <div class="col-lg-6">
            <div class="jumbotron py-5">
                <div class="container">
                    <h1 class="display-4">Bienvenido a nuestra tienda virtual para mascotas</h1>
                    <p class="lead">Encuentra todo lo que necesitas para consentir a tu mascota. ¡Explora nuestra amplia gama de productos!</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('img/asset/productos.png') }}" alt="" width="100%">
        </div>
    </div>
</div>

@endsection