<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Services\PasswordResetEmailQuota;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FeatureGapWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        if (!str_ends_with((string) getenv('DB_DATABASE'), '_test')) {
            $this->markTestSkipped('Set DB_DATABASE to a dedicated database ending in _test.');
        }

        parent::setUp();
    }

    public function test_guest_can_browse_but_must_log_in_to_add_to_cart(): void
    {
        $product = Product::firstOrFail();

        $this->get('/catalogo')->assertOk()->assertSee($product->name);
        $this->get('/producto/'.$product->id)->assertOk()->assertSee('Ingresa para añadir al carrito');
        $this->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 1])
            ->assertRedirect(route('login'));
    }

    public function test_hidden_product_cannot_be_bought_but_old_purchase_remains_visible(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $admin = User::where('role_id', 1)->firstOrFail();
        $product = Product::firstOrFail();
        $method = PaymentMethod::firstOrFail();

        $this->actingAs($client, 'users')->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 1])
            ->assertRedirect(route('cliente.carrito'));
        $oldCart = Cart::where('client_id', $client->id)->where('status_id', 1)->firstOrFail();
        $this->post('/cliente/compras', ['method' => $method->id])->assertRedirect(route('cliente.compras'));
        $purchase = Purchase::where('cart_id', $oldCart->id)->firstOrFail();

        $this->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 1])
            ->assertRedirect(route('cliente.carrito'));
        $this->actingAs($admin, 'users')->put('/admin/productos/'.$product->id, [
            ...$this->productData($product), 'is_active' => '0',
        ])->assertRedirect(route('admin.productos'));

        $this->assertFalse($product->fresh()->is_active);
        $this->get('/catalogo')->assertOk()->assertDontSee($product->name);
        $this->get('/producto/'.$product->id)->assertNotFound();
        $this->actingAs($client, 'users')->post('/cliente/carrito', ['id' => $product->id, 'cantidad' => 1])
            ->assertNotFound();
        $this->post('/cliente/compras', ['method' => $method->id])->assertSessionHasErrors('stock');
        $this->assertSame(1, Purchase::count());
        $this->get('/cliente/factura/'.$purchase->id)->assertOk()->assertSee($product->name);
    }

    public function test_admin_image_upload_survives_without_local_files(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $supplierId = Product::firstOrFail()->supplier_id;
        $this->actingAs($admin, 'users');

        $this->post('/admin/productos', [
            'name' => 'Producto con imagen',
            'description' => 'Producto de prueba',
            'price' => 12.50,
            'stock' => 5,
            'supplier_id' => $supplierId,
            'image' => UploadedFile::fake()->image('first.jpg'),
        ])->assertRedirect(route('admin.productos'));
        $product = Product::where('name', 'Producto con imagen')->firstOrFail();
        $firstHash = $product->fresh()->image_hash;
        $this->assertNotNull($firstHash);
        $this->assertNull($product->fresh()->image_path);
        $this->assertSame('image/jpeg', DB::table('product_images')->where('product_id', $product->id)->value('mime_type'));
        $this->get('/producto/'.$product->id)->assertOk()->assertSee('/imagen?', false);
        $this->get('/producto/'.$product->id.'/imagen')->assertOk()->assertHeader('Content-Type', 'image/jpeg');

        $this->put('/admin/productos/'.$product->id, [
            ...$this->productData($product),
            'image' => UploadedFile::fake()->image('second.png'),
        ])->assertRedirect(route('admin.productos'));
        $secondHash = $product->fresh()->image_hash;
        $this->assertNotSame($firstHash, $secondHash);
        $this->assertSame('image/png', DB::table('product_images')->where('product_id', $product->id)->value('mime_type'));
        $this->get('/producto/'.$product->id.'/imagen')->assertOk()->assertHeader('Content-Type', 'image/png');

        $this->put('/admin/productos/'.$product->id, [
            ...$this->productData($product),
            'image' => UploadedFile::fake()->create('bad.txt', 1, 'text/plain'),
        ])->assertSessionHasErrors('image');
        $this->assertSame($secondHash, $product->fresh()->image_hash);
    }

    public function test_password_reset_link_changes_password_and_rejects_an_invalid_token(): void
    {
        Notification::fake();
        $client = User::where('role_id', 2)->firstOrFail();
        $client->password = 'old-password';
        $client->save();

        $this->get('/password/forgot')->assertOk();
        $this->post('/password/forgot', ['email' => 'unknown@example.test'])->assertSessionHas('status');
        Notification::assertNothingSent();
        $this->post('/password/forgot', ['email' => $client->email])->assertSessionHas('status');
        $token = null;
        Notification::assertSentTo($client, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });
        $this->assertNotNull($token);
        $this->get('/password/reset/'.$token.'?email='.urlencode($client->email))->assertOk();

        $this->post('/password/reset', [
            'token' => 'wrong-token', 'email' => $client->email,
            'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('old-password', $client->fresh()->password));

        $this->post('/password/reset', [
            'token' => $token, 'email' => $client->email,
            'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-password', $client->fresh()->password));
    }

    public function test_password_reset_uses_brevo_https_api_when_configured(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        config()->set('services.brevo.key', 'test-api-key');
        config()->set('services.brevo.sender', 'demo@example.com');
        Http::fake(['api.brevo.com/*' => Http::response(['messageId' => 'test-id'], 201)]);

        $this->post('/password/forgot', ['email' => $client->email])->assertSessionHas('status');

        Http::assertSent(function ($request) use ($client) {
            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key', 'test-api-key')
                && $request['to'][0]['email'] === $client->email
                && str_contains($request['textContent'], rtrim(config('app.url'), '/').'/password/reset/');
        });
    }

    public function test_password_reset_email_quota_persists_in_mysql(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $quota = app(PasswordResetEmailQuota::class);

        $this->assertTrue($quota->claim($client));
        $this->assertTrue($quota->claim($client));
        $this->assertTrue($quota->claim($client));
        $this->assertFalse($quota->claim($client));
        $this->assertSame(3, (int) DB::table('password_reset_email_quotas')
            ->where('user_id', $client->id)->where('day', now()->toDateString())->value('requests'));
    }

    public function test_public_responses_include_security_headers(): void
    {
        $this->get('/')->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_admin_bootstrap_creates_once_without_resetting_credentials(): void
    {
        User::where('role_id', 1)->delete();
        $oldEmail = getenv('BOOTSTRAP_ADMIN_EMAIL');
        $oldPassword = getenv('BOOTSTRAP_ADMIN_PASSWORD');
        putenv('BOOTSTRAP_ADMIN_EMAIL=owner@example.com');
        putenv('BOOTSTRAP_ADMIN_PASSWORD=UniqueTestPassword123!');

        try {
            $this->assertSame(0, Artisan::call('app:provision-admin'));
            $admin = User::where('email', 'owner@example.com')->firstOrFail();
            $hash = $admin->password;
            $this->assertSame(1, $admin->role_id);
            $this->assertTrue(Hash::check('UniqueTestPassword123!', $hash));

            putenv('BOOTSTRAP_ADMIN_PASSWORD=DifferentPassword123!');
            $this->assertSame(0, Artisan::call('app:provision-admin'));
            $this->assertSame($hash, $admin->fresh()->password);
        } finally {
            $oldEmail === false ? putenv('BOOTSTRAP_ADMIN_EMAIL') : putenv('BOOTSTRAP_ADMIN_EMAIL='.$oldEmail);
            $oldPassword === false ? putenv('BOOTSTRAP_ADMIN_PASSWORD') : putenv('BOOTSTRAP_ADMIN_PASSWORD='.$oldPassword);
        }
    }

    public function test_client_and_admin_can_edit_profiles_with_password_for_identity_changes(): void
    {
        $client = User::where('role_id', 2)->firstOrFail();
        $client->password = 'current-password';
        $client->save();
        $this->actingAs($client, 'users')->get('/cliente/perfil')->assertOk()->assertSee('Guardar cambios');

        $this->put('/cliente/perfil', [
            ...$this->profileData($client), 'names' => 'Nombre nuevo', 'phone' => '987654321',
        ])->assertSessionHas('status');
        $this->assertSame('Nombre nuevo', $client->fresh()->names);
        $this->assertSame('987654321', $client->fresh()->phone);

        $this->put('/cliente/perfil', [
            ...$this->profileData($client), 'email' => 'nuevo@example.test',
        ])->assertSessionHasErrors('current_password');
        $this->assertNotSame('nuevo@example.test', $client->fresh()->email);

        $this->put('/cliente/perfil', [
            ...$this->profileData($client), 'email' => 'nuevo@example.test',
            'current_password' => 'current-password',
        ])->assertSessionHas('status');
        $this->assertSame('nuevo@example.test', $client->fresh()->email);

        $admin = User::where('role_id', 1)->firstOrFail();
        $this->actingAs($admin, 'users')->get('/admin/perfil')->assertOk()->assertSee('Guardar cambios');
        $this->put('/admin/perfil', [
            ...$this->profileData($admin), 'address' => 'Dirección nueva',
        ])->assertSessionHas('status');
        $this->assertSame('Dirección nueva', $admin->fresh()->address);
    }

    private function productData(Product $product): array
    {
        return [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'supplier_id' => $product->supplier_id,
        ];
    }

    private function profileData(User $user): array
    {
        return [
            'dni' => $user->dni,
            'names' => $user->names,
            'surnames' => $user->surnames,
            'birthdate' => $user->birthdate,
            'phone' => $user->phone,
            'address' => $user->address,
            'email' => $user->email,
        ];
    }
}
