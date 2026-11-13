@extends('layouts.app')
@section('title', 'Fire Out — Recuperar Senha')

@push('styles')
<style>
.auth-wrap {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: calc(var(--nav-h) + 40px) 24px 60px;
  background: var(--off);
}
.auth-box {
  width: 100%;
  max-width: 440px;
  background: #fff;
  border-radius: 16px;
  padding: 40px 36px;
  box-shadow: 0 4px 24px rgba(0,0,0,.06);
  border: 1.5px solid rgba(0,0,0,.06);
}
.auth-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 32px;
  letter-spacing: 1.2px;
  color: var(--ink);
  margin-bottom: 6px;
  line-height: 1;
}
.auth-sub {
  font-size: 13px;
  color: var(--warm);
  margin-bottom: 24px;
  line-height: 1.5;
}
.form-group { margin-bottom: 18px; }
.form-label {
  display: block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .8px;
  text-transform: uppercase;
  color: var(--warm);
  margin-bottom: 7px;
}
.form-input {
  width: 100%;
  padding: 12px 14px;
  background: #fff;
  border: 1.5px solid rgba(0,0,0,.1);
  border-radius: 10px;
  font-size: 14px;
  font-family: inherit;
  color: var(--ink);
  outline: none;
  transition: border-color .2s;
}
.form-input:focus { border-color: var(--red); }
.form-input.error { border-color: #ef4444; }
.form-error { font-size: 12px; color: #ef4444; margin-top: 5px; }
.btn-submit {
  width: 100%;
  padding: 14px;
  background: var(--red);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: background .2s;
}
.btn-submit:hover { background: var(--red-d); }
.alert-success {
  padding: 12px 16px;
  background: rgba(22,163,74,.08);
  border: 1px solid rgba(22,163,74,.2);
  border-radius: 8px;
  font-size: 13px;
  color: #15803d;
  margin-bottom: 20px;
  line-height: 1.4;
}
.auth-footer {
  text-align: center;
  margin-top: 24px;
  font-size: 13px;
  color: var(--warm);
}
.auth-footer a { color: var(--red); font-weight: 600; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
  <div class="auth-box">
    <h1 class="auth-title">RECUPERAR SENHA</h1>
    <p class="auth-sub">Informe seu e-mail para receber as instruções de recuperação de conta.</p>

    @if(session('status'))
      <div class="alert-success" role="alert">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="reset-email">E-mail cadastrado</label>
        <input class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               id="reset-email"
               type="email" name="email" value="{{ old('email') }}"
               autocomplete="email"
               placeholder="seu@email.com" required autofocus>
        @error('email')<div class="form-error" role="alert">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
    </form>

    <div class="auth-footer">
      Lembrou sua senha? <a href="{{ route('login') }}">Voltar para o login</a>
    </div>
  </div>
</div>
@endsection
