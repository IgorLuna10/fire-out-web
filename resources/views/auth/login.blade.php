@extends('layouts.app')
@section('title', 'Fire Out — Entrar')

@push('styles')
<style>
/* ── LAYOUT ── */
.login-wrap {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-top: var(--nav-h);
}

/* ── LEFT: form ── */
.login-left {
  background: var(--off);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 56px 64px;
}

.login-form-inner {
  width: 100%;
  max-width: 400px;
}

.login-form-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 36px;
  letter-spacing: 1.5px;
  color: var(--ink);
  margin-bottom: 4px;
  line-height: 1;
}
.login-form-sub {
  font-size: 13px;
  color: var(--warm);
  margin-bottom: 36px;
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
  transition: border-color .2s, box-shadow .2s;
}
.form-input::placeholder { color: rgba(0,0,0,.25); }
.form-input:focus {
  border-color: var(--red);
  box-shadow: 0 0 0 3px rgba(215,43,43,.08);
}
.form-input.error { border-color: #ef4444; }
.form-error { font-size: 12px; color: #ef4444; margin-top: 5px; }

.form-check {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--warm); margin-bottom: 24px;
}
.form-check input { width: 16px; height: 16px; accent-color: var(--red); }

.btn-login {
  width: 100%;
  padding: 14px;
  background: var(--red);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 700;
  font-family: inherit;
  letter-spacing: .5px;
  cursor: pointer;
  transition: background .2s, transform .1s, box-shadow .2s;
  box-shadow: 0 4px 20px rgba(215,43,43,.28);
}
.btn-login:hover  { background: var(--red-d); transform: translateY(-1px); box-shadow: 0 6px 28px rgba(215,43,43,.4); }
.btn-login:active { transform: translateY(0); }

.alert-error {
  padding: 12px 16px;
  background: rgba(239,68,68,.07);
  border: 1px solid rgba(239,68,68,.18);
  border-radius: 8px;
  font-size: 13px;
  color: #b91c1c;
  margin-bottom: 20px;
}

.login-footer {
  text-align: center;
  margin-top: 22px;
  font-size: 13px;
  color: var(--warm);
}
.login-footer a { color: var(--red); font-weight: 600; }
.login-footer a:hover { text-decoration: underline; }

/* ── RIGHT: brand panel ── */
.login-right {
  background: var(--ink);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 64px 56px;
  position: relative;
  overflow: hidden;
  min-height: calc(100vh - var(--nav-h));
}

.login-right-img {
  position: absolute;
  inset: 0;
  background-image: url('{{ asset("img/fire out images/foraAcao.webp") }}');
  background-size: cover;
  background-position: center 30%;
  opacity: .15;
}

#login-fog { display: none; }

.login-right-content {
  position: relative;
  z-index: 1;
}

.login-right-eyebrow {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--red-l);
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.login-right-eyebrow::before {
  content: '';
  width: 24px;
  height: 2px;
  background: var(--red-l);
}

.login-right-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 5.5vw, 76px);
  line-height: .9;
  letter-spacing: 2px;
  color: #fff;
  margin-bottom: 28px;
}
.login-right-title em { color: var(--red-l); font-style: normal; }

.login-right-stats {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 1px;
  background: rgba(255,255,255,.07);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 32px;
}
.login-stat {
  background: rgba(255,255,255,.03);
  padding: 20px 22px;
}
.login-stat-num {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 36px;
  color: var(--red-l);
  line-height: 1;
  margin-bottom: 4px;
}
.login-stat-lbl {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .5px;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
}

.login-right-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 8px;
  padding: 10px 16px;
  font-size: 12px;
  font-weight: 600;
  color: rgba(255,255,255,.45);
}
.login-right-badge svg {
  width: 14px; height: 14px;
  stroke: var(--red-l); fill: none; stroke-width: 2;
}

/* ── RESPONSIVE ── */
@media (max-width: 860px) {
  .login-wrap { grid-template-columns: 1fr; }
  .login-right { display: none; }
  .login-left  { padding: calc(var(--nav-h) + 40px) 28px 48px; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="login-wrap">

  {{-- LEFT: form --}}
  <div class="login-left">
    <div class="login-form-inner">

      <h1 class="login-form-title">ENTRAR</h1>
      <p class="login-form-sub">Acesse sua conta para acompanhar seus pedidos.</p>

      @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
          <label class="form-label">E-mail</label>
          <input class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                 type="email" name="email" value="{{ old('email') }}"
                 placeholder="seu@email.com" required autofocus>
          @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Senha</label>
          <input class="form-input" type="password" name="password"
                 placeholder="••••••••" required>
          @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-check">
          <input type="checkbox" name="remember" id="remember">
          <label for="remember">Manter conectado</label>
        </div>
        <button type="submit" class="btn-login">Entrar</button>
      </form>

      <div class="login-footer">
        Não tem conta? <a href="{{ route('register') }}">Criar conta</a>
      </div>

    </div>
  </div>

  {{-- RIGHT: brand panel --}}
  <div class="login-right">
    <div class="login-right-img"></div>
    <div class="login-right-content">
      <div class="login-right-eyebrow">Fire Out — Desde 1996</div>
      <h2 class="login-right-title">30 ANOS<br>PROTEGENDO<br><em>VIDAS</em></h2>
      <div class="login-right-stats">
        <div class="login-stat">
          <div class="login-stat-num">1996</div>
          <div class="login-stat-lbl">Fundação</div>
        </div>
        <div class="login-stat">
          <div class="login-stat-num">30+</div>
          <div class="login-stat-lbl">Anos no mercado</div>
        </div>
        <div class="login-stat">
          <div class="login-stat-num">P-90</div>
          <div class="login-stat-lbl">Certificação ABNT</div>
        </div>
      </div>
      <div class="login-right-badge">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Conformidade ABNT · Corpo de Bombeiros
      </div>
    </div>
  </div>

</div>
@endsection

