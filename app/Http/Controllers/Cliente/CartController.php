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
        $validated = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['id']);
        $cantidad = $validated['cantidad'];
        $stock = $product->stock;

        if ($cantidad > $stock) {
            return back()
                ->withErrors(['stock_error' => 'El stock es insuficiente'])
                ->withInput($request->all());
        }

        $client_id = auth('users')->user()->id;
        DB::transaction(function () use ($client_id, $product, $cantidad) {
            $cart_id = Cart::where('client_id', $client_id)->where('status_id', 1)
                ->lockForUpdate()->firstOrFail()->id;
            DB::statement('CALL add_cart(?, ?, ?)', [$cart_id, $product->id, $cantidad]);
        });
        return redirect()->route('cliente.carrito');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'accion' => 'required|in:mas,menos',
        ]);
        return DB::transaction(function () use ($validated) {
            $cart_id = Cart::where('client_id', auth('users')->id())
                ->where('status_id', 1)->lockForUpdate()->firstOrFail()->id;
            $detail = CartDetail::where('cart_id', $cart_id)
                ->lockForUpdate()->findOrFail($validated['id']);
            $cantidad = $detail->quantity;

            switch ($validated['accion']) {
                case 'mas':
                    if (!$detail->product->is_active) {
                        return redirect()->route('cliente.carrito')->with('error', 'Este producto ya no está disponible. Retíralo del carrito.')->with('error_for_detail', $detail->id);
                    }
                    $stock = $detail->product->stock;
                    if ($stock > $cantidad) {
                        $cantidad++;
                    } else {
                        $error = "El stock actual es de $stock, lo cual es insuficiente para cubrir la solicitud.";
                        return redirect()->route('cliente.carrito')->with('error', $error)->with('error_for_detail', $detail->id);
                    }
                    break;
                case 'menos':
                    if ($cantidad > 1) {
                        $cantidad--;
                    }
                    break;
            }
            $detail->quantity = $cantidad;
            $detail->save();
            return redirect()->route('cliente.carrito');
        });
    }

    public function destroy($detail_id)
    {
        DB::transaction(function () use ($detail_id) {
            $cart_id = Cart::where('client_id', auth('users')->id())
                ->where('status_id', 1)->lockForUpdate()->firstOrFail()->id;
            $detail = CartDetail::where('cart_id', $cart_id)->findOrFail($detail_id);
            $detail->delete();
        });
        return redirect()->route('cliente.carrito');
    }
}
