@extends('layouts.app')

@section('title', 'Fire Out — Obrigado')

@push('styles')
<style>
.obrigado-wrap {
  min-height: calc(100vh - var(--nav-h));
  margin-top: var(--nav-h);
  background: var(--off);
  display: flex; align-items: center; justify-content: center;
  padding: 64px 24px;
}

.obrigado-card {
  background: #fff;
  border: 1px solid rgba(0,0,0,.07);
  border-radius: var(--r-xl);
  padding: 72px 64px;
  max-width: 520px; width: 100%;
  text-align: center;
  box-shadow: 0 24px 64px rgba(0,0,0,.07);
}

.obrigado-icon {
  width: 72px; height: 72px;
  background: rgba(215,43,43,.08);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 32px;
}
.obrigado-icon svg {
  width: 32px; height: 32px;
  stroke: var(--red); fill: none; stroke-width: 2.5;
  stroke-linecap: round; stroke-linejoin: round;
}

.obrigado-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(36px, 5vw, 52px);
  letter-spacing: 2px; line-height: 1;
  color: var(--ink); margin-bottom: 16px;
}

.obrigado-sub {
  font-size: 16px; color: var(--warm);
  line-height: 1.75; margin-bottom: 40px;
}

.obrigado-divider {
  width: 40px; height: 2px;
  background: var(--red);
  margin: 0 auto 40px;
  border-radius: 2px;
}

@media(max-width: 600px) {
  .obrigado-card { padding: 48px 28px; }
}
</style>
@endpush

@section('content')
<div class="obrigado-wrap">
  <div class="obrigado-card">
    <div class="obrigado-icon">
      <svg viewBox="0 0 24 24">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
    </div>
    <h1 class="obrigado-title">Mensagem<br>Enviada!</h1>
    <div class="obrigado-divider"></div>
    <p class="obrigado-sub">Obrigado pelo seu contato.<br>Nossa equipe retornará em breve.</p>
    <a href="{{ route('home') }}" class="btn-red">Voltar para a Home</a>
  </div>
</div>
@endsection
