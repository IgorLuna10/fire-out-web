@extends('layouts.app')

@section('title', 'Fire Out — Porta Corta-Fogo P-90 Certificada ABNT')
@section('description', 'Fabricamos e instalamos portas corta-fogo P-90 certificadas ABNT. Conjunto completo com contra-marco, dobradiças e fechadura. Aprovação CBMERJ inclusa.')

@push('styles')
<style>
/* ── RESET ── */
.pcf { --gutter:48px; }
.pcf-wrap { max-width:1200px; margin:0 auto; padding:0 var(--gutter); }
@media(max-width:768px){ .pcf { --gutter:20px; } }

/* ════════════════════════════════════════════
   1. HERO
════════════════════════════════════════════ */
.pcf-hero {
  margin-top:var(--nav-h);
  background:var(--ink); position:relative; overflow:hidden;
  padding:56px 0 52px;
}
.pcf-hero::before {
  content:''; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 60% 100% at 0% 50%, rgba(215,43,43,.13), transparent);
}
.pcf-hero::after {
  content:'P90'; position:absolute; right:-12px; top:50%; transform:translateY(-50%);
  font-family:'Bebas Neue',sans-serif; font-size:240px; letter-spacing:-4px;
  color:rgba(255,255,255,.025); line-height:1; pointer-events:none; white-space:nowrap;
}
.pcf-hero-inner { max-width:1200px; margin:0 auto; padding:0 var(--gutter); position:relative; z-index:1; }
.pcf-hero-nav {
  display:flex; align-items:center; gap:8px;
  font-size:12px; color:rgba(255,255,255,.3); margin-bottom:28px;
}
.pcf-hero-nav a { color:rgba(255,255,255,.3); text-decoration:none; transition:color .15s; }
.pcf-hero-nav a:hover { color:var(--red-l); }
.pcf-hero-nav-sep { opacity:.25; }
.pcf-hero-eyebrow {
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
  color:var(--red-l); margin-bottom:14px;
}
.pcf-hero-eyebrow::before { content:''; width:20px; height:2px; background:var(--red-l); }
.pcf-hero-title {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(48px,6vw,80px); line-height:.92; letter-spacing:3px;
  color:#fff; margin-bottom:18px;
}
.pcf-hero-title em { color:var(--red-l); font-style:normal; }
.pcf-hero-sub {
  font-size:15px; color:rgba(255,255,255,.45); max-width:500px; line-height:1.75; margin-bottom:28px;
}
.pcf-hero-badges { display:flex; gap:10px; flex-wrap:wrap; }
.pcf-hero-badge {
  display:inline-flex; align-items:center; gap:7px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  padding:7px 14px; border-radius:20px;
  font-size:12px; font-weight:600; color:rgba(255,255,255,.7);
}
.pcf-hero-badge svg { width:13px; height:13px; stroke:var(--red-l); fill:none; stroke-width:2; }

/* ════════════════════════════════════════════
   2. PRODUCT SHOWCASE
════════════════════════════════════════════ */
.pcf-showcase {
  background:var(--off); padding:64px 0 80px;
}
.pcf-showcase-grid {
  display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:start;
}

