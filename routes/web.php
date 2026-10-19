<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cliente\CartController as ClienteCartController;
use App\Http\Controllers\Cliente\HomeController as ClienteHomeController;
use App\Http\Controllers\Cliente\PurchaseController as ClientePurchaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImageController;
use App\Http\Middleware\Rol\Admin;
use App\Http\Middleware\Rol\Cliente;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'app.home')->name('home');
Route::view('/nosotros', 'app.nosotros')->name('nosotros');
Route::get('/catalogo', [ClienteHomeController::class, 'home'])->name('catalogo');
Route::get('/producto/{product}', [ClienteHomeController::class, 'producto'])->name('catalogo.producto');
Route::get('/producto/{product}/imagen', [ProductImageController::class, 'show'])->name('catalogo.imagen');

Route::controller(PasswordResetController::class)->group(function () {
    Route::get('/password/forgot', 'requestForm')->name('password.request');
    Route::post('/password/forgot', 'sendLink')->middleware('throttle:demo-password-email')->name('password.email');
    Route::get('/password/reset/{token}', 'resetForm')->name('password.reset');
    Route::post('/password/reset', 'reset')->middleware('throttle:5,1')->name('password.update');
});

Route::controller(AuthController::class)->group(function () {

    Route::post('/login', 'login')->middleware('throttle:demo-login')->name('login.auth');
    Route::post('/logout', 'logout')->name('logout.auth');
});

Route::controller(HomeController::class)->group(function () {

    Route::get('/login', 'login')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->middleware('throttle:demo-register')->name('register.store');
});

Route::middleware(Admin::class)->group(function () {

    Route::controller(AdminHomeController::class)->group(function () {
        Route::get('/admin', 'home')->name('admin.home');
        Route::get('/admin/perfil', 'perfil')->name('admin.perfil');
    });
    Route::put('/admin/perfil', [ProfileController::class, 'update'])->name('admin.perfil.update');

    Route::controller(AdminClientController::class)->group(function () {
        Route::get('/admin/clientes', 'index')->name('admin.clientes');
    });

    Route::controller(AdminPurchaseController::class)->group(function () {
        Route::get('/admin/ventas', 'index')->name('admin.ventas');
        Route::get('/admin/ventas/factura/{id}', 'factura')->name('admin.factura');
    });

    Route::controller(AdminProductController::class)->group(function () {
        Route::get('/admin/productos', 'index')->name('admin.productos');
        Route::get('/admin/productos/create', 'create')->name('admin.productos.create');
        Route::post('/admin/productos', 'store')->name('admin.productos.store');
        Route::get('/admin/productos/{product}', 'show')->name('admin.productos.show');
        Route::get('/admin/productos/{product}/edit', 'edit')->name('admin.productos.edit');
        Route::put('/admin/productos/{product}', 'update')->name('admin.productos.update');
        Route::delete('/admin/productos/{product}', 'destroy')->name('admin.productos.destroy');
    });

    Route::controller(AdminSupplierController::class)->group(function () {
        Route::get('/admin/proveedores', 'index')->name('admin.proveedores');
        Route::get('/admin/proveedores/create', 'create')->name('admin.proveedores.create');
        Route::post('/admin/proveedores', 'store')->name('admin.proveedores.store');
        Route::get('/admin/proveedores/{supplier}', 'show')->name('admin.proveedores.show');
        Route::get('/admin/proveedores/{supplier}/edit', 'edit')->name('admin.proveedores.edit');
        Route::put('/admin/proveedores/{supplier}', 'update')->name('admin.proveedores.update');
        Route::delete('/admin/proveedores/{supplier}', 'destroy')->name('admin.proveedores.destroy');
    });
});

Route::middleware(Cliente::class)->group(function () {

    Route::controller(ClienteHomeController::class)->group(function () {
        Route::get('/cliente', 'home')->name('cliente.home');
        Route::get('/cliente/perfil', 'perfil')->name('cliente.perfil');
        Route::get('/cliente/producto/{product}', 'producto')->name('cliente.producto.show');
        Route::get('/cliente/carrito', 'carrito')->name('cliente.carrito');
    });
    Route::put('/cliente/perfil', [ProfileController::class, 'update'])->name('cliente.perfil.update');

    Route::controller(ClienteCartController::class)->group(function () {
        Route::delete('/cliente/carrito/{id}', 'destroy')->name('cliente.carrito.delete');
        Route::put('/cliente/carrito', 'update')->name('cliente.carrito.update');
        Route::post('/cliente/carrito', 'add')->name('cliente.carrito.add');
    });

    Route::controller(ClientePurchaseController::class)->group(function () {
        Route::get('/cliente/compras', 'list')->name('cliente.compras');
        Route::post('/cliente/compras', 'pay')->middleware('throttle:demo-purchase')->name('cliente.compras.pay');
        Route::get('/cliente/factura/{id}', 'factura')->name('cliente.factura');
    });
});
