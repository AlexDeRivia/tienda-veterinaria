<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product_id = $request->id;
        $cantidad = $request->cantidad;

        $stock = Product::find($product_id)->stock;

        if ($cantidad > $stock) {
            return back()
                ->withErrors(['stock_error' => 'El stock es insuficiente'])
                ->withInput($request->all());
        }

        $client_id = auth('users')->user()->id;
        $cart_id = Cart::where('client_id', $client_id)->where('status_id', 1)->first()->id;
        DB::unprepared("CALL add_cart($cart_id, $product_id, $cantidad)");
        return redirect()->route('cliente.carrito');
    }

    public function update(Request $request)
    {

        $detail_id = $request->id;
        $accion = $request->accion;
        $cantidad = $request->cantidad;

        switch ($accion) {
            case 'mas':
                $stock = Product::find($request->product_id)->stock;
                if ($stock > $cantidad) {
                    $cantidad++;
                } else {
                    $cantidad = $stock;
                    $error = "El stock actual es de $stock, lo cual es insuficiente para cubrir la solicitud.";
                    return redirect()->route('cliente.carrito')->with('error', $error)->with('error_for_detail', $detail_id);
                }
                break;
            case 'menos':
                if ($cantidad != 1) {
                    $cantidad--;
                }
                break;
            default:
                break;
        }
        CartDetail::where('id', $detail_id)->update(['quantity' => $cantidad]);
        return redirect()->route('cliente.carrito');
    }

    public function destroy($detail_id)
    {
        $detail = CartDetail::find($detail_id);
        $detail->delete();
        return redirect()->route('cliente.carrito');
    }
}
