@extends('layouts.app')

@section('title', 'Fire Out — Contato')
@section('description', 'Entre em contato com a Fire Out para solicitar uma proposta de sistema contra incêndio.')

@push('styles')
<style>
/* ── hero strip ── */
.contact-hero {
  margin-top:var(--nav-h); background:var(--ink);
  padding:80px 48px 90px; position:relative; overflow:hidden;
}
.contact-hero::before {
  content:'CONTATO'; position:absolute; right:-10px; top:50%; transform:translateY(-50%);
  font-family:'Bebas Neue',sans-serif; font-size:200px; letter-spacing:-4px;
  color:rgba(255,255,255,.025); pointer-events:none; white-space:nowrap;
}
.contact-hero-inner { max-width:1200px; margin:0 auto; }
.hero-pill {
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--red-l); margin-bottom:20px;
}
.hero-pill::before { content:''; width:24px; height:2px; background:var(--red-l); }
.contact-hero h1 {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(52px,7vw,88px); line-height:.95; letter-spacing:2px;
  color:#fff; margin-bottom:20px;
}
.contact-hero p { font-size:17px; color:rgba(255,255,255,.52); max-width:480px; line-height:1.72; }

/* ── body ── */
.contact-body { max-width:1200px; margin:0 auto; padding:80px 48px 120px; }
.contact-grid {
  display:grid; grid-template-columns:1fr 1.6fr; gap:64px; align-items:start;
}

/* ── left column: info ── */
.contact-info-block { display:flex; flex-direction:column; gap:28px; }

