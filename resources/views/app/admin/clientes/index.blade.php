@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')

<nav class="navbar navbar-expand-lg bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand">Registro de Clientes</a>
        <a type="button" class="ms-auto btn btn-secondary" href="{{ route('admin.home') }}">
            <i class="fa-solid fa-reply me-2"></i> Volver
        </a>
    </div>
</nav>

<div class="container">
    <div class="card my-3">
        <div class="card-header">Lista de Clientes</div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="mitabla3">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Nacimiento</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Nacimiento</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->dni }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->names }}</td>
                        <td>{{ $client->surnames }}</td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->address }}</td>
                        <td>{{ $client->birthdate }}</td>
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