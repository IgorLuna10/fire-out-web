<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecondarySurfacesTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_user_role_is_not_mass_assignable(): void
    {
        $user = new User();
        $this->assertNotContains('role', $user->getFillable());
    }

    public function test_contato_route_is_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contato', [
                'nome'     => 'Teste',
                'email'    => 'teste@example.com',
                'mensagem' => 'Mensagem de teste válida com mais de dez caracteres.',
            ]);
        }

        // 6th request should hit the 5,1 throttle
        $response = $this->post('/contato', [
            'nome'     => 'Teste',
            'email'    => 'teste@example.com',
            'mensagem' => 'Mensagem de teste válida com mais de dez caracteres.',
        ]);

        $response->assertStatus(429);
    }

    public function test_checkout_page_accessibility_markup(): void
    {
        $response = $this->withSession([
            'checkout_cart' => json_encode([
                ['prodId' => 'mangueira-t1-15', 'name' => 'Mangueira', 'price' => 280, 'qty' => 1]
            ])
        ])->get('/checkout');

        $response->assertStatus(200);

        // Labels linked to IDs
        $response->assertSee('for="ship-cep"', false);
        $response->assertSee('id="ship-cep"', false);
        $response->assertSee('autocomplete="postal-code"', false);

        $response->assertSee('for="ship-rua"', false);
        $response->assertSee('id="ship-rua"', false);
        $response->assertSee('autocomplete="address-line1"', false);

        // Doc toggle has ARIA pressed
        $response->assertSee('aria-pressed="true"', false);
        $response->assertSee('role="group"', false);
    }

    public function test_produtos_cart_drawer_accessibility_markup(): void
    {
        $response = $this->get('/produtos');
        $response->assertStatus(200);

        // Modal dialog ARIA attributes
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('aria-labelledby="cartDrawerTitle"', false);
        $response->assertSee('aria-label="Fechar carrinho"', false);

        // Live region toast
        $response->assertSee('role="status"', false);
        $response->assertSee('aria-live="polite"', false);
    }
}
