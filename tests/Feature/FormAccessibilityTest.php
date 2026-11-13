<?php

namespace Tests\Feature;

use App\Models\User;
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

        // Labels linked to IDs
        $response->assertSee('for="login-email"', false);
        $response->assertSee('id="login-email"', false);
        $response->assertSee('autocomplete="username"', false);

        $response->assertSee('for="login-password"', false);
        $response->assertSee('id="login-password"', false);
        $response->assertSee('autocomplete="current-password"', false);

        // Show/hide toggle button
        $response->assertSee('aria-label="Mostrar senha"', false);
        $response->assertSee('aria-pressed="false"', false);

        // Link to forgot password
        $response->assertSee(route('password.request'), false);
    }

    public function test_register_page_markup_accessibility(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        // Labels and autocompletes
        $response->assertSee('for="reg-name"', false);
        $response->assertSee('id="reg-name"', false);
        $response->assertSee('autocomplete="name"', false);

        $response->assertSee('for="reg-email"', false);
        $response->assertSee('id="reg-email"', false);
        $response->assertSee('autocomplete="email"', false);

        $response->assertSee('for="phone-input"', false);
        $response->assertSee('id="phone-input"', false);
        $response->assertSee('autocomplete="tel"', false);

        $response->assertSee('for="password-input"', false);
        $response->assertSee('id="password-input"', false);
        $response->assertSee('autocomplete="new-password"', false);
        $response->assertSee('aria-describedby="password-hint"', false);

        // Honeypot field exists with aria-hidden
        $response->assertSee('id="website_hp"', false);
        $response->assertSee('aria-hidden="true"', false);
    }

    public function test_profile_page_renders_aria_tabs_and_controls(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/minha-conta');
        $response->assertStatus(200);

        // Tablist and Tab items
        $response->assertSee('role="tablist"', false);
        $response->assertSee('role="tab"', false);
        $response->assertSee('id="tab-btn-info"', false);
        $response->assertSee('aria-controls="tab-info"', false);
        $response->assertSee('aria-selected="true"', false);

        // Tabpanels
        $response->assertSee('id="tab-info"', false);
        $response->assertSee('role="tabpanel"', false);
        $response->assertSee('aria-labelledby="tab-btn-info"', false);

        $response->assertSee('id="tab-password"', false);
        $response->assertSee('role="tabpanel"', false);
        $response->assertSee('aria-labelledby="tab-btn-password"', false);

        // Password change inputs in profile
        $response->assertSee('for="profile-current-password"', false);
        $response->assertSee('id="profile-current-password"', false);
        $response->assertSee('for="profile-new-password"', false);
        $response->assertSee('id="profile-new-password"', false);
        $response->assertSee('autocomplete="new-password"', false);
    }
}
