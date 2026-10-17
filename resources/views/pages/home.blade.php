@extends('layouts.app')

@section('title', 'Fire Out — Proteção Contra Incêndio')
@section('description', 'Mais de 30 anos em segurança contra incêndio no Rio de Janeiro e em todo o Brasil.')

@push('styles')
<style>
/* ═══ HERO ═══ */
.hero {
  display:grid; grid-template-columns:1fr 1fr;
  margin-top:var(--nav-h);
}

.hero-left {
  display:flex; flex-direction:column; justify-content:center;
  padding:44px 64px; background:var(--off);
}

.hero-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(215,43,43,.08); color:var(--red);
  font-size:10px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
  padding:6px 14px; border-radius:20px;
  margin-bottom:18px; align-self:flex-start;
}

.hero-h1 {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(52px,6.5vw,88px); line-height:.92; letter-spacing:3px;
  color:var(--ink); margin-bottom:16px;
}
.hero-h1 em { color:var(--red); font-style:normal; }

.hero-sub {
  font-size:14px; line-height:1.72; color:var(--warm);
  max-width:400px; margin-bottom:24px;
}

.hero-actions { display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:28px; }

/* ghost button on the light hero background */
.hero-actions .btn-ghost {
  border-color:rgba(26,26,26,.18); color:var(--warm);
}
.hero-actions .btn-ghost:hover { border-color:rgba(26,26,26,.4); color:var(--ink); }

.hero-stats {
  display:flex; gap:32px;
  padding-top:24px; border-top:1px solid rgba(0,0,0,.07);
}
.stat-num {
  font-family:'Bebas Neue',sans-serif; font-size:34px; line-height:1;
  letter-spacing:1px; color:var(--ink);
}
.stat-lbl { font-size:11px; color:var(--warm); margin-top:4px; line-height:1.4; }

.hero-right { position:relative; overflow:hidden; background:var(--ink); max-height:calc(60vh - var(--nav-h)); }
.hero-right > img { width:100%; height:100%; min-height:100%; object-fit:cover; object-position:85% 60%; display:block; opacity:.82; }
.hero-right-overlay {
  position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(to right, rgba(245,242,237,.18), transparent 55%),
             linear-gradient(to top, rgba(26,26,26,.65), transparent 55%);
}

.hero-badge {
  position:absolute; bottom:32px; left:28px;
  background:var(--red); color:#fff;
  padding:18px 22px; border-radius:var(--r-lg);
  display:flex; align-items:center; gap:14px;
  box-shadow:0 12px 32px rgba(215,43,43,.38);
}
.hero-badge-num {
  font-family:'Bebas Neue',sans-serif; font-size:52px; line-height:1; letter-spacing:1px;
}
.hero-badge-txt { font-size:13px; line-height:1.45; opacity:.85; font-weight:500; }

/* ═══ SOBRE ═══ */
.section-sobre { padding:120px 0; background:var(--off); }
.sobre-container { max-width:1200px; margin:0 auto; padding:0 48px; }
.sobre-grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center; }

.sobre-card {
  background:var(--ink); border-radius:var(--r-xl); padding:48px 44px;
}
.sobre-card-title {
  font-family:'Bebas Neue',sans-serif; font-size:17px; letter-spacing:2.5px;
  color:var(--red-l); margin-bottom:28px;
}
.feature-list { list-style:none; margin-bottom:32px; }
.feature-list li {
  display:flex; align-items:flex-start; gap:14px;
  font-size:15px; line-height:1.55; color:rgba(255,255,255,.6);
  padding:13px 0; border-bottom:1px solid rgba(255,255,255,.06);
}
.feature-list li:first-child { padding-top:0; }
.feature-list li:last-child  { border-bottom:none; }
.feature-list li::before {
  content:''; width:6px; height:6px; border-radius:50%;
  background:var(--red-l); flex-shrink:0; margin-top:6px;
}
.sobre-accent {
  font-size:10px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
  color:rgba(255,255,255,.75); display:flex; align-items:center; gap:10px;
}
.sobre-accent::before { content:''; width:24px; height:1px; background:rgba(255,255,255,.5); }

/* ═══ CREDENCIAMENTOS — LOGOS ═══ */
.cred-logo-row {
  display:flex; gap:14px; flex-wrap:wrap; align-items:center; margin-top:28px;
}
.cred-logo-item {
  display:flex; align-items:center; justify-content:center;
  width:88px; height:88px; flex-shrink:0;
  background:#fff; border:1px solid rgba(0,0,0,.09);
  border-radius:var(--r-md); padding:10px; overflow:hidden;
}
.cred-logo-item img {
  width:100%; height:100%; object-fit:contain;
}

