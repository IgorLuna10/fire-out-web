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

    public function test_login_and_register_are_not_exempt_from_csrf(): void
    {
        $middleware = app(PreventRequestForgery::class);
        $excluded = $middleware->getExcludedPaths();

        $this->assertNotContains('login', $excluded, 'Route /login must not be exempted from CSRF.');
        $this->assertNotContains('register', $excluded, 'Route /register must not be exempted from CSRF.');
        $this->assertContains('webhook/mercadopago', $excluded, 'Webhook should remain exempted.');
    }

    public function test_login_rate_limiting_per_account_and_ip(): void
    {
        $email = 'vitima@fireout.com.br';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('SenhaCorreta123!'),
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
        ]);

        // Attempt 5 times rotating X-Forwarded-For header from an untrusted client IP (192.168.1.50)
        for ($i = 1; $i <= 5; $i++) {
            $this->withServerVariables([
                'REMOTE_ADDR' => '192.168.1.50',
                'HTTP_X_FORWARDED_FOR' => "10.0.0.{$i}",
            ])->post('/login', [
                'email' => $email,
                'password' => 'SenhaInvalida',
            ]);
        }

        // 6th attempt with another spoofed IP must still be throttled because 192.168.1.50 is not a trusted proxy
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

    public function test_password_change_regenerates_session(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('SenhaAtual123!'),
        ]);

        $this->actingAs($user);
        $initialSessionId = session()->getId();

        $response = $this->post('/minha-conta/senha', [
            'current_password' => 'SenhaAtual123!',
            'password' => 'NovaSenhaForte123!',
            'password_confirmation' => 'NovaSenhaForte123!',
        ]);

        $response->assertSessionHas('success', 'Senha alterada com sucesso.');
        $this->assertTrue(Hash::check('NovaSenhaForte123!', $user->fresh()->password));
        $this->assertNotEquals($initialSessionId, session()->getId());
    }
}
