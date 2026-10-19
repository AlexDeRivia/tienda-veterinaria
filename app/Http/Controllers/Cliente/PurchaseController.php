<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    private const MAX_DEMO_PURCHASES = 500;

    private const MAX_DEMO_PURCHASES_PER_DAY = 100;

    private const MAX_CLIENT_PURCHASES_PER_DAY = 10;

    public function list()
    {
        $user = auth('users')->user();
        $purchases = DB::table('purchases_view')->where('user_id', '=', $user->id)->get();
        //return $purchases;
        return view('app.cliente.compras', compact('purchases'));
    }

    public function pay(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|integer|exists:payment_methods,id',
        ]);
        $client_id = auth('users')->user()->id;

        DB::transaction(function () use ($client_id, $validated) {
            Role::whereKey(2)->lockForUpdate()->firstOrFail();
            if (Purchase::count() >= self::MAX_DEMO_PURCHASES
                || Purchase::where('created_at', '>=', now()->subDay())->count() >= self::MAX_DEMO_PURCHASES_PER_DAY
                || Purchase::where('created_at', '>=', now()->subDay())
                    ->whereHas('cart', fn ($query) => $query->where('client_id', $client_id))->count()
                    >= self::MAX_CLIENT_PURCHASES_PER_DAY) {
                throw ValidationException::withMessages([
                    'carrito' => 'Se alcanzó el límite de compras simuladas de esta demostración.',
                ]);
            }

            $cart = Cart::where('client_id', $client_id)->where('status_id', 1)
                ->lockForUpdate()->firstOrFail();
            $details = CartDetail::where('cart_id', $cart->id)->get();

            if ($details->isEmpty()) {
                throw ValidationException::withMessages([
                    'carrito' => 'Añade al menos un producto antes de efectuar la compra.',
                ]);
            }

            $products = Product::whereIn('id', $details->pluck('product_id'))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            foreach ($details as $detail) {
                if ($detail->quantity < 1 || !isset($products[$detail->product_id])
                    || !$products[$detail->product_id]->is_active
                    || $products[$detail->product_id]->stock < $detail->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => 'Un producto ya no está disponible o su stock cambió. Revisa el carrito.',
                    ]);
                }
            }

            DB::statement('CALL pay_cart(?, ?, ?)', [$cart->id, $client_id, $validated['method']]);
        });

        return redirect()->route('cliente.compras');
    }

    public function factura($id)
    {
        $purchase = Purchase::whereHas('cart', function ($query) {
            $query->where('client_id', auth('users')->id());
        })->with(['cart.client', 'cart.cartDetails.product'])->findOrFail($id);
        return view('app.cliente.factura', compact('purchase'));
    }
}
