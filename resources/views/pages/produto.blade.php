@extends('layouts.app')

@section('title', 'Fire Out — ' . $product['name'])
@section('description', $product['desc'])

@push('styles')
<style>
/* ── BREADCRUMB ── */
.pd-breadcrumb {
  margin-top:var(--nav-h); background:var(--ink);
  padding:14px 48px; border-bottom:1px solid rgba(255,255,255,.06);
}
.pd-breadcrumb-inner {
  max-width:1200px; margin:0 auto;
  display:flex; align-items:center; justify-content:space-between; gap:8px;
  font-size:12px; color:rgba(255,255,255,.35);
}
.pd-breadcrumb a { color:rgba(255,255,255,.35); text-decoration:none; }
.pd-breadcrumb a:hover { color:var(--red-l); }
.pd-breadcrumb-sep { opacity:.25; }

/* ── MAIN GRID ── */
.pd-wrap { max-width:1200px; margin:0 auto; padding:0 48px; }
.pd-section { padding:56px 0 80px; background:var(--off); }
.pd-grid { display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:start; }

/* ── GALLERY ── */
.pd-gallery { display:flex; flex-direction:column; gap:12px; position:sticky; top:calc(var(--nav-h) + 24px); }
.pd-img-main {
  background:#fff; border:1px solid rgba(0,0,0,.07); border-radius:var(--r-xl);
  display:flex; align-items:center; justify-content:center;
  padding:48px; aspect-ratio:1/1; overflow:hidden; position:relative;
}
.pd-img-main img { width:100%; height:100%; object-fit:contain; display:block; }
.pd-img-placeholder {
  display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px;
  color:rgba(0,0,0,.2);
}
.pd-img-placeholder svg { width:56px; height:56px; stroke:currentColor; fill:none; stroke-width:1; }
.pd-img-placeholder span { font-size:13px; font-weight:600; }
.pd-cert-tag {
  position:absolute; top:16px; right:16px;
  background:var(--red); color:#fff;
  font-size:10px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
  padding:6px 12px; border-radius:20px;
}
.pd-thumbs { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
.pd-thumb {
  background:#fff; border:1.5px solid rgba(0,0,0,.07); border-radius:var(--r-md);
  aspect-ratio:1/1; display:flex; align-items:center; justify-content:center;
  overflow:hidden; cursor:pointer; transition:border-color .15s;
}
.pd-thumb.active, .pd-thumb:hover { border-color:var(--red); }
.pd-thumb img { width:100%; height:100%; object-fit:contain; padding:8px; pointer-events:none; }

/* ── DETAILS ── */
.pd-cat {
  display:inline-flex; align-items:center; gap:7px;
  font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--red); margin-bottom:12px;
}
.pd-cat::before { content:''; width:18px; height:2px; background:var(--red); }
.pd-name {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(32px,4vw,48px); line-height:.95; letter-spacing:2px;
  color:var(--ink); margin-bottom:24px;
}
.pd-desc { font-size:15px; color:#555; line-height:1.8; margin-bottom:28px; }

/* variant pills */
.pd-variant-label { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); display:block; margin-bottom:10px; }
.pd-pills { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
.pd-pill {
  padding:8px 18px; border:1.5px solid rgba(0,0,0,.12); border-radius:8px;
  font-size:14px; font-weight:700; color:var(--ink);
  cursor:pointer; background:#fff; transition:all .15s;
  display:flex; flex-direction:column; align-items:center; line-height:1.3;
}
.pd-pill small { font-size:11px; font-weight:500; color:var(--warm); margin-top:2px; }
.pd-pill:hover { border-color:var(--red); color:var(--red); }
.pd-pill.active { background:var(--red); border-color:var(--red); color:#fff; }
.pd-pill.active small { color:rgba(255,255,255,.7); }

/* price */
.pd-price-block { margin-bottom:28px; }
.pd-price { font-size:32px; font-weight:800; color:var(--red); line-height:1; }
.pd-price-consulta { font-size:16px; font-weight:600; color:var(--warm); }

/* specs */
.pd-specs { list-style:none; margin-bottom:32px; border-top:1px solid rgba(0,0,0,.07); }
.pd-spec-row {
  display:flex; align-items:flex-start; gap:10px;
  padding:10px 0; border-bottom:1px solid rgba(0,0,0,.05); font-size:14px; color:var(--ink);
}
.pd-spec-row::before {
  content:''; width:5px; height:5px; border-radius:50%; background:var(--red);
  flex-shrink:0; margin-top:5px;
}

/* ── QUANTITY + ADD TO CART ── */
.pd-cart-row { display:flex; align-items:center; gap:12px; margin-bottom:28px; flex-wrap:wrap; }
.pd-qty-wrap {
  display:flex; align-items:center;
  border:1.5px solid rgba(0,0,0,.12); border-radius:8px; overflow:hidden; background:#fff;
}
.pd-qty-btn {
  width:38px; height:44px; font-size:18px; font-weight:600;
  background:none; border:none; cursor:pointer; color:var(--ink);
  display:flex; align-items:center; justify-content:center;
  transition:background .12s;
}
.pd-qty-btn:hover { background:rgba(0,0,0,.05); }
.pd-qty-input {
  width:52px; height:44px; text-align:center;
  border:none; border-left:1px solid rgba(0,0,0,.08); border-right:1px solid rgba(0,0,0,.08);
  font-size:15px; font-weight:700; font-family:inherit; color:var(--ink);
  outline:none; background:#fff;
  -moz-appearance:textfield; appearance:textfield;
}
.pd-qty-input::-webkit-outer-spin-button,
.pd-qty-input::-webkit-inner-spin-button {
  -webkit-appearance:none; appearance:none;
  margin:0;
}
.pd-add-btn {
  flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px;
  background:var(--ink); color:#fff;
  font-size:14px; font-weight:700; font-family:inherit;
  padding:0 24px; height:44px; border-radius:8px; border:none; cursor:pointer;
  transition:background .15s; white-space:nowrap;
}
.pd-add-btn:hover { background:#2d2d2d; }
.pd-add-btn.added { background:#16a34a; }
.pd-add-btn svg { width:15px; height:15px; stroke:#fff; fill:none; stroke-width:2.5; }

.pd-contact-btn {
  flex:1; display:inline-flex; align-items:center; justify-content:center; gap:9px;
  background:rgba(215,43,43,.06); border:1.5px solid rgba(215,43,43,.22);
  border-radius:8px; padding:0 24px; height:44px;
  font-size:14px; font-weight:700; color:var(--red);
  text-decoration:none; white-space:nowrap;
  transition:background .15s, border-color .15s;
}
.pd-contact-btn:hover { background:rgba(215,43,43,.12); border-color:rgba(215,43,43,.45); }
.pd-contact-btn svg { width:15px; height:15px; stroke:currentColor; fill:none; stroke-width:2.5; }

/* ── BACK LINK ── */
.pd-back {
  display:inline-flex; align-items:center; gap:6px;
  font-size:13px; font-weight:600; color:var(--red);
  text-decoration:none; padding-top:20px;
}
.pd-back svg { width:14px; height:14px; stroke:currentColor; fill:none; stroke-width:2.5; }
.pd-back:hover { opacity:.75; }

/* ── RELATED PRODUCTS ── */
.pd-related { padding:72px 0 96px; background:#fff; border-top:1px solid rgba(0,0,0,.06); }
.pd-related-hdr { display:flex; align-items:baseline; justify-content:space-between; margin-bottom:32px; }
.pd-related-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; }
.pd-rel-card {
  background:#fff; border:1.5px solid rgba(0,0,0,.07); border-radius:var(--r-lg);
  overflow:hidden; text-decoration:none; display:flex; flex-direction:column;
  transition:transform .2s, box-shadow .2s, border-color .2s;
}
.pd-rel-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,.09); border-color:var(--red); }
.pd-rel-img {
  height:140px; background:var(--off);
  display:flex; align-items:center; justify-content:center; padding:16px; overflow:hidden;
}
.pd-rel-img img { max-height:108px; max-width:100%; object-fit:contain; }
.pd-rel-placeholder { color:rgba(0,0,0,.18); }
.pd-rel-placeholder svg { width:32px; height:32px; stroke:currentColor; fill:none; }
.pd-rel-body { padding:14px 14px 16px; flex:1; display:flex; flex-direction:column; gap:4px; }
.pd-rel-cat  { font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--red); }
.pd-rel-name { font-size:13px; font-weight:700; color:var(--ink); line-height:1.3; flex:1; }
.pd-rel-price { font-size:14px; font-weight:800; color:var(--red); margin-top:6px; }

/* ── TOAST ── */
.pd-toast {
  position:fixed; bottom:80px; left:50%; transform:translateX(-50%) translateY(20px);
  background:var(--ink); color:#fff; font-size:13px; font-weight:600;
  padding:10px 22px; border-radius:20px; z-index:500;
  opacity:0; transition:opacity .25s, transform .25s; pointer-events:none;
  white-space:nowrap; box-shadow:0 4px 20px rgba(0,0,0,.25);
}
.pd-toast.show { opacity:1; transform:translateX(-50%) translateY(0); }

@media(max-width:1024px) { .pd-related-grid { grid-template-columns:repeat(4,1fr); } }
@media(max-width:900px) {
  .pd-grid { grid-template-columns:1fr; gap:40px; }
  .pd-gallery { position:static; }
  .pd-wrap { padding:0 24px; }
  .pd-breadcrumb { padding:14px 24px; }
  .pd-related-grid { grid-template-columns:repeat(3,1fr); }
  .pd-related { padding:48px 0 64px; }
}
@media(max-width:600px) {
  .pd-related-grid { grid-template-columns:repeat(2,1fr); }
  .pd-cart-row { flex-direction:column; align-items:stretch; }
  .pd-add-btn { height:48px; }
}

/* ── BREADCRUMB CART BUTTON ── */
.pd-bc-left { display:flex; align-items:center; gap:8px; flex:1; min-width:0; }
.pd-cart-btn {
  display:flex; align-items:center; gap:7px; flex-shrink:0;
  padding:7px 14px; border-radius:20px;
  background:rgba(255,255,255,.1); color:rgba(255,255,255,.85);
  font-size:12px; font-weight:700; cursor:pointer;
  border:1px solid rgba(255,255,255,.15); transition:background .15s; white-space:nowrap;
}
.pd-cart-btn:hover { background:rgba(255,255,255,.2); }
.pd-cart-btn svg { width:15px; height:15px; stroke:currentColor; fill:none; stroke-width:2; }
.pd-cart-count {
  min-width:18px; height:18px; border-radius:9px;
  background:var(--red); color:#fff;
  font-size:10px; font-weight:800; padding:0 5px;
  display:none; align-items:center; justify-content:center; line-height:1;
}
.pd-cart-count.show { display:flex; }

/* ── CART DRAWER ── */
.cart-backdrop {
  position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:390;
  opacity:0; pointer-events:none; transition:opacity .3s;
}
.cart-backdrop.open { opacity:1; pointer-events:all; }
.cart-drawer {
  position:fixed; top:0; right:0; height:100vh; width:380px; max-width:90vw;
  background:#fff; z-index:400; display:flex; flex-direction:column;
  box-shadow:-8px 0 40px rgba(0,0,0,.15);
  transform:translateX(100%); transition:transform .35s var(--ease);
}
.cart-drawer.open { transform:translateX(0); }
.cart-header {
  padding:22px 24px 18px; border-bottom:1px solid rgba(0,0,0,.07);
  display:flex; align-items:center; justify-content:space-between; flex-shrink:0;
}
.cart-title { font-family:'Bebas Neue',sans-serif; font-size:22px; letter-spacing:1px; color:var(--ink); }
.cart-close {
  width:32px; height:32px; border-radius:50%;
  background:rgba(0,0,0,.06); border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:background .15s;
}
.cart-close:hover { background:rgba(0,0,0,.12); }
.cart-close svg { width:14px; height:14px; stroke:var(--ink); fill:none; stroke-width:2.5; }
.cart-items-list { flex:1; overflow-y:auto; padding:16px 24px; }
.cart-empty-msg {
  text-align:center; padding:48px 24px; color:var(--warm); font-size:14px; line-height:1.7;
}
.cart-empty-msg svg { width:48px; height:48px; stroke:rgba(0,0,0,.15); fill:none; stroke-width:1.5; margin:0 auto 16px; display:block; }
.cart-item {
  display:flex; align-items:flex-start; gap:14px;
  padding:14px 0; border-bottom:1px solid rgba(0,0,0,.06);
}
.cart-item:last-child { border-bottom:none; }
.cart-item-info { flex:1; }
.cart-item-name { font-size:14px; font-weight:600; color:var(--ink); margin-bottom:4px; line-height:1.3; }
.cart-item-variant { font-size:12px; color:var(--warm); margin-bottom:8px; }
.cart-qty {
  display:inline-flex; align-items:center;
  border:1.5px solid rgba(0,0,0,.1); border-radius:6px; overflow:hidden;
}
.cart-qty button {
  width:26px; height:26px; font-size:16px; font-weight:600;
  color:var(--ink); background:none; border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:background .1s;
}
.cart-qty button:hover { background:rgba(0,0,0,.06); }
.cart-qty span { width:28px; text-align:center; font-size:13px; font-weight:700; border-left:1px solid rgba(0,0,0,.08); border-right:1px solid rgba(0,0,0,.08); line-height:26px; }
.cart-remove {
  width:28px; height:28px; border-radius:50%;
  background:none; border:none; cursor:pointer; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  color:var(--warm); font-size:18px; line-height:1;
  transition:color .15s, background .15s; margin-top:2px;
}
.cart-remove:hover { color:#e53e3e; background:rgba(229,62,62,.08); }
.cart-item-right { display:flex; flex-direction:column; align-items:flex-end; gap:2px; flex-shrink:0; min-width:80px; }
.cart-item-subtotal { font-size:13px; font-weight:700; color:var(--red); white-space:nowrap; }
.cart-item-unit { font-size:11px; color:var(--warm); white-space:nowrap; }
.cart-item-consulta { font-size:12px; color:var(--warm); font-style:italic; }
.cart-total-row { display:flex; justify-content:space-between; align-items:baseline; padding-top:10px; border-top:1.5px solid rgba(0,0,0,.08); margin-top:2px; }
.cart-total-label { font-size:13px; font-weight:600; color:var(--ink); }
.cart-total-amount { font-size:15px; font-weight:700; color:var(--red); }
.cart-footer { padding:20px 24px; border-top:1px solid rgba(0,0,0,.07); flex-shrink:0; }
.cart-summary { font-size:13px; color:var(--warm); margin-bottom:14px; }
.cart-summary strong { color:var(--ink); font-weight:700; }
.cart-send {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:#25D366; color:#fff; padding:13px; border-radius:10px; border:none; cursor:pointer;
  font-size:14px; font-weight:700; font-family:inherit; transition:background .15s; margin-bottom:8px;
}
.cart-send:hover { background:#1cb955; }
.cart-send svg { width:17px; height:17px; fill:#fff; flex-shrink:0; }
.cart-send:disabled { opacity:.45; cursor:not-allowed; }
.cart-checkout {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:var(--red); color:#fff; padding:13px; border-radius:10px; border:none; cursor:pointer;
  font-size:14px; font-weight:700; font-family:inherit; transition:background .15s; margin-bottom:8px;
}
.cart-checkout:hover { background:#b91c1c; }
.cart-checkout:disabled { opacity:.45; cursor:not-allowed; }
.cart-checkout svg { width:16px; height:16px; stroke:#fff; fill:none; stroke-width:2; flex-shrink:0; }
.cart-clear {
  width:100%; background:none; border:none; cursor:pointer;
  font-size:12px; font-weight:600; color:var(--warm); padding:6px; font-family:inherit; transition:color .15s;
}
.cart-clear:hover { color:#e53e3e; }
</style>
@endpush

@section('content')

<div class="pd-breadcrumb">
  <div class="pd-breadcrumb-inner">
    <div class="pd-bc-left">
      <a href="{{ route('home') }}">Home</a>
      <span class="pd-breadcrumb-sep">›</span>
      <a href="{{ route('produtos') }}">Produtos</a>
      <span class="pd-breadcrumb-sep">›</span>
      <span>{{ $product['name'] }}</span>
    </div>
    <button class="pd-cart-btn" onclick="openCartDrawer()">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
      Meu Carrinho
      <span class="pd-cart-count" id="cartCount"></span>
    </button>
  </div>
</div>

<section class="pd-section">
  <div class="pd-wrap">
    <div class="pd-grid">

      {{-- GALLERY --}}
      <div class="pd-gallery">
        <div class="pd-img-main">
          @if($product['image'])
            <img id="pd-main-img" src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
          @else
            <div class="pd-img-placeholder">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
          @endif
          @if(in_array($product['category'], ['Mangueiras','Sistema de Hidrantes']))
            <span class="pd-cert-tag">INMETRO · UL</span>
          @elseif($product['category'] === 'Portas e Proteção')
            <span class="pd-cert-tag">ABNT NBR 11742</span>
          @elseif($product['category'] === 'Extintores')
            <span class="pd-cert-tag">NBR 15808</span>
          @endif
        </div>
        @php
          $galleryImgs = !empty($product['images'])
            ? $product['images']
            : collect($product['variants'])->pluck('image')->filter()->unique()->values()->toArray();
        @endphp
        @if(count($galleryImgs) > 1)
          <div class="pd-thumbs">
            @foreach($galleryImgs as $gi => $gimg)
              <div class="pd-thumb {{ $gi === 0 ? 'active' : '' }}" data-src="{{ asset($gimg) }}" onclick="pdSetImg(this)">
                <img src="{{ asset($gimg) }}" alt="">
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- DETAILS --}}
      <div>
        <div class="pd-cat">{{ $product['category'] }}</div>
        <h1 class="pd-name">{{ $product['name'] }}</h1>
        <p class="pd-desc">{{ $product['desc'] }}</p>

        @php
          $variants    = $product['variants'];
          $hasVariants = count($variants) > 1;
          $first       = $variants[0];
        @endphp

        @if($hasVariants)
          <span class="pd-variant-label">Selecione a opção</span>
          <div class="pd-pills">
            @foreach($variants as $i => $v)
              <button class="pd-pill {{ $i === 0 ? 'active' : '' }}" data-vi="{{ $i }}" onclick="pdPick(this)">
                {{ $v['label'] }}
                @if(isset($v['price']) && $v['price'] !== null)
                  <small>R$ {{ number_format($v['price'], 2, ',', '.') }}</small>
                @endif
              </button>
            @endforeach
          </div>
        @endif

        <div class="pd-price-block">
          @if(isset($first['price']) && $first['price'] !== null)
            <div class="pd-price" id="pd-price">R$ {{ number_format($first['price'], 2, ',', '.') }}</div>
          @else
            <div class="pd-price-consulta" id="pd-price">Sob Consulta — solicite um orçamento</div>
          @endif
        </div>

        <ul class="pd-specs" id="pd-specs">
          @foreach($first['specs'] as $spec)
            <li class="pd-spec-row">{{ $spec }}</li>
          @endforeach
        </ul>

        {{-- Quantity + Add to Cart / Contact --}}
        <div id="pd-cart-row" class="pd-cart-row">
          @if(isset($first['price']) && $first['price'] !== null)
            <div class="pd-qty-wrap">
              <button class="pd-qty-btn" onclick="pdQty(-1)">−</button>
              <input class="pd-qty-input" id="pd-qty" type="number" value="1" min="1" max="999" readonly>
              <button class="pd-qty-btn" onclick="pdQty(+1)">+</button>
            </div>
            <button class="pd-add-btn" id="pd-add-btn" onclick="pdAddToCart()">
              <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
              Adicionar ao Carrinho
            </button>
          @else
            <a href="{{ route('contato') }}?mensagem={{ urlencode('Olá! Gostaria de solicitar um orçamento para ' . $product['name'] . '.') }}" class="pd-contact-btn">
              <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              Fale Conosco para Orçamento
            </a>
          @endif
        </div>

        <a href="{{ route('produtos') }}" class="pd-back">
          <svg viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
          Voltar ao catálogo
        </a>
      </div>

    </div>
  </div>
</section>

{{-- RELATED PRODUCTS --}}
@if($related->isNotEmpty())
<section class="pd-related">
  <div class="pd-wrap">
    <div class="pd-related-hdr">
      <div>
        <div class="s-label">Outros produtos</div>
        <h2 class="s-title" style="margin-bottom:0;">VOCÊ TAMBÉM<br>PODE GOSTAR</h2>
      </div>
      <a href="{{ route('produtos') }}" style="font-size:13px;font-weight:600;color:var(--red);text-decoration:none;">Ver todos →</a>
    </div>
    <div class="pd-related-grid">
      @foreach($related as $rel)
        <a href="{{ route('produto.show', $rel['sku']) }}" class="pd-rel-card">
          <div class="pd-rel-img">
            @if($rel['image'])
              <img src="{{ asset($rel['image']) }}" alt="{{ $rel['name'] }}">
            @else
              <div class="pd-rel-placeholder"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
            @endif
          </div>
          <div class="pd-rel-body">
            <div class="pd-rel-cat">{{ $rel['category'] }}</div>
            <div class="pd-rel-name">{{ $rel['name'] }}</div>
            @if($rel['price'])
              <div class="pd-rel-price">R$ {{ number_format($rel['price'], 2, ',', '.') }}</div>
            @else
              <div class="pd-rel-price" style="color:var(--warm);font-size:12px;">Sob Consulta</div>
            @endif
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CART DRAWER -->
<div class="cart-backdrop" id="cartBackdrop" onclick="closeCartDrawer()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-header">
    <span class="cart-title">MEU ORÇAMENTO</span>
    <button class="cart-close" onclick="closeCartDrawer()"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
  </div>
  <div class="cart-items-list" id="cartItemsList"></div>
  <div class="cart-footer">
    <p class="cart-summary" id="cartSummary">0 itens no orçamento</p>
    <button class="cart-checkout" id="cartCheckoutBtn" onclick="cartCheckout()" disabled>
      <svg viewBox="0 0 24 24"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
      Finalizar Pedido
    </button>
    <button class="cart-send" id="cartSendBtn" onclick="cartSendWA()" disabled>
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
      Enviar via WhatsApp
    </button>
    <button class="cart-clear" onclick="cartClear()">Limpar lista</button>
  </div>
</div>

<form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" style="display:none">
  @csrf
  <input type="hidden" name="cart" id="checkoutCartInput">
</form>

<div class="pd-toast" id="pdToast"></div>

@push('scripts')
<script>
const pdVariants  = @json($variants);
const pdSku       = @json($sku);
const pdName      = @json($product['name']);
let   pdActiveVi  = 0;

const _pdContatoUrl = '{{ route("contato") }}';

function pdUpdateAction(price) {
  const row = document.getElementById('pd-cart-row');
  if (!row) return;
  if (price !== null && price !== undefined) {
    row.innerHTML =
      '<div class="pd-qty-wrap">' +
        '<button class="pd-qty-btn" onclick="pdQty(-1)">−</button>' +
        '<input class="pd-qty-input" id="pd-qty" type="number" value="1" min="1" max="999" readonly>' +
        '<button class="pd-qty-btn" onclick="pdQty(+1)">+</button>' +
      '</div>' +
      '<button class="pd-add-btn" id="pd-add-btn" onclick="pdAddToCart()">' +
        '<svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>' +
        'Adicionar ao Carrinho' +
      '</button>';
  } else {
    const msg = encodeURIComponent('Olá! Gostaria de solicitar um orçamento para ' + pdName + '.');
    row.innerHTML =
      '<a href="' + _pdContatoUrl + '?mensagem=' + msg + '" class="pd-contact-btn">' +
        '<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>' +
        'Fale Conosco para Orçamento' +
      '</a>';
  }
}

function pdPick(btn) {
  document.querySelectorAll('.pd-pill').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  pdActiveVi = +btn.dataset.vi;
  const v = pdVariants[pdActiveVi];
  const priceEl = document.getElementById('pd-price');
  if (v.price !== null && v.price !== undefined) {
    priceEl.className = 'pd-price';
    priceEl.textContent = 'R$ ' + Number(v.price).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  } else {
    priceEl.className = 'pd-price-consulta';
    priceEl.textContent = 'Sob Consulta — solicite um orçamento';
  }
  document.getElementById('pd-specs').innerHTML = (v.specs || []).map(s => `<li class="pd-spec-row">${s}</li>`).join('');
  if (v.image) {
    const img = document.getElementById('pd-main-img');
    if (img) img.src = '{{ asset("") }}' + v.image;
  }
  pdUpdateAction(v.price);
}

function pdQty(delta) {
  const input = document.getElementById('pd-qty');
  input.value = Math.max(1, (+input.value || 1) + delta);
}

function pdAddToCart() {
  const qty = Math.max(1, +document.getElementById('pd-qty').value || 1);
  const v   = pdVariants[pdActiveVi] || pdVariants[0];
  const key = pdSku + '_' + pdActiveVi;
  const cart = JSON.parse(localStorage.getItem('fireout_cart') || '[]');
  const existing = cart.find(i => i.key === key);
  if (existing) {
    existing.qty += qty;
  } else {
    cart.push({ key, prodId: pdSku, vi: pdActiveVi, name: pdName, variant: v.label || null, price: v.price ?? null, qty });
  }
  localStorage.setItem('fireout_cart', JSON.stringify(cart));

  const btn  = document.getElementById('pd-add-btn');
  const orig = btn.innerHTML;
  btn.classList.add('added');
  btn.innerHTML = '<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" stroke="#fff" fill="none" stroke-width="2.5"/></svg>Adicionado!';
  setTimeout(() => { btn.classList.remove('added'); btn.innerHTML = orig; }, 1600);

  cartUpdateUI();
  openCartDrawer();
}

/* ── CART DRAWER ── */
const cartItems = JSON.parse(localStorage.getItem('fireout_cart') || '[]');
function cartSave() { localStorage.setItem('fireout_cart', JSON.stringify(cartItems)); }

function fmtBRL(n) {
  return 'R$ ' + n.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function cartQtyChange(key, delta) {
  const item = cartItems.find(i => i.key === key);
  if (!item) return;
  item.qty = Math.max(1, item.qty + delta);
  cartSave();
  cartUpdateUI();
}

function cartRemove(key) {
  const idx = cartItems.findIndex(i => i.key === key);
  if (idx > -1) cartItems.splice(idx, 1);
  cartSave();
  cartUpdateUI();
}

function cartClear() {
  cartItems.length = 0;
  cartSave();
  cartUpdateUI();
}

function cartUpdateUI() {
  const stored = JSON.parse(localStorage.getItem('fireout_cart') || '[]');
  cartItems.length = 0;
  stored.forEach(i => cartItems.push(i));

  const total = cartItems.reduce((s, i) => s + i.qty, 0);
  const countEl = document.getElementById('cartCount');
  if (countEl) { countEl.textContent = total; countEl.classList.toggle('show', total > 0); }

  const priceTotal = cartItems.reduce((s, i) => i.price !== null ? s + i.price * i.qty : s, 0);
  const hasConsulta = cartItems.some(i => i.price === null);
  const allConsulta = cartItems.every(i => i.price === null);

  const summary = document.getElementById('cartSummary');
  if (summary) {
    let html = `<strong>${total}</strong> ${total === 1 ? 'item' : 'itens'} no orçamento`;
    if (cartItems.length > 0) {
      html += `<div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-amount">${allConsulta ? 'Sob Consulta' : fmtBRL(priceTotal)}</span></div>`;
      if (!allConsulta && hasConsulta) html += `<div style="font-size:11px;color:var(--warm);margin-top:3px;">* Alguns itens são Sob Consulta</div>`;
    }
    summary.innerHTML = html;
  }

  const sendBtn     = document.getElementById('cartSendBtn');
  const checkoutBtn = document.getElementById('cartCheckoutBtn');
  if (sendBtn)     sendBtn.disabled     = total === 0;
  if (checkoutBtn) checkoutBtn.disabled = total === 0;

  const list = document.getElementById('cartItemsList');
  if (!list) return;
  if (cartItems.length === 0) {
    list.innerHTML = `<div class="cart-empty-msg"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Nenhum item ainda.<br>Adicione produtos para montar seu orçamento.</div>`;
    return;
  }
  list.innerHTML = cartItems.map(item => {
    const unitStr = item.price !== null ? fmtBRL(item.price) + '/un' : 'Sob consulta';
    const subStr  = item.price !== null ? fmtBRL(item.price * item.qty) : '';
    return `
    <div class="cart-item">
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        ${item.variant ? `<div class="cart-item-variant">${item.variant}</div>` : ''}
        <div class="cart-qty">
          <button onclick="cartQtyChange('${item.key}',-1)">−</button>
          <span>${item.qty}</span>
          <button onclick="cartQtyChange('${item.key}',+1)">+</button>
        </div>
      </div>
      <div class="cart-item-right">
        ${subStr ? `<div class="cart-item-subtotal">${subStr}</div>` : `<div class="cart-item-consulta">Sob consulta</div>`}
        <div class="cart-item-unit">${unitStr}</div>
        <button class="cart-remove" onclick="cartRemove('${item.key}')">×</button>
      </div>
    </div>`;
  }).join('');
}

function cartSendWA() {
  if (cartItems.length === 0) return;
  let lines = 'Olá! Gostaria de solicitar um orçamento:\n\n';
  let runningTotal = 0, hasConsulta = false;
  cartItems.forEach(item => {
    const varStr   = item.variant ? ' — ' + item.variant : '';
    const qtyStr   = item.qty > 1 ? ' (x' + item.qty + ')' : '';
    const priceStr = item.price !== null ? ' — ' + fmtBRL(item.price * item.qty) : ' — Sob Consulta';
    if (item.price !== null) runningTotal += item.price * item.qty; else hasConsulta = true;
    lines += '• ' + item.name + varStr + qtyStr + priceStr + '\n';
  });
  lines += '\n';
  if (runningTotal > 0 && hasConsulta) lines += 'Subtotal (itens c/ preço fixo): ' + fmtBRL(runningTotal) + '\n(+ itens Sob Consulta)\n';
  else if (runningTotal > 0)           lines += 'Total: ' + fmtBRL(runningTotal) + '\n';
  else                                  lines += 'Todos os itens são Sob Consulta.\n';
  lines += '\nAguardo retorno. Obrigado!';
  window.open('https://wa.me/5521966969836?text=' + encodeURIComponent(lines), '_blank');
}

function cartCheckout() {
  if (cartItems.length === 0) return;
  document.getElementById('checkoutCartInput').value = JSON.stringify(cartItems);
  document.getElementById('checkoutForm').submit();
}

function openCartDrawer() {
  document.getElementById('cartDrawer').classList.add('open');
  document.getElementById('cartBackdrop').classList.add('open');
  document.body.style.overflow = 'hidden';
  cartUpdateUI();
}

function closeCartDrawer() {
  document.getElementById('cartDrawer').classList.remove('open');
  document.getElementById('cartBackdrop').classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCartDrawer(); });

cartUpdateUI();

function pdSetImg(thumb) {
  document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
  const img = document.getElementById('pd-main-img');
  if (img) img.src = thumb.dataset.src;
}
</script>
@endpush
@endsection