/* ═══ NOSSOS PRODUTOS ═══ */
.section-produtos { background:var(--ink); padding:100px 48px; }
.produtos-inner {
  max-width:1200px; margin:0 auto;
  display:grid; grid-template-columns:160px 1fr; gap:80px; align-items:center;
}
.produtos-logo img { width:140px; height:140px; aspect-ratio:1/1; object-fit:contain; display:block; }
.produtos-desc {
  font-size:16px; color:rgba(255,255,255,.72); line-height:1.85;
  margin-bottom:24px; max-width:600px;
}
/* ═══ NOVA MISSÃO ═══ */
.section-missao {
  background:var(--off); padding:100px 48px;
  border-top:1px solid rgba(0,0,0,.06);
}
.missao-inner {
  max-width:720px; margin:0 auto; text-align:center;
}
.missao-body {
  font-size:17px; color:var(--warm); line-height:1.85; margin-top:20px;
}

/* ═══ CLIENTES ═══ */
.section-clientes { padding:120px 0; background:var(--off); }
.clientes-container { max-width:1200px; margin:0 auto; padding:0 48px; }
.clientes-hdr { text-align:center; margin-bottom:64px; }

.logo-grid {
  display:grid; grid-template-columns:repeat(4,1fr);
  border:1px solid rgba(0,0,0,.07); border-radius:var(--r-lg); overflow:hidden;
}
.logo-item {
  padding:36px; display:flex; align-items:center; justify-content:center;
  border-right:1px solid rgba(0,0,0,.07); border-bottom:1px solid rgba(0,0,0,.07);
  background:#fff;
}
.logo-item:nth-child(4n)      { border-right:none; }
.logo-item:nth-last-child(-n+4) { border-bottom:none; }
.logo-item img {
  max-height:48px; max-width:120px; width:auto; height:auto; object-fit:contain;
  filter:grayscale(1); opacity:.45;
  transition:filter .3s, opacity .3s;
}
.logo-item:hover img { filter:none; opacity:1; }

/* ═══ RESPONSIVE ═══ */
@media(max-width:960px) {
  .hero { grid-template-columns:1fr; }
  .hero-left { padding:60px 28px; }
  .hero-right { height:400px; }
  .sobre-grid { grid-template-columns:1fr; gap:44px; }
  .sobre-container { padding:0 24px; }
  .section-sobre { padding:80px 0; }
  .clientes-container { padding:0 24px; }
  .logo-grid { grid-template-columns:repeat(2,1fr); }
  .logo-item:nth-child(4n)        { border-right:1px solid rgba(0,0,0,.07); }
  .logo-item:nth-child(2n)        { border-right:none; }
  .logo-item:nth-last-child(-n+4) { border-bottom:1px solid rgba(0,0,0,.07); }
  .logo-item:nth-last-child(-n+2) { border-bottom:none; }
  .produtos-inner { grid-template-columns:1fr; gap:36px; }
  .section-produtos { padding:72px 24px; }
  .section-missao   { padding:72px 24px; }
}
@media(max-width:600px) {
  .hero-left { padding:48px 20px; }
  .hero-stats { gap:24px; }
  .hero-badge { left:20px; bottom:20px; padding:14px 18px; gap:10px; }
  .hero-badge-num { font-size:40px; }
}
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <h1 class="hero-h1">PROTEÇÃO<br>SEM<br><em>CONCESSÕES</em></h1>
    <p class="hero-sub">Especialistas em prevenção de incêndios com soluções que protegem o que realmente importa: vidas e patrimônios. Da pequena empresa à grande indústria.</p>
    <div class="hero-actions">
      <a href="{{ route('contato') }}" class="btn-red">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 13.88 19.79 19.79 0 01.00 5.21a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 10.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        Solicitar Proposta
      </a>
      <a href="#sobre" class="btn-ghost">Conheça a Fire Out</a>
    </div>
    <div class="hero-stats">
      <div><div class="stat-num">30+</div><div class="stat-lbl">Anos de Experiência</div></div>
      <div><div class="stat-num">6+</div><div class="stat-lbl">Estados atendidos</div></div>
    </div>
  </div>
  <div class="hero-right">
    <img src="{{ asset('img/fire out images/foraAcao.webp') }}" alt="Fire Out equipe em ação" width="1100" height="704" fetchpriority="high" loading="eager" decoding="async">
    <div class="hero-right-overlay"></div>
    <div class="hero-badge">
      <div style="display:flex;flex-direction:column;align-items:center;line-height:1;">
        <div style="font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#fff;margin-bottom:2px;">DESDE</div>
        <div class="hero-badge-num">1996</div>
      </div>
    </div>
  </div>
</section>

