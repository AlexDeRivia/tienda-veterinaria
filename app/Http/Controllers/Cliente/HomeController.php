<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $query = $request->input('query', '');

        $products = Product::where('is_active', true)->when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('name', 'like', '%' . $query . '%');
        })->paginate(12)->onEachSide(1);

        $products->withPath('');
        return view('app.cliente.home', compact('products', 'query'));
    }

    public function perfil()
    {
        $user = auth('users')->user();
        //return $user;
        return view('app.cliente.perfil', compact('user'));
    }

    public function producto(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('app.cliente.producto', compact('product'));
    }

    public function carrito()
    {
        $client_id = auth('users')->user()->id;
        $cart_id = Cart::where('client_id', $client_id)->where('status_id', 1)->first()->id;
        $methods = PaymentMethod::all();

        $cartDetails = CartDetail::join('carts', 'cart_details.cart_id', '=', 'carts.id')
            ->join('products', 'cart_details.product_id', '=', 'products.id')
            ->where('carts.id', $cart_id)
            ->select(
                'cart_details.id',
                'products.id as product_id',
                'products.name',
                'products.is_active',
                'cart_details.quantity',
                'cart_details.unit_price',
                DB::raw('cart_details.quantity * cart_details.unit_price as subtotal')
            )
            ->get();

        $total = $cartDetails->sum('subtotal');

        return view('app.cliente.carrito', compact('cartDetails', 'total', 'methods'));
    }
}