/* — Gallery — */
.pcf-gallery { display:flex; flex-direction:column; gap:12px; position:sticky; top:calc(var(--nav-h) + 24px); }
.pcf-gallery-main {
  background:#fff; border:1px solid rgba(0,0,0,.07); border-radius:var(--r-xl);
  display:flex; align-items:center; justify-content:center;
  padding:40px; aspect-ratio:1/1; overflow:hidden; position:relative;
}
.pcf-gallery-main img { width:100%; height:100%; object-fit:contain; display:block; }
.pcf-gallery-badge {
  position:absolute; top:16px; left:16px;
  font-family:'Bebas Neue',sans-serif; font-size:44px; letter-spacing:2px;
  color:var(--red); line-height:1; opacity:.12;
  pointer-events:none; user-select:none;
}
.pcf-gallery-cert {
  position:absolute; top:16px; right:16px;
  background:var(--red); color:#fff;
  font-size:10px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
  padding:6px 12px; border-radius:20px;
}
.pcf-gallery-caption {
  font-size:13px; font-weight:600; color:var(--ink);
  text-align:center; padding:8px 12px; background:#fff;
  border:1px solid rgba(0,0,0,.07); border-radius:var(--r-md);
  margin-top:2px; transition:all .2s;
}
.pcf-thumbs {
  display:grid; grid-template-columns:repeat(3,1fr); gap:10px;
}
.pcf-thumb {
  background:#fff; border:1.5px solid rgba(0,0,0,.07); border-radius:var(--r-md);
  aspect-ratio:1/1; display:flex; align-items:center; justify-content:center;
  overflow:hidden; cursor:pointer; transition:border-color .15s;
}
.pcf-thumb:hover,
.pcf-thumb.is-active { border-color:var(--red); }
.pcf-thumb img { width:100%; height:100%; object-fit:cover; }
.pcf-thumb-empty {
  background:rgba(0,0,0,.03);
  display:flex; align-items:center; justify-content:center;
}
.pcf-thumb-empty svg { width:18px; height:18px; stroke:rgba(0,0,0,.18); fill:none; stroke-width:1.5; }

/* ── GALERIA DE FÁBRICA & PRODUÇÃO ── */
.pcf-factory { padding:80px 0; background:var(--off); border-top:1px solid rgba(0,0,0,.06); }
.pcf-factory-grid {
  display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-top:40px;
}
.pcf-factory-card {
  background:#fff; border-radius:var(--r-lg); overflow:hidden;
  border:1px solid rgba(0,0,0,.08); display:flex; flex-direction:column;
  transition:transform .2s, box-shadow .2s;
}
.pcf-factory-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,.09); }
.pcf-factory-img-wrap {
  aspect-ratio:3/4; overflow:hidden; background:#eee; position:relative; cursor:pointer;
}
.pcf-factory-img-wrap img {
  width:100%; height:100%; object-fit:cover; display:block;
  transition:transform .3s;
}
.pcf-factory-card:hover .pcf-factory-img-wrap img { transform:scale(1.04); }
.pcf-factory-body { padding:18px 20px; }
.pcf-factory-title { font-size:14px; font-weight:700; color:var(--ink); margin-bottom:4px; }
.pcf-factory-desc { font-size:12.5px; color:var(--warm); line-height:1.5; }

/* — Details panel — */
.pcf-details { display:flex; flex-direction:column; gap:0; }
.pcf-eyebrow {
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
  color:var(--red); margin-bottom:12px;
}
.pcf-eyebrow::before { content:''; width:20px; height:2px; background:var(--red); }
.pcf-title {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(36px,4.5vw,52px); line-height:.95; letter-spacing:2px;
  color:var(--ink); margin-bottom:24px;
}

.pcf-meta-row {
  display:grid; grid-template-columns:repeat(2,1fr);
  border:1px solid rgba(0,0,0,.08); border-radius:var(--r-md); overflow:hidden;
  margin-bottom:28px; background:#fff;
}
.pcf-meta-cell {
  padding:14px 18px; border-right:1px solid rgba(0,0,0,.06); border-bottom:1px solid rgba(0,0,0,.06);
}
.pcf-meta-cell:nth-child(2n)  { border-right:none; }
.pcf-meta-cell:nth-last-child(-n+2) { border-bottom:none; }
.pcf-meta-key { font-size:10px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--red); margin-bottom:3px; }
.pcf-meta-val { font-size:14px; font-weight:600; color:var(--ink); }

