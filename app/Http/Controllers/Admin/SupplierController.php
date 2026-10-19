<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('app.admin.proveedores.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.admin.proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruc' => 'required|string|max:20|unique:suppliers,ruc',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'business_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        Supplier::create($validated);
        return redirect()->route('admin.proveedores');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('app.admin.proveedores.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('app.admin.proveedores.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'ruc' => ['required', 'string', 'max:20', Rule::unique('suppliers', 'ruc')->ignore($supplier->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('suppliers', 'email')->ignore($supplier->id)],
            'business_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $supplier->update($validated);
        return redirect()->route('admin.proveedores');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if (Product::where('supplier_id', $supplier->id)->exists()) {
            return back()->withErrors([
                'supplier' => 'No se puede eliminar un proveedor que tiene productos.',
            ]);
        }

        $supplier->delete();
        return redirect()->route('admin.proveedores');
    }
}
