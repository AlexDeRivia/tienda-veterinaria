@extends('layouts.cliente')

@section('title', 'Mi perfil')

@section('content')
    @include('partials.profile.form', ['updateRoute' => route('cliente.perfil.update')])
@endsection
