@extends('layouts.app')
@section('title', 'Fire Out — Criar Conta')

@push('styles')
<style>
/* ── LAYOUT ── */
.reg-wrap {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-top: var(--nav-h);
  position: relative;
}

/* ── LEFT PANEL ── */
.reg-left {
  background: var(--ink);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 64px 56px;
  position: relative;
  overflow: hidden;
  min-height: calc(100vh - var(--nav-h));
}

/* background image */
.reg-left-img {
  position: absolute;
  inset: 0;
  background-image: url('{{ asset("img/fire out images/porta corta fogo.webp") }}');
  background-size: cover;
  background-position: center;
  opacity: .18;
}

/* fog follows mouse only on left panel */
#reg-fog {
  position: fixed;
  pointer-events: none;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  background: radial-gradient(circle, rgba(215,43,43,.22) 0%, rgba(215,43,43,.08) 40%, transparent 70%);
  transition: left .15s ease, top .15s ease;
  z-index: 0;
}

.reg-left-content {
  position: relative;
  z-index: 1;
}

.reg-left-eyebrow {
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
.reg-left-eyebrow::before {
  content: '';
  width: 24px;
  height: 2px;
  background: var(--red-l);
}

.reg-left-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 5.5vw, 76px);
  line-height: .9;
  letter-spacing: 2px;
  color: #fff;
  margin-bottom: 28px;
}
.reg-left-title em {
  color: var(--red-l);
  font-style: normal;
}

.reg-left-perks {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 40px;
}
.reg-left-perks li {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 14px;
  color: rgba(255,255,255,.55);
  line-height: 1.4;
}
.reg-perk-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--red);
  flex-shrink: 0;
}

.reg-left-badge {
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
  letter-spacing: .3px;
}
.reg-left-badge svg {
  width: 14px;
  height: 14px;
  stroke: var(--red-l);
  fill: none;
  stroke-width: 2;
}

/* ── RIGHT PANEL ── */
.reg-right {
  background: var(--off);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 56px 64px;
}

.reg-form-inner {
  width: 100%;
  max-width: 420px;
}

.reg-form-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 36px;
  letter-spacing: 1.5px;
  color: var(--ink);
  margin-bottom: 4px;
  line-height: 1;
}
.reg-form-sub {
  font-size: 13px;
  color: var(--warm);
  margin-bottom: 36px;
  line-height: 1.5;
}

