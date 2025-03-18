@extends('layouts.admin')

@section('title', 'Ventas')

@section('content')

@php
    $client = $purchase->cart->client;
    $cart_details = $purchase->cart->cartDetails;
    $total = 0;
    foreach ($cart_details as $cd) {
        $total += $cd->quantity * $cd->unit_price;
    }
@endphp

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand">Detalle de factura</a>
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.ventas') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container py-3">

    <div class="card" style="max-width: 850px;">
        <div class="card-header">
            <p class="mb-0">Factura N° {{ $purchase->id }}</p>
        </div>
        <div class="card-header bg-white">
            <table class="table">
                <tr>
                    <th>DNI:</th>
                    <td>{{ $client->dni }}</td>
                    <th>Fecha:</th>
                    <td>{{ $purchase->created_at }}</td>
                </tr>
                <tr>
                    <th>Cliente:</th>
                    <td>{{ $client->names . ' ' . $client->surnames }}</td>
                    <th>Total:</th>
                    <td>S/ {{ $total }}</td>
                </tr>
            </table>
        </div>
        <div class="card-header">Detalles</div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio Unit.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart_details as $cd)
                    <tr>
                        <td>{{ $cd->product->name }}</td>
                        <td class="text-end">{{ $cd->quantity }}</td>
                        <td class="text-end">S/ {{ $cd->unit_price }}</td>
                        <td class="text-end">S/ {{ $cd->quantity * $cd->unit_price }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table">
                <tr class="text-end">
                    <td>Total: S/ {{ $total }}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.ventas') }}" class="btn btn-dark">Cerrar</a>
        </div>
    </div>
</div>

@endsection