.pcf-desc { font-size:15px; color:#555; line-height:1.8; margin-bottom:28px; }

.pcf-spec-table { list-style:none; margin-bottom:32px; border-top:1px solid rgba(0,0,0,.06); }
.pcf-spec-row {
  display:grid; grid-template-columns:150px 1fr; gap:12px;
  padding:11px 0; border-bottom:1px solid rgba(0,0,0,.05); font-size:13.5px;
}
.pcf-spec-key { font-weight:700; color:var(--ink); }
.pcf-spec-val { color:#555; }

.pcf-actions { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px; }

.pcf-trust {
  display:flex; gap:16px; flex-wrap:wrap; padding-top:20px;
  border-top:1px solid rgba(0,0,0,.07);
}
.pcf-trust-item {
  display:flex; align-items:center; gap:7px;
  font-size:12px; font-weight:600; color:var(--warm);
}
.pcf-trust-item svg { width:14px; height:14px; stroke:var(--red); fill:none; stroke-width:2; flex-shrink:0; }

/* ════════════════════════════════════════════
   3. FEATURE ICONS GRID
════════════════════════════════════════════ */
.pcf-features { padding:72px 0; background:#fff; border-top:1px solid rgba(0,0,0,.06); }
.pcf-feat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:24px; margin-top:48px; }
.pcf-feat-card {
  display:flex; flex-direction:column; gap:16px;
  padding:28px 24px; border:1px solid rgba(0,0,0,.07);
  border-radius:var(--r-lg); background:#fff;
  transition:box-shadow .2s, border-color .2s;
}
.pcf-feat-card:hover { box-shadow:0 8px 32px rgba(0,0,0,.07); border-color:rgba(215,43,43,.2); }
.pcf-feat-icon {
  width:48px; height:48px; background:rgba(215,43,43,.08); border-radius:var(--r-md);
  display:flex; align-items:center; justify-content:center;
}
.pcf-feat-icon svg { width:22px; height:22px; stroke:var(--red); fill:none; stroke-width:1.8; }
.pcf-feat-title { font-size:15px; font-weight:700; color:var(--ink); line-height:1.3; }
.pcf-feat-text  { font-size:13px; color:var(--warm); line-height:1.65; }

/* ════════════════════════════════════════════
   4. ACCORDIONS
════════════════════════════════════════════ */
.pcf-accordions { padding:64px 0 96px; background:#fff; }
.pcf-accord-list { border:1px solid rgba(0,0,0,.08); border-radius:var(--r-lg); overflow:hidden; }
.pcf-accord-item { border-bottom:1px solid rgba(0,0,0,.07); }
.pcf-accord-item:last-child { border-bottom:none; }
.pcf-accord-btn {
  width:100%; display:flex; align-items:center; justify-content:space-between;
  padding:20px 28px; background:#fff; border:none; cursor:pointer;
  font-family:inherit; font-size:14px; font-weight:700; letter-spacing:.2px;
  color:var(--ink); text-align:left; transition:background .15s;
}
.pcf-accord-btn:hover { background:rgba(0,0,0,.02); }
.pcf-accord-btn.open { background:rgba(215,43,43,.03); color:var(--red); }
.pcf-accord-icon {
  width:26px; height:26px; border-radius:50%; border:1.5px solid rgba(0,0,0,.12);
  display:flex; align-items:center; justify-content:center; flex-shrink:0;
  transition:transform .25s, border-color .15s;
}
.pcf-accord-btn.open .pcf-accord-icon { transform:rotate(45deg); border-color:var(--red); }
.pcf-accord-icon svg { width:11px; height:11px; stroke:currentColor; fill:none; stroke-width:2.5; }
.pcf-accord-body { display:none; padding:4px 28px 28px; }
.pcf-accord-body.open { display:block; }

.feat-list { list-style:none; }
.feat-list li {
  display:grid; grid-template-columns:160px 1fr; gap:16px;
  padding:13px 0; border-bottom:1px solid rgba(0,0,0,.05); font-size:14px;
}
.feat-list li:first-child { border-top:1px solid rgba(0,0,0,.05); }
.feat-list li:last-child  { border-bottom:none; }
.feat-key { font-weight:700; color:var(--ink); }
.feat-val { color:#555; line-height:1.6; }

.price-list { list-style:none; }
.price-list li {
  display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
  padding:13px 0; border-bottom:1px solid rgba(0,0,0,.05); font-size:14px;
}
.price-list li:first-child { border-top:1px solid rgba(0,0,0,.05); }
.price-list li:last-child  { border-bottom:none; }
.price-name { color:var(--ink); }
.price-val  { font-weight:700; color:var(--red); white-space:nowrap; }

.process-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; padding-top:4px; }
.process-item { display:flex; flex-direction:column; gap:10px; }
.process-num {
  width:38px; height:38px; border-radius:50%;
  border:2px solid rgba(215,43,43,.3); background:rgba(215,43,43,.05);
  display:flex; align-items:center; justify-content:center;
  font-family:'Bebas Neue',sans-serif; font-size:17px; color:var(--red);
}
.process-item h5 { font-size:14px; font-weight:700; color:var(--ink); margin:0; }
.process-item p  { font-size:13px; color:var(--warm); line-height:1.6; margin:0; }

/* ════════════════════════════════════════════
   5. CERTIFICATION BAND
════════════════════════════════════════════ */
.pcf-cert-band { background:var(--ink); padding:72px 0; }
.pcf-cert-inner { display:grid; grid-template-columns:200px 1fr; gap:80px; align-items:center; }
.pcf-cert-seal {
  width:160px; height:160px; border-radius:50%;
  border:2px solid rgba(215,43,43,.35); background:rgba(215,43,43,.07);
  display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px;
  margin:0 auto;
}
.pcf-cert-seal-logo { font-family:'Bebas Neue',sans-serif; font-size:34px; letter-spacing:3px; color:var(--red-l); line-height:1; }
.pcf-cert-seal-sub  { font-size:10px; font-weight:700; letter-spacing:2px; color:rgba(255,255,255,.35); text-transform:uppercase; }
.pcf-cert-points { list-style:none; }
.pcf-cert-points li {
  display:flex; align-items:flex-start; gap:12px;
  font-size:14px; color:rgba(255,255,255,.6); line-height:1.7;
  padding:12px 0; border-bottom:1px solid rgba(255,255,255,.06);
}
.pcf-cert-points li:first-child { padding-top:0; }
.pcf-cert-points li:last-child  { border-bottom:none; }
.pcf-cert-dot { width:6px; height:6px; border-radius:50%; background:var(--red); flex-shrink:0; margin-top:8px; }

/* ════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════ */
@media(max-width:960px) {
  .pcf-showcase-grid { grid-template-columns:1fr; gap:40px; }
  .pcf-gallery { position:static; }
  .pcf-feat-grid { grid-template-columns:1fr 1fr; }
  .pcf-cert-inner { grid-template-columns:1fr; gap:40px; }
  .pcf-cert-seal { width:120px; height:120px; }
  .process-grid { grid-template-columns:1fr 1fr; }
  .pcf-factory-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:640px) {
  .pcf-feat-grid { grid-template-columns:1fr 1fr; gap:12px; }
  .pcf-feat-card { padding:20px 16px; }
  .feat-list li { grid-template-columns:1fr; gap:2px; }
  .pcf-meta-row { grid-template-columns:1fr 1fr; }
  .pcf-accord-btn { padding:16px 20px; }
  .pcf-accord-body { padding:4px 20px 20px; }
  .process-grid { grid-template-columns:1fr 1fr; }
  .pcf-thumbs { grid-template-columns:repeat(6,1fr); }
}
@media(max-width:480px) {
  .pcf-factory-grid { grid-template-columns:1fr; }
}
@media(max-width:400px) {
  .process-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')

{{-- 1. HERO --}}
<div class="pcf-hero">
  <div class="pcf-hero-inner">
    <div class="pcf-hero-eyebrow">Fabricação Própria — Fire Out</div>
    <h1 class="pcf-hero-title">PORTA CORTA-<em>FOGO</em><br>P-90 CERTIFICADA</h1>
    <p class="pcf-hero-sub">Fabricamos e instalamos com certificação ABNT. Controle total do processo — da chapa ao acabamento — com aprovação junto ao Corpo de Bombeiros.</p>
    <div class="pcf-hero-badges">
      <span class="pcf-hero-badge">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Certificada ABNT
      </span>
      <span class="pcf-hero-badge">
        <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Aprovação CBMERJ
      </span>
    </div>
  </div>
</div>

{{-- 2. PRODUCT SHOWCASE --}}
<section class="pcf-showcase">
  <div class="pcf-wrap">
    <div class="pcf-showcase-grid">

      {{-- Gallery --}}
      <div class="pcf-gallery">
        <div class="pcf-gallery-main">
          <div class="pcf-gallery-badge">P-90</div>
          <span class="pcf-gallery-cert">Certificada ABNT</span>
          <img id="pcf-main-img"
               src="{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}"
               alt="Porta Corta-Fogo P-90"
               width="400" height="500">
        </div>
        <div id="pcf-main-caption" class="pcf-gallery-caption">Porta Corta-Fogo P-90 Certificada ABNT</div>
        <div class="pcf-thumbs">
          <div class="pcf-thumb is-active" data-src="{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}" data-title="Porta Corta-Fogo P-90 Certificada ABNT">
            <img src="{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}" alt="Porta Corta Fogo P-90 Certificada ABNT" width="80" height="60">
          </div>
          <div class="pcf-thumb" data-src="{{ asset('img/fire out images/pcf-fabricacao-1.webp') }}" data-title="Estoque & Produção em Lote na Fábrica">
            <img src="{{ asset('img/fire out images/pcf-fabricacao-1.webp') }}" alt="Estoque & Produção em Lote na Fábrica" width="80" height="60">
          </div>
          <div class="pcf-thumb" data-src="{{ asset('img/fire out images/pcf-galvanizada-e-pintada.webp') }}" data-title="Portas de Aço Galvanizado e Pintadas">
            <img src="{{ asset('img/fire out images/pcf-galvanizada-e-pintada.webp') }}" alt="Portas de Aço Galvanizado e Pintadas" width="80" height="60">
          </div>
        </div>
      </div>

      {{-- Details --}}
      <div class="pcf-details">
        <div class="pcf-eyebrow">Fabricação Própria — Fire Out</div>
        <h1 class="pcf-title">PORTA CORTA-FOGO<br>P-90 CERTIFICADA</h1>

        <div class="pcf-meta-row">
          <div class="pcf-meta-cell">
            <div class="pcf-meta-key">Resistência</div>
            <div class="pcf-meta-val">90 Minutos</div>
          </div>
          <div class="pcf-meta-cell">
            <div class="pcf-meta-key">Norma</div>
            <div class="pcf-meta-val">Normas ABNT</div>
          </div>
          <div class="pcf-meta-cell">
            <div class="pcf-meta-key">Acabamento</div>
            <div class="pcf-meta-val">Chapa Galvanizada</div>
          </div>
          <div class="pcf-meta-cell">
            <div class="pcf-meta-key">Núcleo</div>
            <div class="pcf-meta-val">Isolante Térmico Certificado</div>
          </div>
        </div>

        <p class="pcf-desc">Porta Corta-Fogo Padrão Completa. O modelo ideal que instalamos para garantir total compartimentação ativa e segurança em indústrias, depósitos, garagens cobertas, locais com GLP e edificações comerciais/mistas.</p>

        <ul class="pcf-spec-table">
          <li class="pcf-spec-row">
            <span class="pcf-spec-key">Espessura</span>
            <span class="pcf-spec-val">5 cm — chapa de aço galvanizado com núcleo de fibra cerâmica</span>
          </li>
          <li class="pcf-spec-row">
            <span class="pcf-spec-key">Conjunto incluso</span>
            <span class="pcf-spec-val">Contra-marco, parafusos, 3 dobradiças de mola reguláveis e fechadura trinco de sobrepor</span>
          </li>
          <li class="pcf-spec-row">
            <span class="pcf-spec-key">Dimensões</span>
            <span class="pcf-spec-val">60 × 180 cm — R$ 1.250 &nbsp;|&nbsp; 90 × 210 cm — R$ 1.350</span>
          </li>
          <li class="pcf-spec-row">
            <span class="pcf-spec-key">Aplicações</span>
            <span class="pcf-spec-val">Indústrias, depósitos, garagens, locais com GLP, edificações mistas</span>
          </li>
        </ul>

        <div class="pcf-actions">
          <a href="https://wa.me/5521966969836?text=Ol%C3%A1%21+Gostaria+de+um+or%C3%A7amento+para+Porta+Corta-Fogo+P-90." class="btn-wa" target="_blank" rel="noopener" onclick="return gtag_report_conversion(this.href);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
            Solicitar Orçamento
          </a>
          <a href="{{ route('contato') }}" class="btn-dark">Enviar E-mail</a>
        </div>

        <div class="pcf-trust">
          <div class="pcf-trust-item">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Aprovação CBMERJ
          </div>
          <div class="pcf-trust-item">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pronto para a entrega
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- 3. ACCORDIONS --}}
<section class="pcf-accordions">
  <div class="pcf-wrap">
    <div class="pcf-accord-list">

      <div class="pcf-accord-item">
        <button class="pcf-accord-btn" data-pcf-accord>
          Componentes Inclusos no Conjunto
          <span class="pcf-accord-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
        </button>
        <div class="pcf-accord-body">
          <ul class="feat-list">
            <li><span class="feat-key">Dobradiça de Mola</span><span class="feat-val">Mantém a porta sempre fechada automaticamente após a passagem.</span></li>
            <li><span class="feat-key">Fechadura Corta-Fogo</span><span class="feat-val">Trinco de sobrepor resistente a altas temperaturas. Saída sem chave pelo lado interno.</span></li>
            <li><span class="feat-key">Barra Antipânico</span><span class="feat-val">Dispositivo obrigatório (NBR 11785) para abertura imediata sob simples pressão horizontal.</span></li>
            <li><span class="feat-key">Contra Marco</span><span class="feat-val">Moldura metálica galvanizada para encaixe perfeito na alvenaria ou drywall.</span></li>
          </ul>
        </div>
      </div>

      <div class="pcf-accord-item">
        <button class="pcf-accord-btn" data-pcf-accord>
          Tabela de Preços e Modelos
          <span class="pcf-accord-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
        </button>
        <div class="pcf-accord-body">
          <ul class="price-list">
            <li><span class="price-name">Porta Corta-Fogo Padrão Completa — 90 × 210 cm</span><span class="price-val">R$ 1.350,00</span></li>
            <li><span class="price-name">Porta Corta-Fogo Padrão Completa — 60 × 180 cm</span><span class="price-val">R$ 1.250,00</span></li>
            <li><span class="price-name">Fechadura de Sobrepor sem Chave</span><span class="price-val">R$ 65,00</span></li>
            <li><span class="price-name">Fechadura de Sobrepor com Chave</span><span class="price-val">R$ 260,00</span></li>
            <li><span class="price-name">Dobradiça de Mola Cinza</span><span class="price-val">R$ 40,00<span></li>
            <li><span class="price-name">Barras Antipânico &amp; Portas Duplas / Fora de Padrão</span><span class="price-val">Sob Consulta</span></li>
          </ul>
        </div>
      </div>

      <div class="pcf-accord-item">
        <button class="pcf-accord-btn" data-pcf-accord>
          Do Pedido à Entrega Instalada
          <span class="pcf-accord-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
        </button>
        <div class="pcf-accord-body">
          <div class="process-grid">
            <div class="process-item">
              <div class="process-num">01</div>
              <h5>Visita Técnica</h5>
              <p>Nossa equipe visita o local, mede os vãos e avalia o projeto.</p>
            </div>
            <div class="process-item">
              <div class="process-num">02</div>
              <h5>Proposta Técnica</h5>
              <p>Elaboramos proposta detalhada com especificações e prazo.</p>
            </div>
            <div class="process-item">
              <div class="process-num">03</div>
              <h5>Fabricação Própria</h5>
              <p>Porta fabricada em fábrica com núcleo isolante certificado.</p>
            </div>
            <div class="process-item">
              <div class="process-num">04</div>
              <h5>Instalação + Laudo</h5>
              <p>Instalação pela equipe técnica própria</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- 4. FEATURE ICONS --}}
