@php
    $email = auth('users')->user()->email;
@endphp

<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('dist/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/brand.css') }}">
</head>

<body class="sb-nav-fixed admin-shell">

@component('partials.admin.navbar')
    @slot('email', $email)
@endcomponent

<div id="layoutSidenav">

@component('partials.admin.sidebar')
    @slot('email', $email)
@endcomponent

<div id="layoutSidenav_content">

<main>

@yield('content')

</main>

@include('partials.admin.footer')

</div>

</div>

</body>

<script src="{{ asset('dist/fonts/all.js') }}"></script>
<script src="{{ asset('dist/bootstrap/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('dist/js/scripts.js') }}"></script>
<script src="{{ asset('dist/js/validation.js') }}"></script>

</html>