.contact-wa {
  display:flex; align-items:center; gap:14px;
  background:#25D366; color:#fff;
  padding:20px 24px; border-radius:var(--r-lg);
  text-decoration:none;
  transition:background .2s, transform .2s;
}
.contact-wa:hover { background:#1cb955; transform:translateY(-2px); }
.contact-wa-icon {
  width:44px; height:44px; background:rgba(255,255,255,.15); border-radius:50%;
  display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.contact-wa-icon svg { width:22px; height:22px; fill:#fff; }
.contact-wa-label { font-size:12px; font-weight:600; opacity:.8; text-transform:uppercase; letter-spacing:1px; }
.contact-wa-num   { font-size:18px; font-weight:700; margin-top:2px; }

.contact-info-card {
  background:#fff; border:1px solid rgba(0,0,0,.07); border-radius:var(--r-xl);
  padding:32px 28px; display:flex; flex-direction:column; gap:20px;
}
.contact-row {
  display:flex; align-items:flex-start; gap:14px;
  font-size:15px; color:var(--ink); line-height:1.55;
}
.contact-row-icon {
  width:38px; height:38px; background:rgba(215,43,43,.07); border-radius:var(--r-sm);
  display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;
}
.contact-row-icon svg { width:17px; height:17px; stroke:var(--red); fill:none; stroke-width:2; }
.contact-row-label { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); margin-bottom:3px; }
.contact-row a { color:var(--ink); transition:color .2s; }
.contact-row a:hover { color:var(--red); }

.contact-hours {
  background:rgba(215,43,43,.05); border-radius:var(--r-md);
  padding:16px 20px;
}
.contact-hours p { font-size:13px; color:var(--warm); line-height:1.6; }
.contact-hours strong { color:var(--ink); font-weight:600; }

/* ── right column: form ── */
.contact-form-card {
  background:#fff; border:1px solid rgba(0,0,0,.07);
  border-radius:var(--r-xl); padding:48px 44px;
}
.contact-form-title {
  font-family:'Bebas Neue',sans-serif; font-size:32px; letter-spacing:1px;
  color:var(--ink); margin-bottom:6px;
}
.contact-form-sub { font-size:14px; color:var(--warm); margin-bottom:36px; line-height:1.6; }

.form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-group { display:flex; flex-direction:column; gap:7px; margin-bottom:20px; }
.form-group:last-of-type { margin-bottom:0; }

.form-label {
  font-size:12px; font-weight:700; letter-spacing:.8px; text-transform:uppercase;
  color:var(--warm);
}
.form-input {
  padding:14px 16px; border:1.5px solid rgba(0,0,0,.1); border-radius:var(--r-sm);
  font-size:15px; font-family:inherit; color:var(--ink); background:#fff;
  transition:border-color .2s, box-shadow .2s;
  outline:none;
}
.form-input:focus {
  border-color:var(--red); box-shadow:0 0 0 3px rgba(215,43,43,.1);
}
.form-input.is-invalid { border-color:#e53e3e; }
.form-input.is-invalid:focus { box-shadow:0 0 0 3px rgba(229,62,62,.12); }
textarea.form-input { resize:vertical; min-height:140px; }

.form-error {
  font-size:12px; color:#e53e3e; margin-top:4px; display:none;
}
.form-group.has-error .form-error { display:block; }

.form-alert {
  padding:14px 18px; border-radius:var(--r-sm); margin-bottom:24px;
  font-size:14px; line-height:1.55;
}
.form-alert-error { background:#fff5f5; border:1px solid #fed7d7; color:#c53030; }
.form-alert ul { padding-left:16px; margin:0; }

.btn-submit {
  display:flex; align-items:center; justify-content:center; gap:10px;
  width:100%; padding:16px; border-radius:var(--r-sm);
  background:var(--red); color:#fff;
  font-size:16px; font-weight:700; font-family:inherit;
  border:none; cursor:pointer;
  transition:background .2s, transform .2s;
  margin-top:28px;
}
.btn-submit:hover { background:var(--red-d); transform:translateY(-1px); }
.btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }
.btn-submit svg { width:17px; height:17px; stroke:#fff; fill:none; stroke-width:2.5; }


@media(max-width:900px) {
  .contact-grid { grid-template-columns:1fr; gap:40px; }
  .contact-body { padding:60px 24px 80px; }
  .contact-hero { padding:60px 24px 72px; }
  .form-row { grid-template-columns:1fr; }
  .contact-form-card { padding:32px 24px; }
}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="contact-hero">
  <div class="contact-hero-inner">
    <div class="hero-pill">Fale Conosco</div>
    <h1>VAMOS<br>CONVERSAR</h1>
    <p>Resposta em até 24 horas úteis. Para urgências, acione diretamente pelo WhatsApp.</p>
  </div>
</div>

<!-- BODY -->
<div class="contact-body">
  <div class="contact-grid">

    <!-- LEFT: info -->
    <div class="contact-info-block">

      <a href="https://wa.me/5521966969836" class="contact-wa" target="_blank" rel="noopener">
        <div class="contact-wa-icon">
          <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
        </div>
        <div>
          <div class="contact-wa-label">Contato rápido</div>
          <div class="contact-wa-num">(21) 96696-9836</div>
        </div>
      </a>

      <div class="contact-info-card">
        <div class="contact-row">
          <div class="contact-row-icon">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 13.88 19.79 19.79 0 01.00 5.21a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 10.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
          </div>
          <div>
            <div class="contact-row-label">Telefone</div>
            <a href="tel:+5521966969836">+55 (21) 96696-9836</a>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-row-icon">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div>
            <div class="contact-row-label">E-mail</div>
            <span>contato@fireout.com.br</span>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-row-icon">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <div class="contact-row-label">Endereço</div>
            <span>R. Santo Cristo, 85<br>Rio de Janeiro, RJ</span>
          </div>
        </div>
      </div>

      <div class="contact-hours">
        <p><strong>Horário de atendimento</strong><br>
        Segunda a Sexta: 08h às 18h</p>
      </div>

    </div>

    <!-- RIGHT: form -->
    <div class="contact-form-card">
      <div class="contact-form-title">ENVIE SUA MENSAGEM</div>
      <p class="contact-form-sub">Preencha o formulário e nossa equipe retorna em até 24 horas úteis.</p>

      @if (session('error'))
        <div class="form-alert form-alert-error">{{ session('error') }}</div>
      @endif

      @if ($errors->any())
        <div class="form-alert form-alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('contato.send') }}" method="POST" id="contact-form" novalidate>
        @csrf

        <div class="form-row">
          <div class="form-group @error('nome') has-error @enderror">
            <label class="form-label" for="nome">Nome *</label>
            <input type="text" class="form-input @error('nome') is-invalid @enderror"
                   id="nome" name="nome" value="{{ old('nome') }}" autocomplete="given-name" required>
            @error('nome')
              <div class="form-error" style="display:block;">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group @error('sobrenome') has-error @enderror">
            <label class="form-label" for="sobrenome">Sobrenome</label>
            <input type="text" class="form-input @error('sobrenome') is-invalid @enderror"
                   id="sobrenome" name="sobrenome" value="{{ old('sobrenome') }}" autocomplete="family-name">
            @error('sobrenome')
              <div class="form-error" style="display:block;">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-group @error('email') has-error @enderror">
          <label class="form-label" for="email">E-mail *</label>
          <input type="email" class="form-input @error('email') is-invalid @enderror"
                 id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
          @error('email')
            <div class="form-error" style="display:block;">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group @error('mensagem') has-error @enderror">
          <label class="form-label" for="mensagem">Mensagem *</label>
          <textarea class="form-input @error('mensagem') is-invalid @enderror"
                    id="mensagem" name="mensagem" rows="5" required>{{ old('mensagem') }}</textarea>
          @error('mensagem')
            <div class="form-error" style="display:block;">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn-submit">
          <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          Enviar Mensagem
        </button>
      </form>
    </div>

  </div>
</div>
@push('scripts')
<script>
(function () {
  const params = new URLSearchParams(window.location.search);
  const msg = params.get('mensagem');
  if (msg) {
    const ta = document.getElementById('mensagem');
    if (ta) ta.value = msg;
  }
})();
</script>
@endpush

@endsection