<section class="pcf-features">
  <div class="pcf-wrap">
    <div style="text-align:center;">
      <div class="s-label" style="justify-content:center;">Por que a Fire Out</div>
      <h2 class="s-title">NÃO SOMOS REVENDEDORES —<br>NÓS FABRICAMOS</h2>
    </div>
    <div class="pcf-feat-grid">
      <div class="pcf-feat-card">
        <div class="pcf-feat-icon">
          <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
        </div>
        <div class="pcf-feat-title">Fabricação Própria</div>
        <div class="pcf-feat-text">Cada porta produzida em nossa fábrica. Controlamos seleção de chapas, núcleo isolante e acabamento final.</div>
      </div>
      <div class="pcf-feat-card">
        <div class="pcf-feat-icon">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="pcf-feat-title">Certificação ABNT</div>
        <div class="pcf-feat-text">Conformidade total com as normas ABNT. Laudo técnico de conformidade emitido em cada instalação.</div>
      </div>
      <div class="pcf-feat-card">
        <div class="pcf-feat-icon">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="pcf-feat-title">Equipe Técnica Própria</div>
        <div class="pcf-feat-text">Instalação, medição e visita técnica realizadas pela nossa equipe. Sem terceiros, sem surpresas.</div>
      </div>
      <div class="pcf-feat-card">
        <div class="pcf-feat-icon">
          <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="pcf-feat-title">Sob Medida</div>
        <div class="pcf-feat-text">Portas duplas e fora de padrão disponíveis. Atendemos projetos especiais e edificações não convencionais.</div>
      </div>
    </div>
  </div>
