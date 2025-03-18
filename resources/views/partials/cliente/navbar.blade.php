@php

    use App\Models\User;
    use App\Models\Cart;
    use App\Models\CartDetail;

    $user = auth('users')->user();

    $cart = CartDetail::join('carts', 'cart_details.cart_id', '=', 'carts.id')
        ->where('carts.client_id', $user->id)
        ->where('carts.status_id', 1)
        ->count();
@endphp

<nav class="navbar navbar-expand-lg bg-dark sticky-top shadow-sm">

    <div class="container">

        <a class="navbar-brand" href="{{ route('cliente.home') }}">
            <img src="{{ asset('img/logo/white.png') }}" alt="" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent" data-bs-theme="light">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle link-light" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $user->email }} <i class="fa fa-user ms-1"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('cliente.home') }}">Inicio</a></li>
                        <li><a class="dropdown-item" href="{{ route('cliente.perfil') }}">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('cliente.compras') }}">Compras</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <form action="{{ route('logout.auth') }}" method="post">
                                @csrf
                                <input type="submit" class="dropdown-item" value="Cerrar Sesión">
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light me-3 ms-0 mx-lg-3 mb-2 mb-lg-0" href="{{ route('cliente.carrito') }}">
                        <i class="fa fa-cart-shopping me-1"></i>
                        Carrito
                        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $cart }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
