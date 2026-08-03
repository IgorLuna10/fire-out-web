@extends('layouts.app')

@section('title', 'Fire Out — Serviços')
@section('description', 'Engenharia especializada em combate a incêndio: projetos técnicos, instalação de hidrantes e sprinklers, manutenção preventiva, testes regulamentares e emissão de AVCB.')

@push('styles')
<style>
.page-wrap { max-width:1200px; margin:0 auto; padding:0 48px; }

/* ── HERO ── */
.page-hero {
  margin-top:var(--nav-h); background:var(--off);
  padding:96px 48px 108px; position:relative; overflow:hidden;
  border-bottom:1px solid rgba(0,0,0,.07);
}
.page-hero-inner {
  max-width:1200px; margin:0 auto; position:relative; z-index:1;
  display:grid; grid-template-columns:220px 1fr; gap:80px; align-items:center;
}
.page-hero-logo img {
  width:200px; height:auto; display:block; opacity:.9;
}
.hero-eyebrow {
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
  color:var(--red); margin-bottom:20px;
}
.hero-eyebrow::before { content:''; width:24px; height:2px; background:var(--red); }
.page-hero h1 {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(56px,7vw,92px); line-height:.93; letter-spacing:2px;
  color:var(--ink); margin-bottom:24px;
}
.page-hero h1 span { color:var(--red); }
.page-hero-sub { font-size:17px; color:var(--warm); max-width:500px; line-height:1.75; }

/* ── FEATURED SERVICES (top 2) ── */
.section-featured { padding:80px 0 0; }
.featured-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }

.featured-card {
  background:#fff; border-radius:var(--r-xl);
  border:1px solid rgba(0,0,0,.06);
  overflow:hidden; position:relative;
  transition:transform .25s var(--ease), box-shadow .25s var(--ease);
}
.featured-card:hover { transform:translateY(-4px); box-shadow:0 24px 56px rgba(0,0,0,.1); }

.featured-card-top {
  background:var(--off); padding:40px 40px 36px; position:relative; overflow:hidden;
  border-bottom:1px solid rgba(0,0,0,.06);
}
.featured-num {
  font-family:'Bebas Neue',sans-serif; font-size:80px; line-height:1;
  color:rgba(0,0,0,.05); position:absolute; top:16px; right:28px;
  letter-spacing:-2px;
}
.featured-icon {
  width:56px; height:56px; background:rgba(215,43,43,.1); border-radius:var(--r-md);
  display:flex; align-items:center; justify-content:center; margin-bottom:20px;
}
.featured-icon svg { width:26px; height:26px; stroke:var(--red); fill:none; stroke-width:1.5; }
.featured-card-top h3 {
  font-family:'Bebas Neue',sans-serif; font-size:clamp(26px,3vw,34px);
  letter-spacing:1px; color:var(--ink); line-height:1.05; margin-bottom:12px;
}
.featured-card-top p { font-size:15px; color:var(--warm); line-height:1.7; }

.featured-card-body { padding:32px 40px 36px; }
.featured-list { list-style:none; }
.featured-list li {
  display:flex; align-items:flex-start; gap:12px;
  font-size:14px; color:#555; line-height:1.55;
  padding:11px 0; border-bottom:1px solid rgba(0,0,0,.06);
}
.featured-list li:last-child { border-bottom:none; }
.featured-list li::before {
  content:''; width:6px; height:6px; border-radius:50%;
  background:var(--red); flex-shrink:0; margin-top:5px; opacity:.75;
}

/* ── SERVICE STRIP (remaining services) ── */
.section-strip { padding:24px 0 0; }
.strip-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }

.strip-card {
  background:#fff; border-radius:var(--r-lg); padding:32px 28px;
  border:1px solid rgba(0,0,0,.06);
  display:flex; flex-direction:column;
  transition:transform .2s var(--ease), box-shadow .2s var(--ease), border-color .2s;
}
.strip-card:hover {
  transform:translateY(-3px); box-shadow:0 16px 40px rgba(0,0,0,.08); border-color:transparent;
}
.strip-icon {
  width:46px; height:46px; background:rgba(215,43,43,.07); border-radius:var(--r-sm);
  display:flex; align-items:center; justify-content:center; margin-bottom:18px;
  transition:background .2s;
}
.strip-card:hover .strip-icon { background:rgba(215,43,43,.13); }
.strip-icon svg { width:22px; height:22px; stroke:var(--red); fill:none; stroke-width:1.5; }
.strip-card h3 { font-size:17px; font-weight:700; color:var(--ink); margin-bottom:10px; line-height:1.3; }
.strip-card p  { font-size:14px; color:var(--warm); line-height:1.75; flex:1; margin-bottom:20px; }

