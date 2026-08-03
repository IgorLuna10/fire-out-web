@extends('layouts.app')

@section('title', 'Fire Out — Catálogo de Produtos')
@section('description', 'Equipamentos certificados para sistemas de combate a incêndio: hidrantes, extintores, sprinklers, portas corta-fogo e mais.')

@push('styles')
<style>
/* ── SHOP HEADER ── */
.shop-header {
  margin-top:var(--nav-h); background:var(--off);
  padding:28px 48px 24px; border-bottom:1px solid rgba(0,0,0,.07);
}
.shop-header-inner { max-width:1200px; margin:0 auto; }
.shop-breadcrumb {
  font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--warm); margin-bottom:6px;
  display:flex; align-items:center; gap:6px;
}
.shop-breadcrumb::before { content:''; width:16px; height:1.5px; background:var(--warm); }
.shop-title {
  font-size:22px; font-weight:700; color:var(--ink); letter-spacing:-.3px; line-height:1.2;
}

/* ── CATEGORY TILES ── */
.cat-tiles-wrap { background:#fff; border-bottom:1px solid rgba(0,0,0,.07); }
.cat-tiles {
  max-width:1200px; margin:0 auto; padding:0 48px;
  display:grid; grid-template-columns:repeat(5,1fr);
}
.cat-tile {
  display:flex; flex-direction:column; align-items:center; gap:8px;
  padding:20px 12px; text-align:center;
  border-right:1px solid rgba(0,0,0,.06);
  border-bottom:3px solid transparent;
  cursor:pointer; background:none; border-top:none; border-left:none;
  font-family:inherit; transition:background .15s, border-color .15s;
}
.cat-tile:last-child { border-right:none; }
.cat-tile:hover { background:rgba(0,0,0,.02); }
.cat-tile.active { border-bottom-color:var(--red); background:rgba(215,43,43,.025); }

.cat-tile-icon {
  width:46px; height:46px; background:rgba(0,0,0,.05);
  border-radius:var(--r-md);
  display:flex; align-items:center; justify-content:center;
  transition:background .15s;
}
.cat-tile:hover .cat-tile-icon { background:rgba(215,43,43,.08); }
.cat-tile.active .cat-tile-icon { background:rgba(215,43,43,.1); }
.cat-tile-icon svg { width:22px; height:22px; stroke:var(--warm); fill:none; stroke-width:1.5; transition:stroke .15s; }
.cat-tile:hover .cat-tile-icon svg,
.cat-tile.active .cat-tile-icon svg { stroke:var(--red); }

.cat-tile-name { font-size:12px; font-weight:700; color:var(--ink); line-height:1.3; }
.cat-tile-count { font-size:11px; color:var(--warm); }

/* ── TRUST BAR (removed from this page, kept in CSS for other pages) ── */
.trust-item {
  display:flex; align-items:center; gap:9px;
  padding:14px 20px; white-space:nowrap; flex-shrink:0;
  border-right:1px solid rgba(0,0,0,.06);
  font-size:12px; font-weight:600; color:var(--ink);
}
.trust-item:last-child { border-right:none; }
.trust-item svg { width:16px; height:16px; flex-shrink:0; stroke:var(--red); fill:none; stroke-width:2; }

/* ── STICKY FILTER BAR ── */
.shop-bar {
  background:rgba(245,242,237,.97); backdrop-filter:blur(12px);
  border-bottom:1px solid rgba(0,0,0,.07);
  position:sticky; top:var(--nav-h); z-index:100;
}
.shop-bar-inner {
  max-width:1200px; margin:0 auto; padding:0 48px;
  display:flex; align-items:center; justify-content:space-between;
  gap:20px; height:52px;
}
.shop-total { font-size:13px; color:var(--warm); white-space:nowrap; }

/* ── MINI CART BUTTON in bar ── */
.bar-cart-btn {
  display:flex; align-items:center; gap:7px;
  padding:7px 14px; border-radius:20px;
  background:var(--ink); color:#fff;
  font-size:12px; font-weight:700; cursor:pointer;
  border:none; transition:background .15s; white-space:nowrap; position:relative;
}
.bar-cart-btn:hover { background:#2d2d2d; }
.bar-cart-btn svg { width:15px; height:15px; stroke:#fff; fill:none; stroke-width:2; }
.bar-cart-count {
  min-width:18px; height:18px; border-radius:9px;
  background:var(--red); color:#fff;
  font-size:10px; font-weight:800; padding:0 5px;
  display:none; align-items:center; justify-content:center;
  line-height:1;
}
.bar-cart-count.show { display:flex; }

/* ── SHOP BODY ── */
.shop-body { max-width:1200px; margin:0 auto; padding:52px 48px 140px; }
.shop-section { margin-bottom:68px; }
.shop-section-hdr {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:24px; padding-bottom:14px; border-bottom:1.5px solid rgba(0,0,0,.07);
}
.shop-sec-left { display:flex; align-items:center; gap:12px; }
.shop-sec-title { font-family:'Bebas Neue',sans-serif; font-size:19px; letter-spacing:1.5px; color:var(--ink); }
.shop-sec-link { font-size:13px; font-weight:600; color:var(--red); display:flex; align-items:center; gap:4px; }
.shop-sec-link svg { width:13px; height:13px; stroke:currentColor; fill:none; stroke-width:2.5; }

/* ── PRODUCT GRID ── */
.prod-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }

/* ── PRODUCT CARD ── */
.prod-card {
  background:#fff; border-radius:16px; overflow:hidden;
  border:1.5px solid rgba(0,0,0,.07); display:flex; flex-direction:column;
  transition:transform .25s var(--ease), box-shadow .25s var(--ease), border-color .2s;
}
.prod-card[data-href]:hover { transform:translateY(-4px); box-shadow:0 20px 52px rgba(0,0,0,.1); border-color:var(--red); }

/* image area */
.pcard-img {
  height:210px; background:#f7f5f2;
  display:flex; align-items:center; justify-content:center;
  padding:24px; position:relative; overflow:hidden;
}
.pcard-img img {
  max-height:158px; max-width:100%; object-fit:contain;
  transition:transform .3s var(--ease);
}
.prod-card:hover .pcard-img img { transform:scale(1.06); }

.pcard-cat {
  position:absolute; top:12px; left:12px; z-index:2;
  font-size:9px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase;
  background:var(--ink); color:#fff; padding:4px 9px; border-radius:4px;
}
/* bestseller ribbon */
.pcard-hot {
  position:absolute; bottom:0; left:0; right:0; z-index:2;
  background:linear-gradient(90deg,#D97706,#F59E0B);
  color:#fff; font-size:9px; font-weight:800; letter-spacing:1.5px;
  text-transform:uppercase; text-align:center; padding:5px 0;
}
.pcard-peek {
  position:absolute; bottom:36px; right:10px; z-index:2;
  width:30px; height:30px; border-radius:50%;
  background:rgba(255,255,255,.9); border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  opacity:0; transform:translateY(6px);
  transition:opacity .2s, transform .2s; box-shadow:0 2px 8px rgba(0,0,0,.12);
}
.pcard-img:has(.pcard-hot) .pcard-peek { bottom:46px; }
.prod-card:hover .pcard-peek { opacity:1; transform:translateY(0); }
.pcard-peek svg { width:14px; height:14px; stroke:var(--ink); fill:none; stroke-width:2; }

/* body */
.pcard-body { padding:18px 20px 20px; display:flex; flex-direction:column; flex:1; }
.pcard-name { font-size:15px; font-weight:700; color:var(--ink); margin-bottom:6px; line-height:1.3; }
.pcard-desc { font-size:12px; color:var(--warm); line-height:1.55; margin-bottom:10px; }

/* spec pills */
.pcard-specs { display:flex; flex-wrap:wrap; gap:5px; flex:1; align-content:flex-start; }
.pcard-spec {
  font-size:10px; font-weight:600; letter-spacing:.2px;
  background:rgba(0,0,0,.04); border:1px solid rgba(0,0,0,.08);
  border-radius:4px; padding:3px 8px; color:var(--ink); white-space:nowrap;
}

/* card footer — price + actions */
.pcard-footer {
  margin-top:14px; padding-top:14px;
  border-top:1px solid rgba(0,0,0,.07);
}

/* variant pills */
.pcard-variants { margin-bottom:14px; }
.pcard-vlabel { font-size:10px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:var(--warm); display:block; margin-bottom:7px; }
.vpills { display:flex; gap:6px; flex-wrap:wrap; }
.vpill {
  padding:5px 12px; border:1.5px solid rgba(0,0,0,.1); border-radius:6px;
  font-size:12px; font-weight:700; color:var(--ink);
  cursor:pointer; background:none; transition:all .15s;
  display:flex; flex-direction:column; align-items:center; line-height:1.2;
}
.vpill small { font-size:10px; font-weight:500; color:var(--warm); }
.vpill:hover { border-color:var(--red); color:var(--red); }
.vpill:hover small { color:var(--red); opacity:.7; }
.vpill.active { background:var(--red); border-color:var(--red); color:#fff; }
.vpill.active small { color:rgba(255,255,255,.75); }

/* price */
.pcard-price {
  font-size:20px; font-weight:800; color:var(--red);
  line-height:1; margin-bottom:10px;
}
.pcard-price.consulta {
  font-size:12px; font-weight:600; color:var(--warm); margin-bottom:10px;
}

/* actions */
.pcard-actions { display:flex; gap:6px; }
.pcard-add {
  flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px;
  background:var(--ink); color:#fff;
  font-size:12px; font-weight:700; padding:9px 10px; border-radius:8px;
  border:none; cursor:pointer; transition:background .15s;
  white-space:nowrap;
}
.pcard-add:hover { background:#2d2d2d; }
.pcard-add.added { background:#16a34a; }
.pcard-add svg { width:13px; height:13px; stroke:#fff; fill:none; stroke-width:2.5; flex-shrink:0; }

.pcard-contact {
  flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px;
  background:rgba(215,43,43,.06); border:1.5px solid rgba(215,43,43,.22);
  border-radius:8px; padding:9px 10px;
  font-size:12px; font-weight:700; color:var(--red);
  text-decoration:none; white-space:nowrap;
  transition:background .15s, border-color .15s;
}
.pcard-contact:hover { background:rgba(215,43,43,.12); border-color:rgba(215,43,43,.45); }
.pcard-contact svg { width:13px; height:13px; stroke:currentColor; fill:none; stroke-width:2.5; flex-shrink:0; }

.pcard-detail {
  width:36px; height:36px; border-radius:8px; flex-shrink:0;
  border:1.5px solid rgba(0,0,0,.1); background:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:border-color .15s, background .15s;
}
.pcard-detail:hover { border-color:var(--ink); background:var(--ink); }
.pcard-detail:hover svg { stroke:#fff; }
.pcard-detail svg { width:14px; height:14px; stroke:var(--warm); fill:none; stroke-width:2; }

/* special porta CF card */
.pcard-pcf-cta {
  display:flex; align-items:center; justify-content:space-between;
  background:rgba(215,43,43,.05); border:1px solid rgba(215,43,43,.15);
  border-radius:8px; padding:10px 14px;
  font-size:12px; font-weight:700; color:var(--red); margin-top:auto;
  text-decoration:none; transition:background .15s;
}
.pcard-pcf-cta:hover { background:rgba(215,43,43,.1); }
.pcard-pcf-cta svg { width:14px; height:14px; stroke:currentColor; fill:none; stroke-width:2.5; }

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
  display:flex; align-items:center; justify-content:center;
  transition:background .15s;
}
.cart-close:hover { background:rgba(0,0,0,.12); }
.cart-close svg { width:14px; height:14px; stroke:var(--ink); fill:none; stroke-width:2.5; }

.cart-items-list { flex:1; overflow-y:auto; padding:16px 24px; }
.cart-empty-msg {
  text-align:center; padding:48px 24px;
  color:var(--warm); font-size:14px; line-height:1.7;
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
  display:inline-flex; align-items:center; gap:0;
  border:1.5px solid rgba(0,0,0,.1); border-radius:6px; overflow:hidden;
}
.cart-qty button {
  width:26px; height:26px; font-size:16px; font-weight:600;
  color:var(--ink); background:none; border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:background .1s;
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
.cart-consulta-note { font-size:11px; color:var(--warm); margin-top:3px; }

.cart-footer {
  padding:20px 24px; border-top:1px solid rgba(0,0,0,.07); flex-shrink:0;
}
.cart-summary { font-size:13px; color:var(--warm); margin-bottom:14px; }
.cart-summary strong { color:var(--ink); font-weight:700; }
.cart-send {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:#25D366; color:#fff;
  padding:13px; border-radius:10px; border:none; cursor:pointer;
  font-size:14px; font-weight:700; font-family:inherit;
  transition:background .15s; margin-bottom:8px;
}
.cart-send:hover { background:#1cb955; }
.cart-send svg { width:17px; height:17px; fill:#fff; flex-shrink:0; }
.cart-send:disabled { opacity:.45; cursor:not-allowed; }
.cart-send-email {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:var(--ink); color:#fff;
  padding:13px; border-radius:10px; border:none; cursor:pointer;
  font-size:14px; font-weight:700; font-family:inherit;
  transition:background .15s; margin-bottom:10px;
}
.cart-send-email:hover { background:#2d2d2d; }
.cart-send-email svg { width:16px; height:16px; stroke:#fff; fill:none; stroke-width:2; flex-shrink:0; }
.cart-send-email:disabled { opacity:.45; cursor:not-allowed; }
.cart-checkout {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:var(--red); color:#fff;
  padding:13px; border-radius:10px; border:none; cursor:pointer;
  font-size:14px; font-weight:700; font-family:inherit;
  transition:background .15s; margin-bottom:8px;
}
.cart-checkout:hover { background:#b91c1c; }
.cart-checkout:disabled { opacity:.45; cursor:not-allowed; }
.cart-checkout svg { width:16px; height:16px; stroke:#fff; fill:none; stroke-width:2; flex-shrink:0; }
.cart-clear {
  width:100%; background:none; border:none; cursor:pointer;
  font-size:12px; font-weight:600; color:var(--warm);
  padding:6px; font-family:inherit; transition:color .15s;
}
.cart-clear:hover { color:#e53e3e; }

/* toast notification */
.cart-toast {
  position:fixed; bottom:100px; left:50%; transform:translateX(-50%) translateY(20px);
  background:var(--ink); color:#fff; font-size:13px; font-weight:600;
  padding:10px 20px; border-radius:20px; z-index:500;
  opacity:0; transition:opacity .25s, transform .25s; pointer-events:none;
  white-space:nowrap; box-shadow:0 4px 20px rgba(0,0,0,.25);
}
.cart-toast.show { opacity:1; transform:translateX(-50%) translateY(0); }

/* ── MODAL ── */
.modal-overlay {
  position:fixed; inset:0; background:rgba(10,10,10,.65);
  backdrop-filter:blur(6px); z-index:500;
  display:flex; align-items:center; justify-content:center; padding:24px;
  opacity:0; pointer-events:none; transition:opacity .3s;
}
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal {
  background:#fff; border-radius:20px;
  width:100%; max-width:860px; max-height:90vh; overflow:hidden;
  display:grid; grid-template-columns:1fr 1fr;
  transform:translateY(16px) scale(.97);
  transition:transform .3s var(--ease); position:relative;
}
.modal-overlay.open .modal { transform:none; }
.modal-img-side { background:#f7f5f2; display:flex; align-items:center; justify-content:center; padding:48px 40px; }
.modal-img-side img { max-height:320px; max-width:100%; object-fit:contain; }
.modal-info { padding:44px 44px; overflow-y:auto; display:flex; flex-direction:column; }
.modal-close {
  position:absolute; top:16px; right:16px;
  width:34px; height:34px; border-radius:50%;
  background:rgba(0,0,0,.07); border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:background .15s; z-index:10;
}
.modal-close:hover { background:rgba(0,0,0,.14); }
.modal-close svg { width:15px; height:15px; stroke:var(--ink); fill:none; stroke-width:2.5; }
.modal-badge { font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--red); margin-bottom:12px; }
.modal-title { font-family:'Bebas Neue',sans-serif; font-size:clamp(28px,3vw,38px); letter-spacing:1px; line-height:1; color:var(--ink); margin-bottom:18px; }
.modal-desc { font-size:14px; color:#555; line-height:1.8; margin-bottom:24px; }
.modal-specs { list-style:none; margin-bottom:28px; display:flex; flex-direction:column; gap:8px; }
.modal-specs li { display:flex; align-items:flex-start; gap:10px; font-size:13px; color:var(--ink); line-height:1.5; }
.modal-specs li::before { content:''; width:5px; height:5px; border-radius:50%; background:var(--red); flex-shrink:0; margin-top:5px; }
.modal-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:auto; }
.btn-modal-wa {
  flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px;
  background:#25D366; color:#fff; padding:13px 18px; border-radius:10px;
  font-size:14px; font-weight:700; transition:background .15s;
}
.btn-modal-wa:hover { background:#1cb955; }
.btn-modal-wa svg { width:16px; height:16px; fill:#fff; }
.btn-modal-add {
  display:inline-flex; align-items:center; justify-content:center; gap:7px;
  background:var(--ink); color:#fff; padding:13px 18px; border-radius:10px;
  font-size:14px; font-weight:600; border:none; cursor:pointer; transition:background .15s;
}
.btn-modal-add:hover { background:#2d2d2d; }
.btn-modal-add svg { width:15px; height:15px; stroke:#fff; fill:none; stroke-width:2; }

@media(max-width:860px) {
  .modal { grid-template-columns:1fr; }
  .modal-img-side { height:200px; padding:24px; }
}
@media(max-width:960px) {
  .prod-grid { grid-template-columns:1fr 1fr; }
  .shop-body  { padding:40px 24px 80px; }
  .shop-bar-inner { padding:0 24px; }
  .shop-header { padding:20px 24px; }
  .trust-bar  { padding:0 24px; }
  .cat-tiles  { padding:0 24px; grid-template-columns:repeat(3,1fr); }
  .cat-tile   { padding:16px 8px; }
  .cat-tile-name { font-size:11px; }
  .cart-drawer { width:100%; max-width:100%; }
}
@media(max-width:600px) {
  .prod-grid { grid-template-columns:1fr; }
  .cat-tiles { grid-template-columns:repeat(3,1fr); }
  .trust-item { padding:12px 14px; font-size:11px; }
}

/* ── VIEW TOGGLE ── */
.view-btns { display:flex; gap:5px; }
.view-btn {
  width:34px; height:34px; border-radius:8px; border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  background:rgba(0,0,0,.07); transition:background .15s;
}
.view-btn:hover:not(.active) { background:rgba(0,0,0,.13); }
.view-btn.active { background:var(--ink); }
.view-btn svg { width:16px; height:16px; fill:none; stroke:var(--warm); stroke-width:1.8; }
.view-btn.active svg { stroke:#fff; }

/* ── LIST VIEW ── */
.shop-body.list-view .prod-grid { grid-template-columns:1fr; gap:10px; }
.shop-body.list-view .prod-card { flex-direction:row; }
.shop-body.list-view .pcard-img { width:190px; height:auto; min-height:140px; flex-shrink:0; }
.shop-body.list-view .pcard-peek { display:none; }
.shop-body.list-view .pcard-body { flex-direction:row; align-items:center; flex-wrap:nowrap; gap:20px; padding:16px 22px; }
.shop-body.list-view .pcard-name { min-width:160px; margin-bottom:0; font-size:15px; }
.shop-body.list-view .pcard-desc { flex:1; }
.shop-body.list-view .pcard-variants { display:none; }
.shop-body.list-view .pcard-footer { margin-top:0; padding-top:0; border-top:none; display:flex; align-items:center; gap:12px; flex-shrink:0; }
.shop-body.list-view .pcard-price { font-size:16px; margin-bottom:0; white-space:nowrap; }
.shop-body.list-view .pcard-actions { margin-top:0; }
.shop-body.list-view .pcard-pcf-cta { margin-top:0; }
@media(max-width:700px) {
  .shop-body.list-view .prod-card { flex-direction:column; }
  .shop-body.list-view .pcard-img { width:100%; min-height:180px; }
  .shop-body.list-view .pcard-body { flex-wrap:wrap; }
}
</style>
@endpush

@section('content')
<!-- SHOP HEADER -->
<div class="shop-header">
  <div class="shop-header-inner">
    <div class="shop-breadcrumb">Catálogo</div>
    <h1 class="shop-title">Equipamentos de Combate a Incêndio</h1>
  </div>
</div>

<!-- CATEGORY TILES -->
<div class="cat-tiles-wrap">
  <div class="cat-tiles">
    <button class="cat-tile active" data-cat="all" onclick="filterCat('all',this)">
      <div class="cat-tile-icon">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </div>
      <span class="cat-tile-name">Todos</span>
      <span class="cat-tile-count">13 produtos</span>
    
    <button class="cat-tile" data-cat="mangueiras" onclick="filterCat('mangueiras',this)">
      <div class="cat-tile-icon">
        <svg viewBox="0 0 24 24"><path d="M12 2v6m0 0c-3 0-5 2-5 5s2 5 5 5 5-2 5-5-2-5-5-5zM8 22h8"/></svg>
      </div>
      <span class="cat-tile-name">Mangueiras e Hidrantes</span>
      <span class="cat-tile-count">6 produtos</span>
    
    <button class="cat-tile" data-cat="extintores" onclick="filterCat('extintores',this)">
      <div class="cat-tile-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 2h6l1 4H8L9 2z"/><rect x="7" y="6" width="10" height="14" rx="2"/><path d="M12 10v4M10 12h4"/></svg>
      </div>
      <span class="cat-tile-name">Extintores e Suportes</span>
      <span class="cat-tile-count">2 produtos</span>
    
    <button class="cat-tile" data-cat="protecao" onclick="filterCat('protecao',this)">
      <div class="cat-tile-icon">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
      <span class="cat-tile-name">Portas Corta-Fogo</span>
      <span class="cat-tile-count">3 produtos</span>
    
    <button class="cat-tile" data-cat="abracadeiras" onclick="filterCat('abracadeiras',this)">
      <div class="cat-tile-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M3 12h4M17 12h4M12 3v4M12 17v4"/></svg>
      </div>
      <span class="cat-tile-name">Abraçadeiras e Suportes</span>
      <span class="cat-tile-count">{{ $abracadeiras->count() }} tamanhos</span>
    
  </div>
</div>

<!-- STICKY BAR — cart only -->
<div class="shop-bar">
  <div class="shop-bar-inner">
    <div class="view-btns">
      <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="Vista em grade">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      
      <button class="view-btn" id="btnList" onclick="setView('list')" title="Vista em lista">
        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      
    </div>
    <button class="bar-cart-btn" onclick="openCartDrawer()">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
      Meu Carrinho
      <span class="bar-cart-count" id="cartCount">0</span>
    
  </div>
</div>

<!-- SHOP BODY -->
@php
// Helper: get formatted price from DB, fallback to hardcoded value
$dp = fn(string $sku) => optional($dbPrices->get($sku))->price_formatted ?? 'Sob Consulta';
@endphp
<div class="shop-body">

  {{-- ════ MANGUEIRAS E HIDRANTES ════ --}}
  <div class="shop-section" data-cat="mangueiras">
    <div class="shop-section-hdr">
      <div class="shop-sec-left">
        <span class="shop-sec-title">MANGUEIRAS E HIDRANTES</span>
      </div>
    </div>
    <div class="prod-grid">

      @foreach([
        ['mangueira-t1-15','Mangueira Tipo 1 — 1½"','Para hidrantes internos residenciais.',['15m · Ø 1½" (38mm)','Pressão máx. 980 kPa','NBR 14349 + 11861','INMETRO + UL'],'img/fire out images/Mangueira 1.1:2.jpg','Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%201%201.1%2F2%22.'],
        ['mangueira-t2-15','Mangueira Tipo 2 — 1½"','Uso comercial, industrial e Corpo de Bombeiros.',['15m · Ø 1½" (38mm)','Pressão máx. 1370 kPa','NBR 14349 + 11861','INMETRO + UL'],'img/fire out images/Mangueira 1.1:2.jpg','Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%202%201.1%2F2%22.'],
        ['mangueira-t2-25','Mangueira Tipo 2 — 2½"','Para indústrias e áreas de alto risco.',['15m · Ø 2½" (65mm)','Pressão máx. 1370 kPa','NBR 14349 + 11861','INMETRO + UL'],'img/fire out images/mangueira 2.1:2.jpg','Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%202%202.1%2F2%22.'],
      ] as [$id,$name,$desc,$specs,$img,$wa])
      <article class="prod-card" data-cat="mangueiras" data-prod="{{ $id }}" data-href="{{ route('produto.show', $id) }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Mangueiras</span>
          <img src="{{ asset($img) }}" alt="{{ $name }}">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">{{ $name }}</h3>
          <p class="pcard-desc">{{ $desc }}</p>
          <div class="pcard-specs">@foreach($specs as $s)<span class="pcard-spec">{{ $s }}</span>@endforeach</div>
          <div class="pcard-footer">
            <div class="pcard-price {{ !isset($dbPrices[$id]) || !$dbPrices[$id]->price ? 'consulta' : '' }}">
              {{ isset($dbPrices[$id]) ? $dbPrices[$id]->price_formatted : 'Sob Consulta' }}
            </div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('{{ $id }}',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>
      @endforeach

      {{-- Esguicho Regulável — merged --}}
      <article class="prod-card" data-cat="mangueiras" data-prod="esguicho" data-href="{{ route('produto.show', 'esguicho') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Hidrantes</span>
          <img src="{{ asset('img/fire out images/esguicho.jpg') }}" alt="Esguicho Regulável" id="img-esguicho">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Esguicho Regulável Storz</h3>
          <p class="pcard-desc">Latão forjado. 3 posições: fechado, jato sólido e cortina de neblina.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">Engate rápido Storz</span>
            <span class="pcard-spec">Latão forjado</span>
          </div>
          <div class="pcard-variants" style="margin-top:12px;">
            <span class="pcard-vlabel">Bitola</span>
            <div class="vpills">
              <button class="vpill active" data-vi="0" onclick="pickVariant(this,'esguicho')">1½"<small>{{ $dp('esguicho-15') }}</small>
              <button class="vpill"        data-vi="1" onclick="pickVariant(this,'esguicho')">2½"<small>{{ $dp('esguicho-25') }}</small>
            </div>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price" id="price-esguicho">{{ $dp('esguicho-15') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('esguicho',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

      {{-- Adaptador Storz — merged --}}
      <article class="prod-card" data-cat="mangueiras" data-prod="adaptador" data-href="{{ route('produto.show', 'adaptador') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Hidrantes</span>
          <img src="{{ asset('img/fire out images/adaptador 2.1:2.jpg') }}" alt="Adaptador Storz" id="img-adaptador">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Adaptador Storz</h3>
          <p class="pcard-desc">Acopla o registro globo do hidrante à mangueira de combate.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">Latão forjado</span>
            <span class="pcard-spec">Alta pressão</span>
          </div>
          <div class="pcard-variants" style="margin-top:12px;">
            <span class="pcard-vlabel">Conexão</span>
            <div class="vpills">
              <button class="vpill active" data-vi="0" onclick="pickVariant(this,'adaptador')">2½" × 1½"<small>{{ $dp('adaptador-2515') }}</small>
              <button class="vpill"        data-vi="1" onclick="pickVariant(this,'adaptador')">2½"<small>{{ $dp('adaptador-25') }}</small>
            </div>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price" id="price-adaptador">{{ $dp('adaptador-2515') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('adaptador',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

      {{-- Moldura para Caixa de Incêndio --}}
      <article class="prod-card" data-cat="mangueiras" data-prod="moldura" data-href="{{ route('produto.show', 'moldura') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Acessórios</span>
          <img src="{{ asset('img/fire out images/moldura-caixa-1.jpg') }}" alt="Moldura para Caixa de Incêndio" id="img-moldura">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Moldura para Caixa de Incêndio</h3>
          <p class="pcard-desc">Abrigo de mangueiras. Chapa galvanizada, pintura vermelha padrão Rio.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">Chapa 18 galvanizada</span>
            <span class="pcard-spec">Vermelho padrão Rio</span>
            <span class="pcard-spec">Fecho porta-cadeado</span>
          </div>
          <div class="pcard-variants" style="margin-top:12px;">
            <span class="pcard-vlabel">Tamanho</span>
            <div class="vpills">
              <button class="vpill active" data-vi="0" onclick="pickVariant(this,'moldura')">60×40 cm<small>{{ $dp('moldura-6040') }}</small>
              <button class="vpill"        data-vi="1" onclick="pickVariant(this,'moldura')">70×50 cm<small>{{ $dp('moldura-7050') }}</small>
            </div>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price" id="price-moldura">{{ $dp('moldura-6040') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('moldura',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

    </div>
  </div>

  {{-- ════ EXTINTORES E SUPORTES ════ --}}
  <div class="shop-section" data-cat="extintores">
    <div class="shop-section-hdr">
      <div class="shop-sec-left">
        <span class="shop-sec-title">EXTINTORES E SUPORTES</span>
      </div>
    </div>
    <div class="prod-grid">

      <article class="prod-card" data-cat="extintores" data-prod="extintor-abc" data-href="{{ route('produto.show', 'extintor-abc') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Extintores</span>
          <img src="{{ asset('img/fire out images/Extintor PQS.jpg') }}" alt="Extintor ABC 6kg">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Extintor ABC — 6 kg</h3>
          <p class="pcard-desc">Classes A (sólidos), B (líquidos/gases) e C (elétrico). Suporte de parede incluso.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">6 kg PQS ABC</span>
            <span class="pcard-spec">Descarga ~15 seg.</span>
            <span class="pcard-spec">Pressurização direta</span>
            <span class="pcard-spec">NBR 15808 · INMETRO</span>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price">{{ $dp('extintor-abc') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('extintor-abc',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

      <article class="prod-card" data-cat="extintores" data-prod="tripe" data-href="{{ route('produto.show', 'tripe') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Suportes</span>
          <img src="{{ asset('img/fire out images/tripe-extintor.png') }}" alt="Tripé Reforçado com Haste">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Tripé Reforçado com Haste</h3>
          <p class="pcard-desc">Suporte de solo para extintores PQS onde não há parede disponível.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">Chapa 18 · Base 19cm</span>
            <span class="pcard-spec">Para extintores PQS</span>
            <span class="pcard-spec">Sem placa de sinalização</span>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price">{{ $dp('tripe') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('tripe',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

    </div>
  </div>

  {{-- ════ PORTAS CORTA-FOGO ════ --}}
  <div class="shop-section" data-cat="protecao">
    <div class="shop-section-hdr">
      <div class="shop-sec-left">
        <span class="shop-sec-title">PORTAS CORTA-FOGO</span>
      </div>
      <a href="{{ route('porta-corta-fogo') }}" class="shop-sec-link">Ver página de Portas <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
    <div class="prod-grid">

      {{-- Porta CF — special --}}
      <article class="prod-card" data-cat="protecao" data-prod="porta-cf" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Portas</span>
          <img src="{{ asset('img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp') }}" alt="Porta Corta-Fogo P-90">

        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Porta Corta-Fogo P-90</h3>
          <p class="pcard-desc">Fabricação própria. Conjunto completo pronto para instalação.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">P-90 · 90 min</span>
            <span class="pcard-spec">ABNT NBR 11742</span>
            <span class="pcard-spec">60×180 · 90×210 cm</span>
            <span class="pcard-spec">Inclui contra-marco + ferragens</span>
          </div>
          <a href="{{ route('porta-corta-fogo') }}" class="pcard-pcf-cta">
            Ver especificações e preços
            <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>

      @foreach([
        ['antipanico','Barra Antipânico','Destravamento imediato pelo lado de fuga. NBR 11785.',['Vertical ou horizontal','Hospitais · Shoppings · Cinemas'],'img/fire out images/antipanico.jpg','Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Barra%20Antip%C3%A2nico.'],
        ['portas-duplas','Portas Duplas / Fora de Padrão','Medidas especiais. Fabricação própria Fire Out sob medida.',['ABNT NBR 11742','Prazo 5 dias úteis'],'img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp','Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Portas%20Duplas.'],
      ] as [$id,$name,$desc,$specs,$img,$wa])
      <article class="prod-card" data-cat="protecao" data-prod="{{ $id }}" data-href="{{ route('produto.show', $id) }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Portas</span>
          <img src="{{ asset($img) }}" alt="{{ $name }}">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">{{ $name }}</h3>
          <p class="pcard-desc">{{ $desc }}</p>
          <div class="pcard-specs">@foreach($specs as $s)<span class="pcard-spec">{{ $s }}</span>@endforeach</div>
          <div class="pcard-footer">
            <div class="pcard-price consulta">Sob Consulta</div>
            <div class="pcard-actions">
              <a href="{{ route('contato') }}?mensagem={{ urlencode('Olá! Gostaria de solicitar um orçamento para ' . $name . '.') }}" class="pcard-contact"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Fale Conosco</a>
            </div>
          </div>
        </div>
      </article>
      @endforeach

    </div>
  </div>

  {{-- ════ ABRAÇADEIRAS E SUPORTES ════ --}}
  <div class="shop-section" data-cat="abracadeiras">
    <div class="shop-section-hdr">
      <div class="shop-sec-left">
        <span class="shop-sec-title">ABRAÇADEIRAS TIPO GOTA E SUPORTES</span>
      </div>
    </div>
    <div class="prod-grid">


          {{-- Canopla --}}
      <article class="prod-card" data-cat="abracadeiras" data-prod="canopla" data-href="{{ route('produto.show', 'canopla') }}" data-cv="0">
        <div class="pcard-img">
          <span class="pcard-cat">Suportes</span>
          <img src="{{ asset('img/fire out images/20 canopla acabamento-para-sprinklers-12-200-unidades.webp') }}" alt="Canopla Aço Inox">
        </div>
        <div class="pcard-body">
          <h3 class="pcard-name">Canopla em Aço Inox ½"</h3>
          <p class="pcard-desc">Acabamento para sprinklers. Máxima resistência à corrosão — ideal para maresia.</p>
          <div class="pcard-specs">
            <span class="pcard-spec">Aço inox · Ø 63mm</span>
            <span class="pcard-spec">½" SPK</span>
            <span class="pcard-spec">Anti-corrosão</span>
          </div>
          <div class="pcard-footer">
            <div class="pcard-price">{{ $dp('canopla') }}</div>
            <div class="pcard-actions">
              <button class="pcard-add" onclick="addToCart('canopla',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho
            </div>
          </div>
        </div>
      </article>

      {{-- Abraçadeiras — one card per size, 3 kit variants each --}}
      @foreach($abracadeiras as $size)
        @php
          $prodId    = 'abrac-' . $size['size_key'];
          $firstKit  = $size['kits'][0] ?? null;
          $firstPrice = $firstKit ? $firstKit['price_formatted'] : 'Sob Consulta';
          $isConsulta = !$firstKit || $firstKit['price'] === null;
        @endphp
        @php
          $abracCardImgs = ['abracadeira-gota.png','abracadeira-tamanhos.png','abracadeira-inox.png'];
          $abracCardImg  = $abracCardImgs[$loop->index % 3];
        @endphp
        <article class="prod-card" data-cat="abracadeiras" data-prod="{{ $prodId }}" data-href="{{ route('produto.show', $prodId) }}" data-cv="0">
          <div class="pcard-img">
            <span class="pcard-cat">Abraçadeiras</span>
            <img src="{{ asset('img/fire out images/' . $abracCardImg) }}" alt="Abraçadeira Tipo Gota {{ $size['size_label'] }}">
          </div>
          <div class="pcard-body">
            <h3 class="pcard-name">Abraçadeira {{ $size['size_label'] }}</h3>
            <p class="pcard-desc">Tipo Gota — {{ $size['material'] }}. Para tubulações hidráulicas e elétricas.</p>
            <div class="pcard-variants" style="margin-top:12px;">
              <span class="pcard-vlabel">Quantidade</span>
              <div class="vpills">
                @foreach($size['kits'] as $i => $kit)
                  <button class="vpill {{ $i === 0 ? 'active' : '' }}"
                          data-vi="{{ $i }}"
                          onclick="pickVariant(this,'{{ $prodId }}')">
                    Kit {{ $kit['qty'] }} un.<small>{{ $kit['price_formatted'] }}</small>
                  
                @endforeach
              </div>
            </div>
            <div class="pcard-footer">
              <div class="pcard-price {{ $isConsulta ? 'consulta' : '' }}" id="price-{{ $prodId }}">{{ $firstPrice }}</div>
              <div class="pcard-actions">
                @if($isConsulta)
                  <a href="{{ route('contato') }}?mensagem={{ urlencode('Olá! Gostaria de solicitar um orçamento para Abraçadeira ' . $size['size_label'] . '.') }}" class="pcard-contact"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Fale Conosco</a>
                @else
                  <button class="pcard-add" onclick="addToCart('{{ $prodId }}',this.closest('.prod-card'))"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Adicionar ao Carrinho</button>
                @endif
              </div>
            </div>
          </div>
        </article>
      @endforeach

    </div>
  </div>

  <!-- CTA -->
  <div class="cta-banner">
    <div>
      <h2>NÃO ENCONTROU O QUE PRECISA?</h2>
      <p>Nossa equipe localiza qualquer equipamento certificado. Fale conosco.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="https://wa.me/5521966969836" class="btn-white" target="_blank">WhatsApp: (21) 96696-9836</a>
      <a href="{{ route('contato') }}" class="btn-ghost" style="border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.85);">Enviar E-mail</a>
    </div>
  </div>

</div>

<!-- CART DRAWER -->
<div class="cart-backdrop" id="cartBackdrop" onclick="closeCartDrawer()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-header">
    <span class="cart-title">MEU ORÇAMENTO</span>
    <button class="cart-close" onclick="closeCartDrawer()"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </div>
  <div class="cart-items-list" id="cartItemsList"></div>
  <div class="cart-footer">
    <p class="cart-summary" id="cartSummary">0 itens no orçamento</p>
    <button class="cart-checkout" id="cartCheckoutBtn" onclick="cartCheckout()" disabled>
      <svg viewBox="0 0 24 24"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
      Finalizar Pedido
    
    <button class="cart-send" id="cartSendBtn" onclick="cartSendWA()" disabled>
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
      Enviar via WhatsApp
    
    <button class="cart-clear" onclick="cartClear()">Limpar lista
  </div>
</div>

<!-- TOAST -->
<div class="cart-toast" id="cartToast"></div>

<!-- Hidden form for checkout POST -->
<form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" style="display:none">
  @csrf
  <input type="hidden" name="cart" id="checkoutCartInput">
</form>

@endsection

@push('floats')
{{-- The global .float-wa is in the layout; no extra float needed here --}}
@endpush

@push('scripts')
<script>
/* ── VIEW TOGGLE ── */
function setView(v) {
  document.querySelector('.shop-body').classList.toggle('list-view', v === 'list');
  document.getElementById('btnGrid').classList.toggle('active', v === 'grid');
  document.getElementById('btnList').classList.toggle('active', v === 'list');
  try { localStorage.setItem('shopView', v); } catch(e) {}
}
(function(){ try { if (localStorage.getItem('shopView') === 'list') setView('list'); } catch(e) {} })();

/* ── CATALOGUE ── */
const catalogue = {
  /* ── MANGUEIRAS ── */
  'mangueira-t1-15': { price:280.00,  badge:'Mangueiras', name:'Mangueira Tipo 1 — 1½"', desc:'Para hidrantes internos residenciais. Revestimento externo 100% poliéster, tubo interno borracha sintética, conexões Storz em latão forjado. Dupla certificação INMETRO e UL.', variants:[{ label:null, img:'{{ asset("img/fire out images/Mangueira 1.1:2.jpg") }}', specs:['Tipo: 1 — Edifícios Residenciais','Bitola: 1½" (38mm)','Comprimento: 15 metros','Pressão máx.: 980 kPa (10 kgf/cm²)','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL','Material externo: 100% poliéster','Tubo interno: borracha sintética','Conexões: Storz latão forjado'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%201%201.1%2F2%22.' }] },
  'mangueira-t2-15': { price:340.00,  badge:'Mangueiras', name:'Mangueira Tipo 2 — 1½"', desc:'Para uso comercial, industrial e Corpo de Bombeiros. Maior resistência mecânica que o Tipo 1. Revestimento externo 100% poliéster, tubo interno borracha sintética, conexões Storz em latão forjado.', variants:[{ label:null, img:'{{ asset("img/fire out images/Mangueira 1.1:2.jpg") }}', specs:['Tipo: 2 — Comercial, Industrial e Bombeiros','Bitola: 1½" (38mm)','Comprimento: 15 metros','Pressão máx.: 1370 kPa (14 kgf/cm²)','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL','Material externo: 100% poliéster','Tubo interno: borracha sintética','Conexões: Storz latão forjado'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%202%201.1%2F2%22.' }] },
  'mangueira-t2-25': { price:540.00,  badge:'Mangueiras', name:'Mangueira Tipo 2 — 2½"', desc:'Para indústrias e áreas de alto risco. Revestimento externo 100% poliéster, tubo interno borracha sintética, conexões Storz em latão forjado. Dupla certificação INMETRO e UL.', variants:[{ label:null, img:'{{ asset("img/fire out images/mangueira 2.1:2.jpg") }}', specs:['Tipo: 2 — Comercial, Industrial e Bombeiros','Bitola: 2½" (65mm)','Comprimento: 15 metros','Pressão máx.: 1370 kPa (14 kgf/cm²)','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL','Material externo: 100% poliéster','Tubo interno: borracha sintética','Conexões: Storz latão forjado'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Mangueira%20Tipo%202%202.1%2F2%22.' }] },
  'esguicho': { price:140.00, badge:'Sistema de Hidrantes', name:'Esguicho Regulável Storz', desc:'Alta performance em latão forjado. Controle operacional em 3 posições estratégicas de combate.', variants:[
    { label:'1½"', price:140.00, img:'{{ asset("img/fire out images/esguicho.jpg") }}', specs:['Conexão: 1½" Storz','Posição 1: Totalmente fechado','Posição 2: Jato sólido concentrado','Posição 3: Cortina de neblina','Material: Latão forjado'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Esguicho%201.1%2F2%22.' },
    { label:'2½"', price:199.00, img:'{{ asset("img/fire out images/esguicho.jpg") }}', specs:['Conexão: 2½" Storz','Posição 1: Totalmente fechado','Posição 2: Jato sólido concentrado','Posição 3: Cortina de neblina','Material: Latão forjado','Aplicação: Maior vazão'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Esguicho%202.1%2F2%22.' }
  ] },
  'adaptador': { price:110.00, badge:'Sistema de Hidrantes', name:'Adaptador Storz', desc:'Engate rápido em latão de alta durabilidade. Acopla o registro globo do hidrante à mangueira de combate.', variants:[
    { label:'2½" × 1½"', price:110.00, img:'{{ asset("img/fire out images/adaptador 2.1:2.jpg") }}', specs:['Entrada: 2½" Rosca (registro globo)','Saída: 1½" Storz (mangueira)','Material: Latão de alta durabilidade'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Adaptador%202.1%2F2%22%20x%201.1%2F2%22.' },
    { label:'2½"',       price:180.00, img:'{{ asset("img/fire out images/adaptador 2.1:2.jpg") }}', specs:['Conexão: 2½"','Material: Latão de alta durabilidade'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Adaptador%202.1%2F2%22.' }
  ] },
  /* ── PORTAS E MOLDURAS ── */
  'porta-cf':        { price:null,    badge:'Proteção Estrutural', name:'Porta Corta-Fogo P-90', desc:'Porta P-90 com 90 minutos de resistência ao fogo, certificada ABNT NBR 11742. Fabricação própria Fire Out com instalação inclusa.', variants:[{ label:null, img:'{{ asset("img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp") }}', specs:['Classificação: P-90','Resistência: 90 minutos','Norma: ABNT NBR 11742','Fabricação própria Fire Out','Núcleo isolante térmico certificado','Instalação inclusa'], wa:'Ol%C3%A1!%20Gostaria%20de%20solicitar%20um%20or%C3%A7amento%20para%20Porta%20Corta-Fogo%20P-90.' }] },
  'moldura': { price:200.00, badge:'Portas e Molduras', name:'Moldura para Caixa de Incêndio', desc:'Caixa em chapa 18 galvanizada para proteção e abrigo de mangueiras de incêndio. Acabamento vermelho padrão Rio com fecho porta-cadeado instalado.', variants:[
    { label:'60×40 cm', price:200.00, img:'{{ asset("img/fire out images/moldura-caixa-1.jpg") }}', specs:['Dimensões: 60×40 cm','Material: Chapa 18 galvanizada','Acabamento: Vermelho padrão Rio','Fecho porta-cadeado incluso'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Moldura%20Caixa%2060x40.' },
    { label:'70×50 cm', price:250.00, img:'{{ asset("img/fire out images/moldura-caixa-2.jpg") }}', specs:['Dimensões: 70×50 cm','Material: Chapa 18 galvanizada','Acabamento: Vermelho padrão Rio','Fecho porta-cadeado incluso'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Moldura%20Caixa%2070x50.' }
  ] },
  'antipanico':      { price:null,    badge:'Proteção Estrutural', name:'Barra Antipânico', desc:'Garante o destravamento imediato da porta sob qualquer pressão exercida na barra pelo lado de fuga. A norma proíbe qualquer mecanismo de trancamento no lado oposto que impeça essa abertura.', variants:[{ label:null, img:'{{ asset("img/fire out images/antipanico.jpg") }}', specs:['Norma: NBR 11785','Tipos: Alavanca com travamento vertical ou horizontal','Obrigatória em: hospitais, indústrias, cinemas, shoppings, igrejas e discotecas','Acionamento: simples pressão pelo lado de fuga'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Barra%20Antip%C3%A2nico.' }] },
  'portas-duplas':   { price:null,    badge:'Portas e Molduras', name:'Portas Duplas / Fora de Padrão', desc:'Portas corta-fogo duplas e medidas especiais. Fabricação própria Fire Out sob medida conforme a necessidade da edificação. Prazo de fabricação: 5 dias úteis.', variants:[{ label:null, img:'{{ asset("img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp") }}', specs:['Medidas especiais sob pedido','Portas duplas disponíveis','Fabricação própria Fire Out','Prazo: 5 dias úteis','Consulte nossa equipe técnica'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Portas%20Duplas.' }] },
  /* ── ABRAÇADEIRAS — prices from DB ── */
  @foreach($abracadeiras as $size)
  @php
    $prodId     = 'abrac-' . $size['size_key'];
    $firstPrice = $size['kits'][0]['price'] ?? null;
    $variants   = array_map(fn($kit) => [
        'label' => 'Kit ' . $kit['qty'] . ' un.',
        'price' => $kit['price'],
        'img'   => asset('img/fire out images/abracadeira-gota.png'),
        'specs' => [
            'Bitola: ' . $size['size_label'],
            'Quantidade: ' . $kit['qty'] . ' unidades',
            'Material: ' . $size['material'],
            'Tipo: Gota',
        ],
        'wa' => rawurlencode('Olá! Gostaria de informações sobre Abraçadeira ' . $size['size_label'] . ' Kit ' . $kit['qty'] . ' un.'),
    ], $size['kits']);
  @endphp
  '{{ $prodId }}': {
    price: {{ $firstPrice !== null ? $firstPrice : 'null' }},
    badge: 'Abraçadeiras Tipo Gota',
    name: @json('Abraçadeira ' . $size['size_label']),
    desc: @json('Tipo Gota em ' . $size['material'] . '. Para amarração e ancoragem de tubulações elétricas e hidráulicas.'),
    variants: @json($variants)
  },
  @endforeach
  /* ── SUPORTES ── */
  'canopla':     { price:4.00,   badge:'Suportes e Acessórios', name:'Canopla em Aço Inox ½"', desc:'Acabamento estético e funcional para instalação de sprinklers em forros de gesso. Produzida em aço inoxidável — máxima resistência à corrosão, ideal para áreas litorâneas expostas à maresia. Não deforma como peças de alumínio.', variants:[{ label:null, img:'{{ asset("img/fire out images/20 canopla acabamento-para-sprinklers-12-200-unidades.webp") }}', specs:['Material: Aço Inox','Diâmetro: 63 mm','Compatível com: ½" SPK (sprinklers)','Resistência à corrosão: máxima (maresia)','Vantagem: não deforma sob calor como alumínio'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Canopla%20Inox.' }] },
  'tripe':       { price:50.00,  badge:'Suportes e Acessórios', name:'Tripé Reforçado com Haste', desc:'Suporte de solo para extintores em locais onde não é possível fixar diretamente na parede. Altamente indicado para extintores PQS (pó químico seco). Não acompanha placa de sinalização.', variants:[{ label:null, img:'{{ asset("img/fire out images/tripe-extintor.png") }}', specs:['Material: Chapa 18','Base: 19 cm de diâmetro','Indicado para: extintores PQS','Aplicação: onde não há parede disponível para fixação','Obs.: não acompanha placa de sinalização'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Trip%C3%A9%20Refor%C3%A7ado.' }] },
  /* ── EXTINTORES ── */
  'extintor-abc': { price:250.00, badge:'Extintores de Incêndio', name:'Extintor ABC — 6 kg', desc:'Recomendado para extinguir princípios de incêndio em três frentes: sólidos combustíveis (A), líquidos e gases inflamáveis (B) e sistemas elétricos energizados (C). Acompanha suporte de parede metálico.', variants:[{ label:null, img:'{{ asset("img/fire out images/Extintor PQS.jpg") }}', specs:['Agente extintor: Pó Químico Seco ABC','Carga: 6 kg','Classe A: sólidos (madeira, papel, plásticos)','Classe B: líquidos/gases (álcool, gasolina, óleo)','Classe C: sistemas elétricos energizados','Tempo de descarga: ~15 segundos','Pressurização: direta','Suporte de parede metálico incluso','Norma: NBR 15808','Certificação: INMETRO'], wa:'Ol%C3%A1!%20Gostaria%20de%20informa%C3%A7%C3%B5es%20sobre%20Extintor%20ABC%206kg.' }] }
};

/* ── DB PRICE OVERRIDES (admin-managed) ──
   Maps db SKU → [catalogueKey, variantIndex]
   Prices set in /admin/produtos override the hardcoded defaults above. */
@php
$skuMap = [
    'mangueira-t1-15'  => ['mangueira-t1-15', 0],
    'mangueira-t2-15'  => ['mangueira-t2-15', 0],
    'mangueira-t2-25'  => ['mangueira-t2-25', 0],
    'esguicho-15'      => ['esguicho', 0],
    'esguicho-25'      => ['esguicho', 1],
    'adaptador-2515'   => ['adaptador', 0],
    'adaptador-25'     => ['adaptador', 1],
    'moldura-6040'     => ['moldura', 0],
    'moldura-7050'     => ['moldura', 1],
    'antipanico'       => ['antipanico', 0],
    'portas-duplas'    => ['portas-duplas', 0],
    'canopla'          => ['canopla', 0],
    'tripe'            => ['tripe', 0],
    'extintor-abc'     => ['extintor-abc', 0],
    'porta-cf'         => ['porta-cf', 0],
];
@endphp
(function() {
  const overrides = @json($dbPrices->only(array_keys($skuMap))->map(fn($p) => $p->price));
  const map = @json($skuMap);
  Object.keys(overrides).forEach(dbSku => {
    const entry = map[dbSku];
    if (!entry) return;
    const [catKey, vi] = entry;
    if (!catalogue[catKey]) return;
    const price = overrides[dbSku] !== null ? parseFloat(overrides[dbSku]) : null;
    catalogue[catKey].variants[vi].price = price;
    if (vi === 0) catalogue[catKey].price = price;
  });
})();

/* ── CONTACT / ADD-TO-CART ACTION ── */
const _contatoUrl = '{{ route("contato") }}';
const _addSvg  = '<svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>';
const _chatSvg = '<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';

function updateCardAction(card) {
  const prodId = card.dataset.prod;
  if (!prodId) return;
  const vi   = parseInt(card.dataset.cv) || 0;
  const prod = catalogue[prodId];
  if (!prod) return;
  const price = (prod.variants[vi] ?? prod.variants[0] ?? {}).price ?? prod.price;
  const div   = card.querySelector('.pcard-actions');
  if (!div) return;

  if (price === null || price === undefined) {
    const msg = encodeURIComponent('Olá! Gostaria de solicitar um orçamento para ' + prod.name + '.');
    div.innerHTML = '<a href="' + _contatoUrl + '?mensagem=' + msg + '" class="pcard-contact">' + _chatSvg + 'Fale Conosco</a>';
  } else if (div.querySelector('.pcard-contact')) {
    div.innerHTML = '<button class="pcard-add" onclick="addToCart(\'' + prodId + '\',this.closest(\'.prod-card\'))">' + _addSvg + 'Adicionar ao Carrinho</button>';
  }
}

/* ── VARIANT PICKER ── */
function pickVariant(pill, prodId) {
  const card = pill.closest('.prod-card');
  const vi   = +pill.dataset.vi;
  const v    = catalogue[prodId].variants[vi];
  card.querySelectorAll('.vpill').forEach(p => p.classList.remove('active'));
  pill.classList.add('active');
  card.dataset.cv = vi;
  const imgEl   = document.getElementById('img-'   + prodId);
  if (imgEl)   imgEl.src  = v.img;
  const waEl    = document.getElementById('wa-'    + prodId);
  if (waEl)    waEl.href  = 'https://wa.me/5521966969836?text=' + v.wa;
  const priceEl = document.getElementById('price-' + prodId);
  if (priceEl) {
    priceEl.textContent = v.price != null ? fmtBRL(v.price) : 'Sob Consulta';
    priceEl.classList.toggle('consulta', v.price === null);
  }
  updateCardAction(card);
}

document.querySelectorAll('.prod-card[data-prod]').forEach(updateCardAction);

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCartDrawer(); });

/* ── FILTER ── */
function filterCat(cat, btn) {
  /* sync tiles */
  document.querySelectorAll('.cat-tile').forEach(t => t.classList.remove('active'));
  const tile = document.querySelector(`.cat-tile[data-cat="${cat}"]`);
  if (tile) tile.classList.add('active');
  /* sync pills */
  document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
  const pill = document.querySelector(`.filter-pill[data-cat="${cat}"]`);
  if (pill) pill.classList.add('active');

  document.querySelectorAll('.shop-section').forEach(s => {
    s.style.display = (cat === 'all' || s.dataset.cat === cat) ? '' : 'none';
  });
  const count = cat === 'all'
    ? document.querySelectorAll('.prod-card').length
    : document.querySelectorAll(`.prod-card[data-cat="${cat}"]`).length;
}

/* ── QUOTE CART ── */
const cartItems = JSON.parse(localStorage.getItem('fireout_cart') || '[]');
function cartSave() { localStorage.setItem('fireout_cart', JSON.stringify(cartItems)); }

function fmtBRL(n) {
  return 'R$ ' + n.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function cartAdd(prodId, vi) {
  const prod = catalogue[prodId];
  if (!prod) return;
  const v = prod.variants[vi] || prod.variants[0];
  const key = prodId + '_' + vi;
  const existing = cartItems.find(i => i.key === key);
  if (existing) {
    existing.qty++;
  } else {
    cartItems.push({ key, prodId, vi, name: prod.name, variant: v.label, price: v.price ?? prod.price, qty: 1 });
  }
  cartSave();
  cartUpdateUI();
  showToast(prod.name + (v.label ? ' — ' + v.label : '') + ' adicionado');
}

function addToCart(prodId, card) {
  const vi = card ? +card.dataset.cv : 0;
  const btn = card ? card.querySelector('.pcard-add') : null;
  cartAdd(prodId, vi);
  if (btn) {
    const orig = btn.innerHTML;
    btn.classList.add('added');
    btn.innerHTML = '<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" stroke="#fff" fill="none" stroke-width="2.5"/></svg>Adicionado!';
    setTimeout(() => { btn.classList.remove('added'); btn.innerHTML = orig; }, 1400);
  }
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
  const total = cartItems.reduce((s, i) => s + i.qty, 0);
  const countEl = document.getElementById('cartCount');
  countEl.textContent = total;
  countEl.classList.toggle('show', total > 0);

  const priceTotal = cartItems.reduce((s, i) => i.price !== null ? s + i.price * i.qty : s, 0);
  const hasConsulta = cartItems.some(i => i.price === null);
  const allConsulta = cartItems.every(i => i.price === null);

  const summary = document.getElementById('cartSummary');
  let summaryHtml = `<strong>${total}</strong> ${total === 1 ? 'item' : 'itens'} no orçamento`;
  if (cartItems.length > 0) {
    summaryHtml += `<div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-amount">${allConsulta ? 'Sob Consulta' : fmtBRL(priceTotal)}</span></div>`;
    if (!allConsulta && hasConsulta) summaryHtml += `<div class="cart-consulta-note">* Alguns itens são Sob Consulta</div>`;
  }
  summary.innerHTML = summaryHtml;

  const sendBtn = document.getElementById('cartSendBtn');
  sendBtn.disabled = total === 0;
  const checkoutBtn = document.getElementById('cartCheckoutBtn');
  if (checkoutBtn) checkoutBtn.disabled = total === 0;

  const list = document.getElementById('cartItemsList');
  if (cartItems.length === 0) {
    list.innerHTML = `<div class="cart-empty-msg"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>Nenhum item ainda.<br>Adicione produtos para montar seu orçamento.</div>`;
    return;
  }
  list.innerHTML = cartItems.map(item => {
    const unitStr  = item.price !== null ? fmtBRL(item.price) + '/un' : 'Sob consulta';
    const subStr   = item.price !== null ? fmtBRL(item.price * item.qty) : '';
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
        <button class="cart-remove" onclick="cartRemove('${item.key}')">×
      </div>
    </div>`;
  }).join('');
}

function cartSendEmail() {
  if (cartItems.length === 0) return;

  let body = 'Olá, equipe Fire Out!\n\nGostaria de solicitar um orçamento para os seguintes itens:\n\n';
  cartItems.forEach(item => {
    const varStr = item.variant ? ' — ' + item.variant : '';
    const qtyStr = item.qty > 1 ? ' (x' + item.qty + ')' : '';
    body += '• ' + item.name + varStr + qtyStr + '\n';
  });
  body += '\nPor favor, enviar proposta comercial com valores e prazo de entrega.\n\nObrigado!';

  window.location.href = '{{ route("contato") }}?mensagem=' + encodeURIComponent(body);
}

function cartSendWA() {
  if (cartItems.length === 0) return;
  let lines = 'Olá! Gostaria de solicitar um orçamento:\n\n';
  let runningTotal = 0;
  let hasConsulta  = false;
  cartItems.forEach(item => {
    const varStr  = item.variant ? ' — ' + item.variant : '';
    const qtyStr  = item.qty > 1 ? ' (x' + item.qty + ')' : '';
    const priceStr = item.price !== null
      ? ' — ' + fmtBRL(item.price * item.qty)
      : ' — Sob Consulta';
    if (item.price !== null) runningTotal += item.price * item.qty;
    else hasConsulta = true;
    lines += '• ' + item.name + varStr + qtyStr + priceStr + '\n';
  });
  lines += '\n';
  if (runningTotal > 0 && hasConsulta) lines += 'Subtotal (itens c/ preço fixo): ' + fmtBRL(runningTotal) + '\n(+ itens Sob Consulta)\n';
  else if (runningTotal > 0)           lines += 'Total: ' + fmtBRL(runningTotal) + '\n';
  else                                  lines += 'Todos os itens são Sob Consulta.\n';
  lines += '\nAguardo retorno. Obrigado!';
  window.open('https://wa.me/5521966969836?text=' + encodeURIComponent(lines), '_blank');
}

function openCartDrawer()  { document.getElementById('cartDrawer').classList.add('open');   document.getElementById('cartBackdrop').classList.add('open');   document.body.style.overflow='hidden'; cartUpdateUI(); }
function closeCartDrawer() { document.getElementById('cartDrawer').classList.remove('open'); document.getElementById('cartBackdrop').classList.remove('open'); document.body.style.overflow=''; }

function showToast(msg) {
  const t = document.getElementById('cartToast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2200);
}

function cartCheckout() {
  if (cartItems.length === 0) return;
  document.getElementById('checkoutCartInput').value = JSON.stringify(cartItems);
  document.getElementById('checkoutForm').submit();
}

/* init */
cartUpdateUI();

/* ── CLICKABLE CARDS ──
   Clicking anywhere on a card navigates to the product page.
   Clicks on buttons/links (cart, WhatsApp) are ignored. */
document.querySelectorAll('.prod-card[data-href]').forEach(card => {
  card.style.cursor = 'pointer';
  card.addEventListener('click', e => {
    if (e.target.closest('button, a, .vpill')) return;
    window.location.href = card.dataset.href;
  });
});
</script>
@endpush
