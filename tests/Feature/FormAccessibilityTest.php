<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_layout_contains_skip_link_and_accessible_header(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Bypass block skip link (WCAG 2.2 SC 2.4.1)
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('class="skip-link"', false);
        $response->assertSee('id="main-content"', false);

        // Accessible mobile hamburger (WCAG 2.2 SC 4.1.2)
        $response->assertSee('aria-label="Menu principal"', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('aria-controls="mobile-nav"', false);
    }

    public function test_login_page_markup_accessibility(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        // Labels linked to IDs (WCAG 2.2 SC 1.3.1)
        $response->assertSee('for="login-email"', false);
        $response->assertSee('id="login-email"', false);
        $response->assertSee('autocomplete="username"', false);

        $response->assertSee('for="login-password"', false);
        $response->assertSee('id="login-password"', false);
        $response->assertSee('autocomplete="current-password"', false);

        // Show/hide toggle button (WCAG 2.2 SC 4.1.2, SC 3.3.8)
        $response->assertSee('aria-label="Mostrar senha"', false);
        $response->assertSee('aria-pressed="false"', false);
    }

    public function test_checkout_form_markup_accessibility(): void
    {
        // Place an item in cart session
        $cart = [
            ['prodId' => 'pcf-p90', 'name' => 'Porta Corta-Fogo P-90', 'qty' => 1, 'price' => null]
        ];

        $response = $this->withSession(['checkout_cart' => json_encode($cart)])->get('/checkout');
        $response->assertStatus(200);

        // Explicit labels and IDs
        $response->assertSee('for="nome"', false);
        $response->assertSee('id="nome"', false);
        $response->assertSee('for="telefone"', false);
        $response->assertSee('id="telefone"', false);
        $response->assertSee('for="email"', false);
        $response->assertSee('id="email"', false);
        $response->assertSee('for="documentNumber"', false);
        $response->assertSee('id="documentNumber"', false);

        // Document toggle group
        $response->assertSee('role="group"', false);
        $response->assertSee('aria-label="Tipo de documento"', false);
        $response->assertSee('aria-pressed="true"', false);

        // Address fields
        $response->assertSee('for="ship-cep"', false);
        $response->assertSee('id="ship-cep"', false);
        $response->assertSee('for="ship-numero"', false);
        $response->assertSee('id="ship-numero"', false);
        $response->assertSee('for="ship-rua"', false);
        $response->assertSee('id="ship-rua"', false);
    }

    public function test_catalogue_cart_drawer_accessibility(): void
    {
        $response = $this->get('/produtos');
        $response->assertStatus(200);

        // Modal cart drawer semantics (WCAG 2.2 SC 4.1.2)
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('aria-labelledby="cartDrawerTitle"', false);
        $response->assertSee('aria-label="Fechar carrinho"', false);

        // Live status region for notifications (WCAG 2.2 SC 4.1.3)
        $response->assertSee('role="status"', false);
        $response->assertSee('aria-live="polite"', false);
    }
}
