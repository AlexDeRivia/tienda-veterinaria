<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function list()
    {
        $user = auth('users')->user();
        $purchases = DB::table('purchases_view')->where('user_id', '=', $user->id)->get();
        //return $purchases;
        return view('app.cliente.compras', compact('purchases'));
    }

    public function pay(Request $request)
    {
        $client_id = auth('users')->user()->id;
        $cart_id = Cart::where('client_id', $client_id)->where('status_id', 1)->first()->id;
        DB::unprepared("CALL pay_cart($cart_id, $client_id, $request->method);");
        return redirect()->route('cliente.compras');
    }

    public function factura($id)
    {
        //$purchase = Purchase::with('cart.client', 'cart.cartDetails.product')->find($id);
        $purchase = Purchase::with(['cart.client', 'cart.cartDetails.product'])->find($id);
        //return $purchase->cart->cartDetails;
        return view('app.cliente.factura', compact('purchase'));
    }
}
