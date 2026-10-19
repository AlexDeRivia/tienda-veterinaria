@extends('layouts.cliente')

@section('title', 'Mi carrito | Tienda de mascotas')

@section('content')
<main class="container cart-page">
    <header class="inner-page-heading">
        <a href="{{ route('catalogo') }}">Seguir explorando</a>
        <h1>Tu carrito</h1>
        <p>Revisa los productos antes de registrar tu compra de demostración.</p>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    @if ($cartDetails->count() > 0)
        <div class="cart-layout">
            <section class="cart-items" aria-label="Productos del carrito">
                @foreach ($cartDetails as $detail)
                    <article class="cart-item">
                        <div class="cart-item-name">
                            @if ($detail->is_active)
                                <h2><a href="{{ route('catalogo.producto', $detail->product_id) }}">{{ $detail->name }}</a></h2>
                            @else
                                <h2>{{ $detail->name }}</h2>
                                <p class="cart-unavailable">Ya no está disponible. Retíralo para continuar.</p>
                            @endif
                            <p>Precio unitario: S/ {{ number_format((float) $detail->unit_price, 2) }}</p>
                        </div>
                        <div class="cart-item-controls">
                            <form action="{{ route('cliente.carrito.update') }}" method="post" class="quantity-form">
                                @csrf
                                @method('put')
                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                <input type="hidden" name="product_id" value="{{ $detail->product_id }}">
                                <span id="quantity-label-{{ $detail->id }}" class="visually-hidden">Cantidad de {{ $detail->name }}</span>
                                <button type="submit" name="accion" value="menos" aria-label="Quitar una unidad de {{ $detail->name }}">−</button>
                                <input type="text" name="cantidad" value="{{ $detail->quantity }}" aria-labelledby="quantity-label-{{ $detail->id }}" readonly>
                                <button type="submit" name="accion" value="mas" aria-label="Añadir una unidad de {{ $detail->name }}">+</button>
                            </form>
                            <form action="{{ route('cliente.carrito.delete', $detail->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                <button class="cart-remove" type="submit">Retirar</button>
                            </form>
                        </div>
                        <p class="cart-item-subtotal">S/ {{ number_format((float) $detail->subtotal, 2) }}</p>
                    </article>
                    @if (session('error') && session('error_for_detail') == $detail->id)
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif
                @endforeach
            </section>
            <aside class="cart-summary" aria-labelledby="cart-summary-title">
                <h2 id="cart-summary-title">Resumen</h2>
                <div class="cart-summary-total"><span>Total</span><strong>S/ {{ number_format((float) $total, 2) }}</strong></div>
                <p>Esta compra no genera cobros ni pedidos reales.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">Confirmar compra de demostración</button>
            </aside>
        </div>
    @else
        <div class="cart-empty">
            <h2>Tu carrito está vacío.</h2>
            <p>Explora el catálogo y añade el primer producto.</p>
            <a class="btn btn-primary" href="{{ route('catalogo') }}">Ver productos</a>
        </div>
    @endif
</main>

<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutTitle" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('cliente.compras.pay') }}" method="post" class="needs-validation" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="checkoutTitle">Confirmar compra de demostración</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>No se realizará ningún cobro. La compra aparecerá en tu historial.</p>
                    <label for="payment-method" class="form-label">Método de pago simulado</label>
                    <select id="payment-method" name="method" class="form-select" required>
                        <option value="">Selecciona un método</option>
                        @foreach ($methods as $method)
                            <option value="{{ $method->id }}">{{ $method->description }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Selecciona un método de pago.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar compra</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