.strip-toggle {
  display:inline-flex; align-items:center; gap:6px;
  font-size:13px; font-weight:600; color:var(--red);
  cursor:pointer; background:none; border:none; padding:0;
  transition:opacity .2s; align-self:flex-start;
}
.strip-toggle:hover { opacity:.7; }
.strip-toggle svg { width:13px; height:13px; stroke:currentColor; fill:none; stroke-width:2.5; transition:transform .2s; }
.strip-card.expanded .strip-toggle svg { transform:rotate(180deg); }

.strip-detail {
  display:none; list-style:none; padding-top:16px; border-top:1px solid rgba(0,0,0,.06); margin-top:4px;
}
.strip-card.expanded .strip-detail { display:flex; flex-direction:column; }
.strip-detail li {
  display:flex; align-items:flex-start; gap:9px;
  font-size:13px; color:#666; padding:6px 0; line-height:1.5;
}
.strip-detail li::before {
  content:''; width:4px; height:4px; border-radius:50%;
  background:var(--red); flex-shrink:0; margin-top:6px; opacity:.6;
}

/* ── DIFFERENTIALS ── */
.section-dif { padding:96px 0; }
.dif-grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center; }
.dif-img { border-radius:var(--r-xl); overflow:hidden; }
.dif-img img { width:100%; height:460px; object-fit:cover; }
.dif-list { list-style:none; margin:28px 0 40px; }
.dif-list li {
  display:flex; align-items:flex-start; gap:14px;
  font-size:15px; color:#555; padding:13px 0;
  border-bottom:1px solid rgba(0,0,0,.07); line-height:1.55;
}
.dif-list li:last-child { border-bottom:none; }
.dif-check {
  width:22px; height:22px; border-radius:50%;
  background:rgba(215,43,43,.1); flex-shrink:0;
  display:flex; align-items:center; justify-content:center; margin-top:1px;
}
.dif-check svg { width:11px; height:11px; stroke:var(--red); fill:none; stroke-width:3; }

/* ── CLIENTS ── */
.section-clients-strip { padding:80px 0 96px; }
.clients-hdr { text-align:center; margin-bottom:52px; }
.logo-strip {
  display:flex; flex-wrap:wrap; justify-content:center;
  border:1px solid rgba(0,0,0,.07); border-radius:var(--r-lg); overflow:hidden; background:#fff;
}
.logo-strip-item {
  flex:0 0 25%; padding:32px;
  display:flex; align-items:center; justify-content:center;
  border-right:1px solid rgba(0,0,0,.07); border-bottom:1px solid rgba(0,0,0,.07);
}
.logo-strip-item:nth-child(4n)        { border-right:none; }
.logo-strip-item:nth-last-child(-n+4) { border-bottom:none; }
.logo-strip-item img {
  max-height:44px; max-width:110px; object-fit:contain;
  filter:grayscale(1); opacity:.45; transition:filter .3s, opacity .3s;
}
.logo-strip-item:hover img { filter:none; opacity:1; }

@media(max-width:960px) {
  .featured-grid  { grid-template-columns:1fr; }
  .strip-grid     { grid-template-columns:1fr 1fr; }
  .dif-grid       { grid-template-columns:1fr; gap:40px; }
  .dif-img        { display:none; }
  .page-wrap       { padding:0 24px; }
  .page-hero       { padding:72px 24px 84px; }
  .page-hero-inner { grid-template-columns:1fr; gap:36px; }
  .page-hero-logo  { display:none; }
}
@media(max-width:600px) {
  .strip-grid { grid-template-columns:1fr; }
  .logo-strip-item { flex:0 0 50%; }
  .logo-strip-item:nth-child(4n)        { border-right:1px solid rgba(0,0,0,.07); }
  .logo-strip-item:nth-child(2n)        { border-right:none; }
  .logo-strip-item:nth-last-child(-n+4) { border-bottom:1px solid rgba(0,0,0,.07); }
  .logo-strip-item:nth-last-child(-n+2) { border-bottom:none; }
}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-logo">
      <img src="{{ asset('img/logo/dark-logo.png') }}" alt="Fire Out">
    </div>
    <div>
      <div class="hero-eyebrow">Engenharia de Combate a Incêndio</div>
      <h1>PROJETOS.<br>MANUTENÇÃO.<br><span>INSTALAÇÃO.</span></h1>
      <p class="page-hero-sub">Engenharia especializada em combate a incêndio. Oferecemos execução técnica rigorosa para garantir a proteção total do seu patrimônio e total conformidade para a emissão e renovação de AVCB.</p>
    </div>
  </div>
