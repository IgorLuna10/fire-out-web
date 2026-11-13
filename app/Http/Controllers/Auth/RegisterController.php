<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        if (Auth::check()) return redirect()->route('profile');
        session(['register_form_time' => now()->timestamp]);
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Honeypot check
        if ($request->filled('website_hp')) {
            return back()->withErrors(['name' => 'Acesso inválido detectado.'])->withInput();
        }

        // Timing check (min 2 seconds)
        $formTime = session('register_form_time');
        if ($formTime && (now()->timestamp - $formTime < 2)) {
            return back()->withErrors(['name' => 'Envio muito rápido. Por favor, aguarde um instante e envie novamente.'])->withInput();
        }

        $data = $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => ['required', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->uncompromised(),
            ],
        ], [
            'phone.regex'            => 'Informe um telefone válido com DDD. Ex: (21) 98765-4321',
            'password.min'            => 'A senha deve conter no mínimo 12 caracteres.',
            'password.uncompromised' => 'A senha escolhida apareceu em vazamentos públicos de credenciais. Escolha uma senha diferente.',
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
}
