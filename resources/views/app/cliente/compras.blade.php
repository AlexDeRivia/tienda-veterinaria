@extends('layouts.cliente')

@section('title', 'Compras efectuadas')

@section('content')

<div class="container py-5">

    @if ($purchases->count() > 0)
        
        <div class="row">
            <div class="col-md-10">
                <ul class="list-group list-group-flush px-3">
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-2">ID</div>
                            <div class="col-4">Total</div>
                            <div class="col-4">Fecha de orden</div>
                        </div>
                    </li>
                </ul>
                <hr>
                @foreach ($purchases as $purchase)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-2">
                                        <p class="mb-0">{{ $purchase->purchase_id }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-0">S/ {{ $purchase->total }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-0">{{ $purchase->created_at }}</p>
                                    </div>
                                    <div class="col-2 text-end">
                                        <a href="{{ route('cliente.factura', $purchase->purchase_id) }}" class="btn btn-dark">Ver detalles</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
            <p class="lead mb-0">Aún no has efectuado ninguna compra.</p>
        </div>
    @endif

</div>

@endsection
