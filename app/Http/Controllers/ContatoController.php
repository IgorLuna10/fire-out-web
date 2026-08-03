<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoMail;

class ContatoController extends Controller
{
    public function index()
    {
        return view('pages.contato');
    }

    public function send(Request $request)
    {
        // Laravel validation (replaces manual PHP validation)
        $validated = $request->validate([
            'nome'      => 'required|min:2|max:50|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'sobrenome' => 'nullable|min:2|max:50|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'email'     => 'required|email|max:254',
            'mensagem'  => 'required|min:10|max:5000',
        ], [
            'nome.required'     => 'Nome é obrigatório.',
            'nome.min'          => 'Nome deve ter pelo menos 2 caracteres.',
            'nome.regex'        => 'Nome deve conter apenas letras e espaços.',
            'email.required'    => 'Email é obrigatório.',
            'email.email'       => 'Por favor, insira um email válido.',
            'mensagem.required' => 'Mensagem é obrigatória.',
            'mensagem.min'      => 'Mensagem deve ter pelo menos 10 caracteres.',
        ]);

        try {
            Mail::to(['contato@fireout.com.br', 'fireout@fireout.com.br'])
                ->send(new ContatoMail($validated));

            return redirect()->route('obrigado');

        } catch (\Exception $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', 'Erro ao enviar mensagem. Tente novamente ou entre em contato pelo WhatsApp.');
        }
    }

    public function obrigado()
    {
        return view('pages.obrigado');
    }
}