</section>

{{-- 4.5. GALERIA DE FÁBRICA --}}
<section class="pcf-factory">
  <div class="pcf-wrap">
    <div style="text-align:center;max-width:700px;margin:0 auto 40px;">
      <div class="s-label" style="justify-content:center;">Produção Própria — Fire Out</div>
      <h2 class="s-title">CONFIRA NOSSA FÁBRICA<br>E <em style="color:var(--red);font-style:normal;">PROCESSO DE FABRICAÇÃO</em></h2>
      <p class="s-body" style="margin-bottom:0;">Fabricação com chapas galvanizadas de alta resistência, corte de precisão, montagem estrutural e acabamento técnico alinhado às normas ABNT.</p>
    </div>

    <div class="pcf-factory-grid">
      <div class="pcf-factory-card">
        <div class="pcf-factory-img-wrap" onclick="document.getElementById('pcf-main-img').src='{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}';document.getElementById('pcf-main-caption').textContent='Porta Corta-Fogo P-90 Certificada ABNT';window.scrollTo({top:document.querySelector('.pcf-showcase').offsetTop - 80,behavior:'smooth'});">
          <img src="{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}" alt="Porta Corta-Fogo P-90 Certificada ABNT" width="400" height="260" loading="lazy">
        </div>
        <div class="pcf-factory-body">
          <div class="pcf-factory-title">Porta Corta-Fogo P-90</div>
          <div class="pcf-factory-desc">Porta Corta-Fogo P-90 Certificada ABNT com alto isolamento térmico e resistência.</div>
        </div>
      </div>

      <div class="pcf-factory-card">
        <div class="pcf-factory-img-wrap" onclick="document.getElementById('pcf-main-img').src='{{ asset('img/fire out images/pcf-fabricacao-1.webp') }}';document.getElementById('pcf-main-caption').textContent='Estoque & Produção em Lote na Fábrica';window.scrollTo({top:document.querySelector('.pcf-showcase').offsetTop - 80,behavior:'smooth'});">
          <img src="{{ asset('img/fire out images/pcf-fabricacao-1.webp') }}" alt="Estoque & Produção em Lote na Fábrica" width="400" height="260" loading="lazy">
        </div>
        <div class="pcf-factory-body">
          <div class="pcf-factory-title">Estoque & Produção em Lote</div>
          <div class="pcf-factory-desc">Capacidade fabril para atender grandes obras, condomínios e edifícios comerciais.</div>
        </div>
      </div>

      <div class="pcf-factory-card">
        <div class="pcf-factory-img-wrap" onclick="document.getElementById('pcf-main-img').src='{{ asset('img/fire out images/pcf-galvanizada-e-pintada.webp') }}';document.getElementById('pcf-main-caption').textContent='Portas de Aço Galvanizado e Pintadas';window.scrollTo({top:document.querySelector('.pcf-showcase').offsetTop - 80,behavior:'smooth'});">
          <img src="{{ asset('img/fire out images/pcf-galvanizada-e-pintada.webp') }}" alt="Portas de Aço Galvanizado e Pintadas" width="400" height="260" loading="lazy">
        </div>
        <div class="pcf-factory-body">
          <div class="pcf-factory-title">Linha Galvanizada & Pintada</div>
          <div class="pcf-factory-desc">Portas de aço galvanizado e opções com acabamento pintado de fábrica.</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- 5. CERTIFICATION BAND --}}
