<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StoreWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        // These tests use real MySQL procedures. Never run them against the app database.
        if (!str_ends_with((string) getenv('DB_DATABASE'), '_test')) {
            $this->markTestSkipped('Set DB_DATABASE to a dedicated database ending in _test.');
        }

        parent::setUp();
    }

    public function test_customer_can_register_shop_and_view_their_invoice(): void
    {
        $product = Product::firstOrFail();
        $initialStock = $product->stock;
        $method = PaymentMethod::firstOrFail();

        $this->post('/register', [
            'correo' => 'demo@example.test',
            'contrasena' => 'example-password',
            'nombres' => 'Cliente',
        ])->assertRedirect(route('cliente.home'));

        $client = User::where('email', 'demo@example.test')->firstOrFail();
        $this->assertNull($client->dni);
        $this->assertNull($client->address);
        $this->assertNull($client->phone);
        $this->assertNull($client->birthdate);
        $cart = Cart::where('client_id', $client->id)->where('status_id', 1)->firstOrFail();
        $this->get('/cliente')->assertOk()->assertSee($product->name);

        $this->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 2])
            ->assertRedirect(route('cliente.carrito'));
        $detail = CartDetail::where('cart_id', $cart->id)->firstOrFail();
        $this->get('/cliente/carrito')->assertOk()->assertSee($product->name);

        $this->put('/cliente/carrito', [
            'id' => $detail->id,
            'product_id' => $product->id,
            'cantidad' => 2,
            'accion' => 'mas',
        ])->assertRedirect(route('cliente.carrito'));
        $this->assertSame(3, $detail->fresh()->quantity);

        $this->post('/cliente/compras', ['method' => $method->id])
            ->assertRedirect(route('cliente.compras'));
        $purchase = Purchase::where('cart_id', $cart->id)->firstOrFail();
        $this->assertSame(2, $cart->fresh()->status_id);
        $this->assertSame($initialStock - 3, $product->fresh()->stock);
        $this->assertSame(1, Cart::where('client_id', $client->id)->where('status_id', 1)->count());
        $this->get('/cliente/factura/'.$purchase->id)->assertOk()->assertSee($product->name);
    }

    public function test_registration_stops_at_the_lifetime_demo_account_cap(): void
    {
        $existing = User::where('role_id', 2)->count();
        for ($i = $existing; $i < 50; $i++) {
            $user = User::forceCreate([
                'email' => 'quota'.$i.'@example.test',
                'password' => 'example-password',
                'names' => 'Demo',
                'role_id' => 2,
            ]);
            $user->created_at = now()->subDays(2);
            $user->save();
        }

        $this->post('/register', [
            'correo' => 'extra@example.test',
            'contrasena' => 'example-password',
            'nombres' => 'Extra',
        ])->assertSessionHasErrors('correo');
        $this->assertSame(50, User::where('role_id', 2)->count());
    }

    public function test_registration_stops_after_ten_clients_in_one_day(): void
    {
        $existingToday = User::where('role_id', 2)->where('created_at', '>=', now()->subDay())->count();
        for ($i = $existingToday; $i < 10; $i++) {
            User::forceCreate([
                'email' => 'daily'.$i.'@example.test',
                'password' => 'example-password',
                'names' => 'Demo',
                'role_id' => 2,
            ]);
        }

        $this->post('/register', [
            'correo' => 'extra-daily@example.test',
            'contrasena' => 'example-password',
            'nombres' => 'Extra',
        ])->assertSessionHasErrors('correo');
        $this->assertSame(10, User::where('role_id', 2)->where('created_at', '>=', now()->subDay())->count());
    }

    public function test_customer_can_log_in_and_out_and_cannot_open_admin_pages(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $client->password = 'example-password';
        $client->save();

        $this->post('/login', [
            'correo' => $client->email,
            'contrasena' => 'example-password',
        ])->assertRedirect(route('cliente.home'));
        $this->assertAuthenticatedAs($client, 'users');
        $this->get('/admin')->assertRedirect(route('cliente.home'));

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest('users');
        $this->get('/cliente')->assertRedirect(route('login'));
    }

    public function test_an_unrecognized_role_cannot_open_protected_pages(): void
    {
        $roleId = DB::table('roles')->insertGetId([
            'description' => 'Rol sin acceso',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::where('role_id', 2)->firstOrFail();
        $user->role_id = $roleId;
        $user->save();

        $this->actingAs($user, 'users')->get('/admin')->assertForbidden();
        $this->get('/cliente')->assertForbidden();
    }

    public function test_customer_cannot_change_another_cart_or_view_another_invoice(): void
    {
        $clients = User::where('role_id', 2)->orderBy('id')->take(2)->get();
        [$owner, $other] = [$clients[0], $clients[1]];
        $product = Product::firstOrFail();

        $this->actingAs($owner, 'users')->post('/cliente/carrito', [
            'id' => $product->id,
            'cantidad' => 2,
        ])->assertRedirect(route('cliente.carrito'));
        $cart = Cart::where('client_id', $owner->id)->where('status_id', 1)->firstOrFail();
        $detail = CartDetail::where('cart_id', $cart->id)->firstOrFail();

        $this->actingAs($other, 'users')->put('/cliente/carrito', [
            'id' => $detail->id,
            'product_id' => $product->id,
            'cantidad' => 2,
            'accion' => 'mas',
        ])->assertNotFound();
        $this->assertSame(2, $detail->fresh()->quantity);
        $this->delete('/cliente/carrito/'.$detail->id)->assertNotFound();
        $this->assertNotNull($detail->fresh());

        $this->actingAs($owner, 'users')->post('/cliente/compras', [
            'method' => PaymentMethod::firstOrFail()->id,
        ])->assertRedirect(route('cliente.compras'));
        $purchase = Purchase::where('cart_id', $cart->id)->firstOrFail();
        $this->actingAs($other, 'users')->get('/cliente/factura/'.$purchase->id)->assertNotFound();
    }

    public function test_invalid_cart_input_and_empty_checkout_are_rejected(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $product = Product::firstOrFail();
        $this->actingAs($client, 'users');

        $this->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 0])
            ->assertSessionHasErrors('cantidad');
        $this->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => -2])
            ->assertSessionHasErrors('cantidad');
        $this->post('/cliente/compras', ['method' => PaymentMethod::firstOrFail()->id])
            ->assertSessionHasErrors('carrito');

        $this->assertSame(0, CartDetail::count());
        $this->assertSame(0, Purchase::count());
    }

    public function test_invalid_payment_method_does_not_complete_the_cart(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $product = Product::firstOrFail();
        $this->actingAs($client, 'users')->post('/cliente/carrito', [
            'id' => $product->id,
            'cantidad' => 1,
        ])->assertRedirect(route('cliente.carrito'));

        $this->post('/cliente/compras', ['method' => 999999])
            ->assertSessionHasErrors('method');
        $this->assertSame(0, Purchase::count());
        $this->assertSame(1, Cart::where('client_id', $client->id)->where('status_id', 1)->count());
    }

    public function test_registration_rejects_missing_name_and_weak_password(): void
    {
        $this->post('/register', [
            'correo' => 'invalid@example.test',
            'contrasena' => '123',
        ])->assertSessionHasErrors(['contrasena', 'nombres']);

        $this->assertSame(0, User::where('email', 'invalid@example.test')->count());
    }

    public function test_checkout_rechecks_stock_without_creating_a_purchase(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $product = Product::firstOrFail();
        $this->actingAs($client, 'users')->post('/cliente/carrito', [
            'id' => $product->id,
            'cantidad' => 2,
        ])->assertRedirect(route('cliente.carrito'));

        $product->update(['stock' => 1]);
        $this->post('/cliente/compras', ['method' => PaymentMethod::firstOrFail()->id])
            ->assertSessionHasErrors('stock');

        $this->assertSame(0, Purchase::count());
        $this->assertSame(1, $product->fresh()->stock);
        $this->assertSame(1, Cart::where('client_id', $client->id)->where('status_id', 1)->count());
    }

    public function test_admin_can_create_and_update_supplier_and_product(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $this->actingAs($admin, 'users');

        $this->post('/admin/proveedores', [
            'ruc' => '12345678901',
            'email' => 'proveedor@example.test',
            'business_name' => 'Proveedor de prueba',
            'country' => 'Perú',
            'city' => 'Lima',
            'address' => 'Dirección de prueba',
        ])->assertRedirect(route('admin.proveedores'));
        $supplier = \App\Models\Supplier::where('ruc', '12345678901')->firstOrFail();

        $this->post('/admin/productos', [
            'name' => 'Producto de prueba',
            'description' => 'Descripción de prueba',
            'price' => '12.50',
            'stock' => 8,
            'supplier_id' => $supplier->id,
        ])->assertRedirect(route('admin.productos'));
        $product = Product::where('name', 'Producto de prueba')->firstOrFail();

        $this->put('/admin/productos/'.$product->id, [
            'name' => 'Producto actualizado',
            'description' => 'Descripción de prueba',
            'price' => '15.00',
            'stock' => 7,
            'supplier_id' => $supplier->id,
        ])->assertRedirect(route('admin.productos'));
        $this->assertSame('Producto actualizado', $product->fresh()->name);
        $this->get('/admin/productos')->assertOk()->assertSee('Producto actualizado');
    }

    public function test_admin_forms_reject_missing_required_fields(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $this->actingAs($admin, 'users');

        $this->post('/admin/proveedores', ['ruc' => '12345678901'])
            ->assertSessionHasErrors(['email', 'business_name', 'country', 'city', 'address']);
        $this->post('/admin/productos', ['price' => 2, 'stock' => 1])
            ->assertSessionHasErrors(['name', 'description', 'supplier_id']);
    }

    public function test_admin_cannot_delete_records_used_by_a_purchase(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $product = Product::firstOrFail();
        $supplier = \App\Models\Supplier::findOrFail($product->supplier_id);
        $this->actingAs($client, 'users')->post('/cliente/carrito', [
            'id' => $product->id,
            'cantidad' => 1,
        ])->assertRedirect(route('cliente.carrito'));
        $this->post('/cliente/compras', ['method' => PaymentMethod::firstOrFail()->id])
            ->assertRedirect(route('cliente.compras'));

        $admin = User::where('role_id', 1)->firstOrFail();
        $this->actingAs($admin, 'users')->delete('/admin/productos/'.$product->id)
            ->assertRedirect()->assertSessionHasErrors('product');
        $this->delete('/admin/proveedores/'.$supplier->id)
            ->assertRedirect()->assertSessionHasErrors('supplier');
        $this->assertNotNull($product->fresh());
        $this->assertNotNull($supplier->fresh());
    }

    public function test_admin_can_delete_unused_product_and_supplier(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $supplier = \App\Models\Supplier::create([
            'ruc' => '12345678901',
            'email' => 'unused@example.test',
            'business_name' => 'Proveedor sin ventas',
            'country' => 'Perú',
            'city' => 'Lima',
            'address' => 'Dirección de prueba',
        ]);
        $product = Product::create([
            'name' => 'Producto sin ventas',
            'description' => 'Descripción de prueba',
            'price' => 4.50,
            'stock' => 5,
            'supplier_id' => $supplier->id,
        ]);
        $this->actingAs($admin, 'users');

        $this->get('/admin/productos/'.$product->id)
            ->assertOk()->assertSee('Eliminar producto')->assertSee($product->name);
        $this->delete('/admin/productos/'.$product->id)
            ->assertRedirect(route('admin.productos'));
        $this->get('/admin/proveedores/'.$supplier->id)->assertOk();
        $this->delete('/admin/proveedores/'.$supplier->id)
            ->assertRedirect(route('admin.proveedores'));

        $this->assertNull($product->fresh());
        $this->assertNull($supplier->fresh());
    }
}
