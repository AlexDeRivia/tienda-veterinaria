@php
    use App\Models\CartDetail;
    $user = auth('users')->user();
    $isClient = $user && (int) $user->role_id === 2;
    $cart = $isClient ? CartDetail::join('carts', 'cart_details.cart_id', '=', 'carts.id')
        ->where('carts.client_id', $user->id)->where('carts.status_id', 1)->count() : 0;
@endphp

<nav class="navbar navbar-expand-lg store-nav sticky-top" aria-label="Navegación principal">
    <div class="container">
        <a class="navbar-brand store-brand" href="{{ route('home') }}" aria-label="Tienda de mascotas, inicio">
            <span class="brand-symbol" aria-hidden="true">+</span><span>Tienda<br><strong>de mascotas</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNav" aria-controls="customerNav" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="customerNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalogo*') ? 'active' : '' }}" href="{{ route('catalogo') }}" @if(request()->routeIs('catalogo*')) aria-current="page" @endif>Catálogo</a></li>
                @if ($isClient)
                    <li class="nav-item"><a class="nav-link" href="{{ route('cliente.compras') }}">Mis compras</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cliente.perfil') }}">Mi cuenta</a></li>
                    <li class="nav-item"><a class="btn btn-primary cart-nav-link" href="{{ route('cliente.carrito') }}">Carrito <span class="cart-count" aria-label="{{ $cart }} productos">{{ $cart }}</span></a></li>
                    <li class="nav-item"><form action="{{ route('logout.auth') }}" method="post">@csrf<button class="nav-link nav-logout" type="submit">Salir</button></form></li>
                @elseif ($user)
                    <li class="nav-item"><a class="btn btn-primary" href="{{ route('admin.home') }}">Administración</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                    <li class="nav-item"><a class="btn btn-primary" href="{{ route('register') }}">Crear cuenta</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
