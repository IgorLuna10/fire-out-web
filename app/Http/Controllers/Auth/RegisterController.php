<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function show()
    {
        if (Auth::check()) return redirect()->route('profile');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validateCaptcha($request);

        $data = $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => ['required', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ], [
            'phone.regex'    => 'Informe um telefone válido com DDD. Ex: (21) 98765-4321',
            'password.regex' => 'A senha deve conter letras maiúsculas, minúsculas, números e um símbolo (@$!%*?&#).',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
        ]);

        Auth::login($user);

        try {
            Mail::send(
                'emails.admin-new-registration',
                ['user' => $user],
                function ($message) {
                    $message
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->to(config('mail.from.address'), 'Fire Out Admin')
                        ->subject('Novo cliente cadastrado — Fire Out');
                }
            );
        } catch (\Exception $e) {
            Log::warning('Admin registration alert failed for user ' . $user->id . ': ' . $e->getMessage());
        }

        return redirect()->route('profile');
    }

    private function validateCaptcha(Request $request): void
    {
        $siteSecret = config('services.recaptcha.secret');

        if (! $siteSecret) {
            return;
        }

        $token = $request->input('recaptcha_token');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $siteSecret,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (! ($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
            abort(422, 'Verificação de segurança falhou. Tente novamente.');
        }
    }
}
