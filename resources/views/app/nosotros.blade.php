@extends('layouts.home')

@section('title', 'Tienda Virtual')

@section('content')

<div class="container py-lg-5">

    <div class="row">

        <div class="col-lg-6">
            <div class="jumbotron py-5">
                <div class="container">
                    <h1 class="display-4">Descubre Nuestra Devoción por las Mascotas</h1>
                    <p class="lead">Bienvenido a nuestra familia de amantes de mascotas. En este sitio compartimos nuestra pasión por proporcionar productos excepcionales que hacen la vida de tus amigos peludos más feliz. </p>
                    <p class="fw-light">Cada producto que ofrecemos ha sido seleccionado con mimo y atención a los detalles. Nos enorgullece brindarte no solo artículos de calidad, sino también una experiencia de compra que refleje nuestro compromiso genuino con el bienestar animal. En "Nuestro Equipo", podrás conocer a las personas apasionadas que trabajan incansablemente para asegurarse de que tus mascotas reciban solo lo mejor.</p>
                    <p class="fw-light">Acompáñanos en este viaje dedicado a consentir a tus peludos amigos. Descubre quiénes somos, cómo empezamos y, lo más importante, cómo podemos ser parte de la historia de felicidad de tu mascota.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('img/asset/nosotros.jpg') }}" alt="" width="100%">
        </div>
    </div>
</div>

@endsection