/* ── FORM FIELDS ── */
.form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { margin-bottom: 16px; }

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
.form-input.error  { border-color: #ef4444; }
.form-error { font-size: 12px; color: #ef4444; margin-top: 5px; }

/* ── SUBMIT ── */
.btn-reg {
  width: 100%;
  padding: 14px;
  margin-top: 8px;
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
.btn-reg:hover  { background: var(--red-d); transform: translateY(-1px); box-shadow: 0 6px 28px rgba(215,43,43,.4); }
.btn-reg:active { transform: translateY(0); }

/* ── FOOTER LINK ── */
.reg-footer {
  text-align: center;
  margin-top: 22px;
  font-size: 13px;
  color: var(--warm);
}
.reg-footer a { color: var(--red); font-weight: 600; }
.reg-footer a:hover { text-decoration: underline; }

/* ── PASSWORD RULES ── */
.pwd-rules {
  list-style: none;
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.pwd-rules li {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: var(--warm);
  transition: color .2s;
}
.pwd-rules li.ok { color: #16a34a; }
.pwd-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: rgba(0,0,0,.15);
  flex-shrink: 0;
  transition: background .2s;
}
.pwd-rules li.ok .pwd-dot { background: #16a34a; }

/* ── RESPONSIVE ── */
@media (max-width: 860px) {
  .reg-wrap { grid-template-columns: 1fr; }
  .reg-left  { display: none; }
  .reg-right { padding: calc(var(--nav-h) + 40px) 28px 48px; align-items: flex-start; }
}
@media (max-width: 480px) {
  .form-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div id="reg-fog"></div>

<div class="reg-wrap">

  {{-- LEFT: brand panel --}}
  <div class="reg-left">
    <div class="reg-left-img"></div>
    <div class="reg-left-content">
      <div class="reg-left-eyebrow">Fire Out — Desde 1996</div>
      <h2 class="reg-left-title">PROTEÇÃO<br>SEM<br><em>CONCESSÕES</em></h2>
      <ul class="reg-left-perks">
        <li><span class="reg-perk-dot"></span>Acompanhe seus pedidos em tempo real</li>
        <li><span class="reg-perk-dot"></span>Salve seus endereços de entrega</li>
        <li><span class="reg-perk-dot"></span>Acesso rápido ao histórico de compras</li>
        <li><span class="reg-perk-dot"></span>Orçamentos e propostas personalizadas</li>
      </ul>
      <div class="reg-left-badge">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Conformidade ABNT · Corpo de Bombeiros
      </div>
    </div>
  </div>

  {{-- RIGHT: form --}}
  <div class="reg-right">
    <div class="reg-form-inner">

      <h1 class="reg-form-title">CRIAR CONTA</h1>
      <p class="reg-form-sub">Preencha os dados abaixo para acessar sua área de cliente.</p>

      <form method="POST" action="{{ route('register.post') }}" id="register-form">
        @csrf
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        <div class="form-group">
          <label class="form-label">Nome completo</label>
          <input class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                 type="text" name="name" value="{{ old('name') }}"
                 placeholder="Seu nome completo" required>
          @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">E-mail</label>
            <input class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                   type="email" name="email" value="{{ old('email') }}"
                   placeholder="seu@email.com" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Telefone / WhatsApp</label>
            <input class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                   type="tel" name="phone" id="phone-input" value="{{ old('phone') }}"
                   placeholder="(21) 98765-4321" maxlength="15" required>
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Senha</label>
          <input class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                 type="password" name="password" id="password-input"
                 placeholder="Min. 8 caracteres" required>
          @error('password')<div class="form-error">{{ $message }}</div>@enderror
          <ul class="pwd-rules" id="pwd-rules">
            <li id="r-len">  <span class="pwd-dot"></span> Mínimo 8 caracteres</li>
            <li id="r-upper"><span class="pwd-dot"></span> Letra maiúscula</li>
            <li id="r-lower"><span class="pwd-dot"></span> Letra minúscula</li>
            <li id="r-num">  <span class="pwd-dot"></span> Número</li>
            <li id="r-sym">  <span class="pwd-dot"></span> Símbolo (@$!%*?&#)</li>
          </ul>
        </div>

        <div class="form-group">
          <label class="form-label">Confirmar senha</label>
          <input class="form-input" type="password"
                 name="password_confirmation"
                 placeholder="Repita a senha" required>
        </div>

        <button type="submit" class="btn-reg" id="register-btn">Criar Conta</button>
      </form>

      <div class="reg-footer">
        Já tem conta? <a href="{{ route('login') }}">Entrar</a>
      </div>

    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
/* ── PHONE MASK ── */
document.getElementById('phone-input').addEventListener('input', function () {
  var v = this.value.replace(/\D/g, '').substring(0, 11);
  if (v.length <= 2)       this.value = v.length ? '(' + v : '';
  else if (v.length <= 6)  this.value = '(' + v.substring(0,2) + ') ' + v.substring(2);
  else if (v.length <= 10) this.value = '(' + v.substring(0,2) + ') ' + v.substring(2,6) + '-' + v.substring(6);
  else                     this.value = '(' + v.substring(0,2) + ') ' + v.substring(2,7) + '-' + v.substring(7);
});

/* ── PASSWORD RULES ── */
document.getElementById('password-input').addEventListener('input', function () {
  var v = this.value;
  function check(id, ok) {
    var el = document.getElementById(id);
    el.classList.toggle('ok', ok);
  }
  check('r-len',   v.length >= 8);
  check('r-upper', /[A-Z]/.test(v));
  check('r-lower', /[a-z]/.test(v));
  check('r-num',   /[0-9]/.test(v));
  check('r-sym',   /[@$!%*?&#]/.test(v));
});

(function () {
  var fog = document.getElementById('reg-fog');
  fog.style.left = '25vw';
  fog.style.top  = '50vh';
  document.addEventListener('mousemove', function (e) {
    fog.style.left = e.clientX + 'px';
    fog.style.top  = e.clientY + 'px';
  });
})();

@if(config('services.recaptcha.site_key'))
var _rcSiteKey = '{{ config('services.recaptcha.site_key') }}';
document.getElementById('register-form').addEventListener('submit', function (e) {
  e.preventDefault();
  var form = this;
  grecaptcha.ready(function () {
    grecaptcha.execute(_rcSiteKey, { action: 'register' }).then(function (token) {
      document.getElementById('recaptcha_token').value = token;
      form.submit();
    });
  });
});
@endif
</script>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endif
@endpush