</div>

<!-- FEATURED: Serviços em Destaque -->
<section class="section-featured">
  <div class="page-wrap">
    <div style="margin-bottom:36px; display:flex; align-items:flex-end; justify-content:space-between; gap:24px; flex-wrap:wrap;">
      <div>
        <div class="s-label">Serviços em Destaque</div>
        <h2 class="s-title" style="margin-bottom:0;">EXECUÇÃO<br><em style="color:var(--red);font-style:normal;">TÉCNICA RIGOROSA</em></h2>
      </div>
      <p style="font-size:15px;color:var(--warm);max-width:360px;line-height:1.7;">Montagem completa e manutenção de sistemas hidráulicos fixos sob controle de engenharia especializada.</p>
    </div>
    <div class="featured-grid">

      <div class="featured-card">
        <div class="featured-card-top">
          <div class="featured-num">01</div>
          <div class="featured-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2v6m0 0c-3 0-5 2-5 5s2 5 5 5 5-2 5-5-2-5-5-5zM8 22h8"/></svg>
          </div>
          <h3>INSTALAÇÃO DE<br>REDE DE HIDRANTES</h3>
          <p>Montagem completa de redes de hidrantes e mangotinhos com dimensionamento técnico e equipe própria qualificada.</p>
        </div>
        <div class="featured-card-body">
          <ul class="featured-list">
            <li>Dimensionamento hidráulico por engenheiro habilitado</li>
            <li>Instalação de hidrantes de parede, coluna e mangotinhos</li>
            <li>Montagem de abrigos, mangueiras e esguichos certificados</li>
            <li>Instalação e automação de bombas de incêndio e painéis dedicados</li>
            <li>Testes de pressão e emissão de laudo de conformidade</li>
          </ul>
        </div>
      </div>

      <div class="featured-card">
        <div class="featured-card-top">
          <div class="featured-num">02</div>
          <div class="featured-icon">
            <svg viewBox="0 0 24 24"><line x1="2" y1="4" x2="22" y2="4"/><line x1="12" y1="4" x2="12" y2="9"/><circle cx="12" cy="11" r="2"/><path d="M8 17l-1 4M11 16v4M15 16v4M18 17l1 4"/></svg>
          </div>
          <h3>INSTALAÇÃO DE<br>REDE DE SPRINKLER</h3>
          <p>Instalação de sistemas de chuveiros automáticos (SPK) para proteção integral estrutural conforme normas vigentes.</p>
        </div>
        <div class="featured-card-body">
          <ul class="featured-list">
            <li>Dimensionamento e instalação de redes de sprinklers (SPK)</li>
            <li>Instalação de sprinklers pendente, oculto, lateral e de resposta rápida</li>
            <li>Tubulação, conexões e suportes dimensionados por engenharia</li>
            <li>Instalação de reservatórios, bombas e painéis de controle</li>
            <li>Testes hidrostáticos e relatório de conformidade</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- STRIP: Do Projeto ao Laudo Final -->
