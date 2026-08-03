@extends('layouts.app')
@section('title', 'Fire Out — Admin Estoque')

@push('styles')
<style>
.admin-wrap { max-width:1280px; margin:0 auto; padding:calc(var(--nav-h) + 40px) 48px 120px; }
.admin-title { font-family:'Bebas Neue',sans-serif; font-size:38px; letter-spacing:1.5px; color:var(--ink); margin-bottom:8px; }
.admin-nav { display:flex; gap:8px; margin-bottom:28px; flex-wrap:wrap; align-items:center; }
.admin-nav a { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:var(--warm); background:rgba(0,0,0,.05); transition:all .15s; text-decoration:none; }
.admin-nav a:hover, .admin-nav a.active { background:var(--ink); color:#fff; }
.btn-logout { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:#fff; background:#ef4444; border:none; cursor:pointer; font-family:inherit; }
.btn-logout:hover { background:#dc2626; }

.alert { padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:20px; }
.alert-success { background:rgba(22,163,74,.08); border:1px solid rgba(22,163,74,.2); color:#15803d; }
.alert-error   { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); color:#dc2626; }

/* ── toolbar ── */
.toolbar {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
  margin-bottom:20px;
}
.btn-save-all {
  padding:11px 28px; background:var(--red); color:#fff; border:none; border-radius:8px;
  font-size:14px; font-weight:700; font-family:inherit; cursor:pointer; transition:background .15s;
  display:flex; align-items:center; gap:8px;
}
.btn-save-all:hover { background:var(--red-d); }
.btn-save-all svg { width:15px; height:15px; stroke:#fff; fill:none; stroke-width:2.5; }

/* ── CSV upload ── */
.csv-box {
  display:flex; align-items:center; gap:10px; flex-wrap:wrap;
  background:rgba(0,0,0,.03); border:1.5px dashed rgba(0,0,0,.12);
  border-radius:10px; padding:14px 18px; margin-bottom:24px;
}
.csv-label { font-size:13px; font-weight:600; color:var(--ink); white-space:nowrap; }
.csv-hint  { font-size:12px; color:var(--warm); }
.csv-input { font-size:13px; font-family:inherit; flex:1; min-width:0; }
.btn-csv {
  padding:8px 18px; background:var(--ink); color:#fff; border:none; border-radius:7px;
  font-size:12px; font-weight:700; font-family:inherit; cursor:pointer; white-space:nowrap;
  transition:background .15s;
}
.btn-csv:hover { background:#2d2d2d; }
.csv-download { font-size:12px; color:var(--red); font-weight:600; text-decoration:none; margin-left:4px; }
.csv-download:hover { text-decoration:underline; }

/* ── table ── */
.data-table { width:100%; border-collapse:collapse; font-size:14px; background:#fff; border-radius:14px; overflow:hidden; border:1.5px solid rgba(0,0,0,.07); }
.data-table th { padding:12px 14px; text-align:left; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); background:var(--off); border-bottom:1px solid rgba(0,0,0,.07); }
.data-table td { padding:10px 14px; border-bottom:1px solid rgba(0,0,0,.05); color:var(--ink); vertical-align:middle; }
.data-table tr:last-child td { border-bottom:none; }
.category-header td { background:var(--ink); color:rgba(255,255,255,.5); font-size:10px; font-weight:700; letter-spacing:2px; text-transform:uppercase; padding:8px 14px; }
.qty-input, .price-input {
  padding:7px 10px; border:1.5px solid rgba(0,0,0,.1); border-radius:7px;
  font-size:13px; font-family:inherit; outline:none; color:var(--ink);
  transition:border-color .15s;
}
.qty-input   { width:76px; text-align:center; }
.price-input { width:96px; }
.qty-input:focus, .price-input:focus { border-color:var(--red); }
.stock-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }

@media(max-width:900px) {
  .admin-wrap { padding:calc(var(--nav-h) + 24px) 20px 80px; }
  .data-table { font-size:12px; }
  .data-table th, .data-table td { padding:8px 10px; }
  .price-input { width:76px; }
}
</style>
@endpush

@section('content')
<div class="admin-wrap">
  <div class="admin-title">ESTOQUE DE PRODUTOS</div>
  <nav class="admin-nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.orders') }}">Pedidos</a>
    <a href="{{ route('admin.products') }}" class="active">Estoque</a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf <button type="submit" class="btn-logout">Sair</button>
    </form>
  </nav>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
  @endif

  {{-- CSV Upload --}}
  <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data">
    @csrf
    <div class="csv-box">
      <span class="csv-label">Importar CSV</span>
      <input class="csv-input" type="file" name="csv" accept=".csv,.txt">
      <button type="submit" class="btn-csv">Importar</button>
      <span class="csv-hint">Colunas: <code>sku, price, stock_qty</code></span>
      <a class="csv-download" href="{{ route('admin.products.export-csv') }}">⬇ Baixar CSV atual</a>
    </div>
  </form>

  {{-- Bulk update form --}}
  <form method="POST" action="{{ route('admin.products.bulk') }}">
    @csrf

    <div class="toolbar">
      <div style="font-size:13px;color:var(--warm);">{{ $products->count() }} produtos · edite preço e estoque e clique em Salvar Tudo</div>
      <button type="submit" class="btn-save-all">
        <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Salvar Tudo
      </button>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>Produto</th>
          <th>SKU</th>
          <th>Preço (R$)</th>
          <th>Estoque (un.)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @php $currentCategory = null; @endphp
        @foreach($products as $product)
          @if($product->category !== $currentCategory)
            @php $currentCategory = $product->category; @endphp
            <tr class="category-header"><td colspan="5">{{ $product->category }}</td></tr>
          @endif
          <tr>
            <td style="font-weight:600;">{{ $product->name }}</td>
            <td style="font-size:11px;color:var(--warm);font-family:monospace;">{{ $product->sku }}</td>
            <td>
              <input class="price-input" type="number"
                     name="products[{{ $product->id }}][price]"
                     step="0.01" min="0"
                     value="{{ $product->price }}"
                     placeholder="—">
            </td>
            <td>
              <input class="qty-input" type="number"
                     name="products[{{ $product->id }}][stock_qty]"
                     min="0"
                     value="{{ $product->stock_qty }}">
            </td>
            <td>
              @if($product->stock_qty <= 0)
                <span class="stock-badge" style="background:rgba(239,68,68,.1);color:#dc2626;">Sem estoque</span>
              @elseif($product->stock_qty <= 5)
                <span class="stock-badge" style="background:rgba(245,158,11,.1);color:#d97706;">{{ $product->stock_qty }} un</span>
              @else
                <span class="stock-badge" style="background:rgba(22,163,74,.1);color:#15803d;">{{ $product->stock_qty }} un</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div style="display:flex;justify-content:flex-end;margin-top:16px;">
      <button type="submit" class="btn-save-all">
        <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Salvar Tudo
      </button>
    </div>
  </form>
</div>
@endsection
