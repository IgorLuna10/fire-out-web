@extends('layouts.app')
@section('title', 'Fire Out — Redefinir Senha')

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
    <h1 class="auth-title">CRIAR NOVA SENHA</h1>
    <p class="auth-sub">Defina sua nova senha para voltar a acessar seus pedidos.</p>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="form-group">
        <label class="form-label" for="reset-email">E-mail</label>
        <input class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               id="reset-email"
               type="email" name="email" value="{{ $email ?? old('email') }}"
               autocomplete="username"
               required autofocus>
        @error('email')<div class="form-error" role="alert">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="reset-password">Nova Senha</label>
        <div style="position:relative;">
          <input class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                 id="reset-password"
                 type="password" name="password"
                 autocomplete="new-password"
                 aria-describedby="password-hint"
                 placeholder="Min. 12 caracteres" required style="padding-right:44px;">
          <button type="button" class="btn-toggle-pwd" aria-label="Mostrar nova senha" aria-pressed="false"
                  onclick="togglePasswordVisibility('reset-password', this)"
                  style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--warm);padding:6px;display:flex;align-items:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <p id="password-hint" style="font-size:12px;color:var(--warm);margin-top:6px;line-height:1.4;">
          Mínimo de 12 caracteres. Recomendamos o uso de uma frase secreta ou gerenciador de senhas.
        </p>
        @error('password')<div class="form-error" role="alert">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="reset-password-confirmation">Confirmar Nova Senha</label>
        <div style="position:relative;">
          <input class="form-input"
                 id="reset-password-confirmation"
                 type="password" name="password_confirmation"
                 autocomplete="new-password"
                 placeholder="Repita a nova senha" required style="padding-right:44px;">
          <button type="button" class="btn-toggle-pwd" aria-label="Mostrar confirmação da nova senha" aria-pressed="false"
                  onclick="togglePasswordVisibility('reset-password-confirmation', this)"
                  style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--warm);padding:6px;display:flex;align-items:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit">Salvar Nova Senha</button>
    </form>

    <div class="auth-footer">
      Lembrou sua senha? <a href="{{ route('login') }}">Voltar para o login</a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId, btn) {
  var input = document.getElementById(inputId);
  if (!input) return;
  var isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';
  btn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
  btn.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
  btn.innerHTML = isPassword
    ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
    : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
}
</script>
@endpush