<section class="section-strip">
  <div class="page-wrap">
    <div style="margin-bottom:28px;">
      <div class="s-label">Nossas Especialidades</div>
      <h3 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(28px,3vw,38px);letter-spacing:1px;color:var(--ink);line-height:1;">DO PROJETO<br>AO LAUDO FINAL</h3>
    </div>
    <div class="strip-grid">

      <div class="strip-card">
        <div class="strip-icon">
          <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <h3>Elaboração de Projetos e Aprovação</h3>
        <p>Projetos técnicos completos elaborados por engenheiros habilitados e aprovados junto ao Corpo de Bombeiros.</p>
        <button class="strip-toggle" onclick="toggleStrip(this)">
          Ver detalhes <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="strip-detail">
          <li>Elaboração de projeto conforme ABNT e decretos do CBMERJ</li>
          <li>Aprovação e protocolo junto ao Corpo de Bombeiros</li>
          <li>Suporte completo para emissão e renovação do AVCB</li>
          <li>Consultoria técnica especializada durante todo o processo</li>
        </ul>
      </div>

      <div class="strip-card">
        <div class="strip-icon">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        </div>
        <h3>Sistema de Detecção de Incêndio</h3>
        <p>Instalação de sistemas automáticos de detecção e alarme para resposta imediata em situações de emergência.</p>
        <button class="strip-toggle" onclick="toggleStrip(this)">
          Ver detalhes <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="strip-detail">
          <li>Detectores de fumaça ópticos e iônicos</li>
          <li>Detectores de calor e chama</li>
          <li>Central de alarme de incêndio endereçável</li>
          <li>Acionadores manuais e sirenes de evacuação</li>
          <li>Iluminação de emergência e sinalização de saída</li>
        </ul>
      </div>

      <div class="strip-card">
        <div class="strip-icon">
          <svg viewBox="0 0 24 24"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        </div>
        <h3>Sistema de Para-raios</h3>
        <p>Dimensionamento e instalação de Sistemas de Proteção contra Descargas Atmosféricas para edificações comerciais e industriais.</p>
        <button class="strip-toggle" onclick="toggleStrip(this)">
          Ver detalhes <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="strip-detail">
          <li>Estudo e dimensionamento do Sistema de Para-raios por engenheiro habilitado</li>
          <li>Instalação de captores, descidas e sistema de aterramento</li>

          <li>Emissão de laudo técnico de conformidade</li>
          <li>Manutenção periódica e medição de resistência de terra</li>
        </ul>
      </div>

    </div>
  </div>
</section>

<!-- DIFFERENTIALS -->
<section class="section-dif">
  <div class="page-wrap">
    <div class="dif-grid">
      <div class="dif-img">
        <img src="{{ asset('img/fire out images/computador-imagem.png') }}" alt="Equipe Fire Out">
      </div>
      <div>
        <div class="s-label">Por que a Fire Out</div>
        <h2 class="s-title">QUALIDADE QUE<br>PROTEGE VIDAS</h2>
        <ul class="dif-list">
          <li><div class="dif-check"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>Empresa de engenharia de combate a incêndio com mais de 30 anos de tradição e mercado.</li>
          <li><div class="dif-check"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>Equipe técnica própria, treinada e especializada para instalações complexas.</li>
          <li><div class="dif-check"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>Atendimento abrangente para o Rio de Janeiro e múltiplos estados do Brasil.</li>
          <li><div class="dif-check"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>Execução de testes regulamentares com emissão de relatórios de conformidade.</li>
          <li><div class="dif-check"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>Projetos rigorosamente alinhados com as exigências técnicas da ABNT e decretos do Corpo de Bombeiros.</li>
        </ul>
        <a href="{{ route('contato') }}" class="btn-red">Solicitar Proposta</a>
      </div>
    </div>
  </div>
</section>

<!-- CLIENTS -->
<section class="section-clients-strip">
  <div class="page-wrap">
    <div class="clients-hdr">
      <div class="s-label" style="justify-content:center;">Clientes</div>
      <h2 class="s-title">QUEM CONFIA NA FIRE OUT</h2>
    </div>
    <div class="logo-strip">
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/hospital.png') }}"    alt="Hospital"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/hospital2.png') }}"   alt="Hospital Icaraí"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/amoedo-logo.png') }}" alt="Amoedo"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/distac-logo.png') }}" alt="Distac"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/unimed-logo.png') }}" alt="Unimed"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/frescatto-logo.png') }}" alt="Frescatto"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/FAP-logo.png') }}"    alt="FAP"></div>
      <div class="logo-strip-item"><img src="{{ asset('img/logo-cliente/redentor-logo.png') }}" alt="Redentor"></div>
    </div>
  </div>
</section>

<!-- CTA -->
<div style="max-width:1200px;margin:0 auto 120px;padding:0 48px;">
  <div class="cta-banner">
    <div>
      <h2>PRECISA DE UM PROJETO<br>OU INSTALAÇÃO?</h2>
      <p>Fale com nossa equipe técnica e receba uma proposta em até 24h.</p>
    </div>
    <a href="{{ route('contato') }}" class="btn-white">Solicitar Orçamento</a>
  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStrip(btn) {
  const card = btn.closest('.strip-card');
  card.classList.toggle('expanded');
  const txt = btn.firstChild;
  if (txt && txt.nodeType === 3) {
    txt.textContent = card.classList.contains('expanded') ? 'Ver menos ' : 'Ver detalhes ';
  }
}
</script>
@endpush
