<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('test@fireout.com.br|127.0.0.1');
        RateLimiter::clear('vitima@fireout.com.br|127.0.0.1');
        RateLimiter::clear('brute@fireout.com.br|192.168.1.50');
    }

    public function test_login_is_not_exempt_from_csrf(): void
    {
        $middleware = app(PreventRequestForgery::class);
        $excluded = $middleware->getExcludedPaths();

        $this->assertNotContains('login', $excluded, 'Route /login must not be exempted from CSRF.');
        $this->assertEmpty($excluded, 'No routes should be exempted from CSRF.');
    }

    public function test_login_rate_limiting_per_account_and_ip(): void
    {
        $email = 'vitima@fireout.com.br';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('SenhaCorreta123!'),
            'role' => 'admin',
        ]);

        // 5 consecutive failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $email,
                'password' => 'SenhaIncorreta',
            ])->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        // 6th attempt must be throttled with countdown message
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'SenhaIncorreta',
        ]);

        $response->assertSessionHasErrors('email');
        $error = session('errors')->first('email');
        $this->assertStringContainsString('Muitas tentativas de login. Tente novamente em', $error);
    }

    public function test_spoofed_x_forwarded_for_does_not_bypass_rate_limiting_without_trusted_proxy(): void
    {
        $email = 'brute@fireout.com.br';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('SenhaValida123!'),
            'role' => 'admin',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $this->withServerVariables([
                'REMOTE_ADDR' => '192.168.1.50',
                'HTTP_X_FORWARDED_FOR' => "10.0.0.{$i}",
            ])->post('/login', [
                'email' => $email,
                'password' => 'SenhaInvalida',
            ]);
        }

        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '192.168.1.50',
            'HTTP_X_FORWARDED_FOR' => '10.0.0.99',
        ])->post('/login', [
            'email' => $email,
            'password' => 'SenhaInvalida',
        ]);

        $response->assertSessionHasErrors('email');
        $error = session('errors')->first('email');
        $this->assertStringContainsString('Muitas tentativas de login. Tente novamente em', $error);
    }

    public function test_non_admin_cannot_login_to_admin_area(): void
    {
        $customer = User::factory()->create([
            'email' => 'cliente@fireout.com.br',
            'password' => Hash::make('SenhaSegura123!'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'cliente@fireout.com.br',
            'password' => 'SenhaSegura123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_logs_in_and_redirects_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@fireout.com.br',
            'password' => Hash::make('SenhaAdminForte123!'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@fireout.com.br',
            'password' => 'SenhaAdminForte123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }
}
