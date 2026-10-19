@extends('layouts.admin')

@section('title', 'Compras de demostración')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand">Compras de demostración</a>
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.home') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container">
    <div class="card my-3">
        <div class="card-header">Lista de compras simuladas</div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="mitabla2">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>DNI</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>DNI</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('admin.factura', $purchase->purchase_id) }}"><i class="fa-solid fa-receipt me-2"></i>Ver detalles</a></td>
                        <td>{{ $purchase->purchase_id }}</td>
                        <td>{{ $purchase->user_dni }}</td>
                        <td>{{ $purchase->user_names . ' ' . $purchase->user_surnames }}</td>
                        <td>S/ {{ $purchase->total }}</td>
                        <td>{{ $purchase->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('dist/js/datatables.js') }}"></script>
<script src="{{ asset('dist/js/mitable.js') }}"></script>

@endsection
