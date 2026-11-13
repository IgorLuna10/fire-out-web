<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class RegistrationAndResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_minimum_12_characters_nist(): void
    {
        // Set form time to simulate 3 seconds elapsed
        $response = $this->withSession(['register_form_time' => now()->subSeconds(3)->timestamp])
            ->post('/register', [
                'name'                  => 'Cliente Fire Out',
                'email'                 => 'cliente@fireout.com.br',
                'phone'                 => '(21) 98765-4321',
                'password'              => 'curta123',
                'password_confirmation' => 'curta123',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'cliente@fireout.com.br']);
    }

    public function test_registration_succeeds_with_passphrase_without_composition_rules(): void
    {
        // 14 characters, no symbols required by NIST
        $response = $this->withSession(['register_form_time' => now()->subSeconds(3)->timestamp])
            ->post('/register', [
                'name'                  => 'Cliente Fire Out',
                'email'                 => 'passphrase@fireout.com.br',
                'phone'                 => '(21) 98765-4321',
                'password'              => 'minhafrasesecreta',
                'password_confirmation' => 'minhafrasesecreta',
            ]);

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('users', ['email' => 'passphrase@fireout.com.br']);
    }

    public function test_registration_honeypot_traps_automated_bots(): void
    {
        $response = $this->withSession(['register_form_time' => now()->subSeconds(3)->timestamp])
            ->post('/register', [
                'name'                  => 'Spam Bot',
                'email'                 => 'bot@fireout.com.br',
                'phone'                 => '(21) 98765-4321',
                'password'              => 'SenhaValidaSegura123',
                'password_confirmation' => 'SenhaValidaSegura123',
                'website_hp'            => 'http://spam-link.com',
            ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('users', ['email' => 'bot@fireout.com.br']);
    }

    public function test_registration_rejects_sub_second_submission(): void
    {
        // Submitted immediately (0 seconds elapsed)
        $response = $this->withSession(['register_form_time' => now()->timestamp])
            ->post('/register', [
                'name'                  => 'Fast Submitter',
                'email'                 => 'fast@fireout.com.br',
                'phone'                 => '(21) 98765-4321',
                'password'              => 'SenhaValidaSegura123',
                'password_confirmation' => 'SenhaValidaSegura123',
            ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('users', ['email' => 'fast@fireout.com.br']);
    }

    public function test_forgot_password_sends_notification_for_valid_user(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'cadastrado@fireout.com.br',
        ]);

        $response = $this->post('/esqueci-minha-senha', [
            'email' => 'cadastrado@fireout.com.br',
        ]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_password_returns_same_generic_response_for_non_existent_email(): void
    {
        Notification::fake();

        $response = $this->post('/esqueci-minha-senha', [
            'email' => 'inexistente@fireout.com.br',
        ]);

        $response->assertSessionHas('status', 'Se o e-mail informado estiver cadastrado em nossa base, você receberá um link para redefinir sua senha.');
        Notification::assertNothingSent();
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'resetuser@fireout.com.br',
            'password' => Hash::make('SenhaAntiga12345'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post('/redefinir-senha', [
            'token'                 => $token,
            'email'                 => 'resetuser@fireout.com.br',
            'password'              => 'NovaSenhaSuperSegura123',
            'password_confirmation' => 'NovaSenhaSuperSegura123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NovaSenhaSuperSegura123', $user->fresh()->password));
    }
}
