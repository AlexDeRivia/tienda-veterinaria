@extends('layouts.admin')

@section('title', 'Panel | Tienda de mascotas')

@section('content')
<div class="container admin-dashboard">
    <header class="admin-dashboard-header">
        <div><h1>Control de la tienda</h1><p>Un vistazo a los datos de esta demostración.</p></div>
        <a class="btn btn-primary" href="{{ route('admin.productos.create') }}">Añadir producto</a>
    </header>
    <div class="admin-summary">
        <a href="{{ route('admin.productos') }}" class="admin-summary-primary"><span>Productos en catálogo</span><strong>{{ $products }}</strong><span>Gestionar productos <span aria-hidden="true">↗</span></span></a>
        <div class="admin-summary-list">
            <a href="{{ route('admin.clientes') }}"><span>Clientes</span><strong>{{ $clients }}</strong><span aria-hidden="true">↗</span></a>
            <a href="{{ route('admin.ventas') }}"><span>Compras de demostración</span><strong>{{ $purchases }}</strong><span aria-hidden="true">↗</span></a>
            <a href="{{ route('admin.proveedores') }}"><span>Proveedores</span><strong>{{ $suppliers }}</strong><span aria-hidden="true">↗</span></a>
        </div>
    </div>
    <section class="admin-shortcuts" aria-labelledby="admin-shortcuts-title">
        <h2 id="admin-shortcuts-title">Accesos rápidos</h2>
        <div><a href="{{ route('admin.productos') }}">Revisar productos</a><a href="{{ route('admin.proveedores') }}">Gestionar proveedores</a><a href="{{ route('catalogo') }}">Ver la tienda</a></div>
    </section>
</div>
@endsection
