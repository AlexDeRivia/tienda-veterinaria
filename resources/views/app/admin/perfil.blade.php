@extends('layouts.admin')

@section('title', 'Mi perfil')

@section('content')
    @include('partials.profile.form', ['updateRoute' => route('admin.perfil.update')])
@endsection