<!-- SOBRE -->
<section id="sobre" class="section-sobre">
  <div class="sobre-container">
    <div class="sobre-grid">
      <div>
        <div class="s-label">Quem Somos</div>
        <h2 class="s-title">INDÚSTRIA DE <span style="color:var(--red)">PORTAS<br>CORTA-FOGO</span> E SOLUÇÕES<br>CONTRA INCÊNDIO</h2>
        <p class="s-body">Fabricante de portas corta-fogo, canoplas, abraçadeiras, molduras, tripés para extintores e fornecedora de soluções completas para prevenção e combate a incêndio. Além da fabricação própria, realizamos instalação de redes de hidrantes, sistemas de proteção contra incêndio e projetos personalizados para empresas, condomínios e edificações em geral.</p>
        <a href="{{ route('servicos') }}" class="btn-red" style="margin-top:8px;">Ver Nossos Serviços</a>
        <div class="cred-logo-row">
          <div class="cred-logo-item">
            <img src="{{ asset('img/logo/CBMERJ.webp') }}" alt="Corpo de Bombeiros Militar do Estado do Rio de Janeiro" width="66" height="66">
          </div>
          <div class="cred-logo-item">
            <img src="{{ asset('img/logo/CREA.webp') }}" alt="CREA-RJ — Conselho Regional de Engenharia e Agronomia" width="66" height="40">
          </div>
          <div class="cred-logo-item">
            <img src="{{ asset('img/logo/ABNT.webp') }}?v=2" alt="ABNT — Associação Brasileira de Normas Técnicas" width="62" height="66">
          </div>
        </div>
      </div>
      <div>
        <div class="sobre-card">
          <div class="sobre-card-title">NOSSOS DIFERENCIAIS</div>
          <ul class="feature-list">
            <li>Fabricação própria de portas corta-fogo e acessórios metálicos</li>
            <li>Mais de 30 anos de tradição e experiência</li>
            <li>Produtos com alto padrão de qualidade e acabamento</li>
            <li>Atendimento para todo o território nacional</li>
            <li>Fornecimento para obras de pequeno, médio e grande porte</li>
            <li>Projetos e instalação de redes de hidrantes e sistemas contra incêndio</li>
          </ul>
          <div class="sobre-accent">CONFORMIDADE TOTAL ABNT</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NOSSOS PRODUTOS -->
<section class="section-produtos">
  <div class="produtos-inner">
    <div class="produtos-logo">
      <img src="{{ asset('img/logo/light-logo.webp') }}" alt="Fire Out" width="140" height="140">
    </div>
    <div class="produtos-content">
      <div class="s-label" style="color:var(--red-l);">Nossos Produtos</div>
      <h2 class="s-title s-title--white">LINHA COMPLETA DE<br>EQUIPAMENTOS<br><em style="color:var(--red-l);font-style:normal;">CERTIFICADOS</em></h2>
      <p class="produtos-desc">Produzimos e fornecemos porta corta-fogo, abraçadeiras, molduras, canoplas, mangueiras e acessórios certificados para sistemas de combate a incêndio.</p>
      <a href="{{ route('produtos') }}" class="btn-red" style="margin-top:8px;">Ver Nossos Produtos</a>
    </div>
  </div>
</section>

<!-- CLIENTES -->
<section class="section-clientes">
  <div class="clientes-container">
    <div class="clientes-hdr">
      <div class="s-label" style="justify-content:center;">Nossos Clientes</div>
      <h2 class="s-title">EMPRESAS QUE CONFIRMAM<br>NOSSA <em style="color:var(--red);font-style:normal;">QUALIDADE</em></h2>
    </div>
    <div class="logo-grid">
      @foreach($clientes as $cliente)
      <div class="logo-item"><img src="{{ asset('img/logo-cliente/' . $cliente['logo']) }}" alt="{{ $cliente['nome'] }}" width="120" height="44" loading="lazy"></div>
      @endforeach
    </div>
  </div>
</section>
<!-- MISSÃO -->
<section class="section-missao">
  <div class="missao-inner">
    <div class="s-label" style="justify-content:center;">Nossa Missão</div>
    <h2 class="s-title">PROTEGER VIDAS ATRAVÉS<br>DA <em style="color:var(--red);font-style:normal;">QUALIDADE</em></h2>
    <p class="missao-body">Fabricar portas corta-fogo e equipamentos de segurança contra incêndio com excelência, oferecendo também projetos e instalações que atendam às normas técnicas e às necessidades de cada cliente.</p>
  </div>
</section>


<!-- CTA -->
<div style="max-width:1200px;margin:0 auto 120px;padding:0 48px;">
  <div class="cta-banner">
    <div>
      <h2>PRONTO PARA PROTEGER<br>SUA EDIFICAÇÃO?</h2>
      <p>Nossos especialistas entram em contato com uma proposta personalizada.</p>
    </div>
    <a href="https://wa.me/5521966969836" class="btn-white" target="_blank" onclick="return gtag_report_conversion('https://wa.me/5521966969836');">WhatsApp: (21) 96696-9836</a>
  </div>
</div>
@endsection
