<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $clients = User::where('role_id', '2')->count();
        $products = Product::count();
        $purchases = Purchase::count();
        $suppliers = Supplier::count();
        return view('app.admin.home', compact('clients', 'products', 'purchases', 'suppliers'));
    }

    public function perfil()
    {
        $user = auth('users')->user();
        return view('app.admin.perfil', compact('user'));
    }
}
