<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Fixed global keys cannot be evaded by rotating or spoofing client IPs.
        RateLimiter::for('demo-register', fn () => Limit::perMinute(10)->by('register:global'));
        RateLimiter::for('demo-password-email', fn () => Limit::perMinute(15)->by('password-email:global'));

        RateLimiter::for('demo-login', function (Request $request) {
            $limits = [Limit::perMinute(40)->by('login:global')];
            $email = $request->input('correo');
            if (is_string($email) && strlen($email) <= 255) {
                $userId = User::where('email', strtolower(trim($email)))->value('id');
                if ($userId) {
                    $limits[] = Limit::perHour(20)->by('login:user:'.$userId);
                }
            }

            return $limits;
        });

        RateLimiter::for('demo-purchase', fn () => [
            Limit::perMinute(5)->by('purchase:minute:'.auth('users')->id()),
            Limit::perDay(20)->by('purchase:day:'.auth('users')->id()),
        ]);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Render probes this often; keep it outside the web session middleware.
            Route::get('/health', function () {
                DB::select('SELECT 1');
                return response('ok', 200)->header('Content-Type', 'text/plain');
            });

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