<section class="pcf-cert-band">
  <div class="pcf-wrap">
    <div class="pcf-cert-inner">
      <div>
        <div class="pcf-cert-seal">
          <div class="pcf-cert-seal-logo">ABNT</div>
          <div class="pcf-cert-seal-sub">CERTIFICADA</div>
        </div>
      </div>
      <div>
        <div class="s-label">Por que a certificação importa</div>
        <h2 class="s-title s-title--white" style="margin-bottom:20px;">CONFORMIDADE QUE<br>PROTEGE VIDAS</h2>
        <ul class="pcf-cert-points">
          <li><div class="pcf-cert-dot"></div>Esse certificado define os requisitos de desempenho para portas corta-fogo, garantindo que o produto funcione durante o tempo de resistência declarado.</li>
          <li><div class="pcf-cert-dot"></div>Portas sem certificação comprometem a aprovação junto ao Corpo de Bombeiros, causando multas e impedimento de funcionamento.</li>
          <li><div class="pcf-cert-dot"></div>A porta da Fire Out possuem certificação em dia e laudo técnico de conformidade incluído na instalação.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- 6. CTA BANNER --}}
<div style="max-width:1200px;margin:0 auto 120px;padding:48px var(--gutter,48px) 0;">
  <div class="cta-banner">
    <div>
      <h2>PRECISA DE UMA PORTA<br>CORTA-FOGO?</h2>
      <p>Fazemos visita técnica e enviamos proposta em até 24h.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="https://wa.me/5521966969836" class="btn-white" target="_blank" rel="noopener" onclick="return gtag_report_conversion('https://wa.me/5521966969836');">WhatsApp: (21) 96696-9836</a>
      <a href="{{ route('contato') }}" class="btn-ghost">Enviar E-mail</a>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Accordion
document.querySelectorAll('[data-pcf-accord]').forEach(btn => {
  btn.addEventListener('click', () => {
    const open = btn.classList.contains('open');
    btn.classList.toggle('open', !open);
    btn.nextElementSibling.classList.toggle('open', !open);
  });
});
// Thumbnail gallery
document.querySelectorAll('.pcf-thumb[data-src]').forEach(thumb => {
  thumb.addEventListener('click', () => {
    const src = thumb.dataset.src;
    const title = thumb.dataset.title || '';
    if (!src) return;
    document.getElementById('pcf-main-img').src = src;
    const cap = document.getElementById('pcf-main-caption');
    if (cap) cap.textContent = title;
    document.querySelectorAll('.pcf-thumb').forEach(t => t.classList.remove('is-active'));
    thumb.classList.add('is-active');
  });
});
</script>
@endpush
@endsection
