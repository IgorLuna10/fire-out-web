@extends('layouts.app')
@section('title', 'Fire Out — Admin Dashboard')

@push('styles')
<style>
.admin-wrap { max-width:1200px; margin:0 auto; padding:calc(var(--nav-h) + 40px) 48px 120px; }
.admin-title { font-family:'Bebas Neue',sans-serif; font-size:38px; letter-spacing:1.5px; color:var(--ink); margin-bottom:8px; }
.admin-sub { font-size:14px; color:var(--warm); margin-bottom:36px; }
.admin-nav { display:flex; gap:8px; margin-bottom:36px; flex-wrap:wrap; }
.admin-nav a { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:var(--warm); background:rgba(0,0,0,.05); transition:all .15s; text-decoration:none; }
.admin-nav a:hover, .admin-nav a.active { background:var(--ink); color:#fff; }
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:36px; }
.stat-card { background:#fff; border-radius:14px; padding:22px 24px; border:1.5px solid rgba(0,0,0,.07); }
.stat-label { font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--warm); margin-bottom:8px; }
.stat-value { font-family:'Bebas Neue',sans-serif; font-size:42px; line-height:1; color:var(--ink); }
.stat-value.red { color:var(--red); }
.section-title { font-family:'Bebas Neue',sans-serif; font-size:20px; letter-spacing:1.5px; color:var(--ink); margin-bottom:16px; display:flex; align-items:center; gap:10px; }
.section-title::before { content:''; width:4px; height:20px; background:var(--red); border-radius:2px; display:inline-block; }
.data-table { width:100%; border-collapse:collapse; font-size:14px; background:#fff; border-radius:14px; overflow:hidden; border:1.5px solid rgba(0,0,0,.07); margin-bottom:36px; }
.data-table th { padding:12px 16px; text-align:left; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); background:var(--off); border-bottom:1px solid rgba(0,0,0,.07); }
.data-table td { padding:14px 16px; border-bottom:1px solid rgba(0,0,0,.05); color:var(--ink); vertical-align:middle; }
.data-table tr:last-child td { border-bottom:none; }
.data-table tr:hover td { background:rgba(0,0,0,.015); }
.status-pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.badge-low { background:rgba(239,68,68,.1); color:#dc2626; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-ok { background:rgba(22,163,74,.1); color:#15803d; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.btn-sm { padding:6px 14px; border-radius:6px; font-size:12px; font-weight:700; font-family:inherit; cursor:pointer; border:none; transition:background .15s; }
.btn-sm-red { background:var(--red); color:#fff; }
.btn-sm-red:hover { background:var(--red-d); }
.btn-sm-ink { background:var(--ink); color:#fff; }
.btn-sm-ink:hover { background:#2d2d2d; }
.btn-logout { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:#fff; background:#ef4444; border:none; cursor:pointer; font-family:inherit; }
.btn-logout:hover { background:#dc2626; }
@media(max-width:900px) { .stats-grid { grid-template-columns:1fr 1fr; } .admin-wrap { padding:calc(var(--nav-h) + 24px) 24px 80px; } }
@media(max-width:540px) { .stats-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="admin-wrap">
  <div class="admin-title">PAINEL ADMIN</div>
  <div class="admin-sub">Bem-vindo(a), {{ auth()->user()->name }}. Aqui está o resumo de hoje.</div>

  <nav class="admin-nav">
    <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
    <a href="{{ route('admin.orders') }}">Pedidos</a>
    <a href="{{ route('admin.products') }}">Estoque</a>
    <a href="{{ route('home') }}" target="_blank">Ver site</a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf
      <button type="submit" class="btn-logout">Sair</button>
    </form>
  </nav>

  <!-- STATS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total de Pedidos</div>
      <div class="stat-value">{{ $stats['orders_total'] }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pedidos Pendentes</div>
      <div class="stat-value red">{{ $stats['orders_pending'] }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Clientes</div>
      <div class="stat-value">{{ $stats['customers'] }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Produtos c/ Estoque Baixo</div>
      <div class="stat-value {{ $stats['low_stock'] > 0 ? 'red' : '' }}">{{ $stats['low_stock'] }}</div>
    </div>
  </div>

  <!-- RECENT ORDERS -->
  <div class="section-title">PEDIDOS RECENTES</div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Data</th>
        <th>Total</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($recent_orders as $order)
      <tr>
        <td style="font-weight:700;color:var(--red);">{{ $order->order_number }}</td>
        <td>
          <div style="font-weight:600;">{{ $order->customer_name }}</div>
          <div style="font-size:12px;color:var(--warm);">{{ $order->customer_email }}</div>
        </td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td style="font-weight:600;">{{ $order->total_display }}</td>
        <td><span class="status-pill" style="background:{{ $order->status_color }}20;color:{{ $order->status_color }};">{{ $order->status_label }}</span></td>
        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn-sm btn-sm-ink">Ver</a></td>
      </tr>
      @empty
      <tr><td colspan="6" style="color:var(--warm);text-align:center;padding:32px;">Nenhum pedido ainda.</td></tr>
      @endforelse
    </tbody>
  </table>

  <!-- LOW STOCK ALERT -->
  @if($low_stock->isNotEmpty())
  <div class="section-title">ALERTA DE ESTOQUE BAIXO</div>
  <table class="data-table">
    <thead>
      <tr><th>Produto</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th></th></tr>
    </thead>
    <tbody>
      @foreach($low_stock as $product)
      <tr>
        <td style="font-weight:600;">{{ $product->name }}</td>
        <td>{{ $product->category }}</td>
        <td>{{ $product->price_formatted }}</td>
        <td>
          <span class="{{ $product->stock_qty <= 0 ? 'badge-low' : 'badge-low' }}">
            {{ $product->stock_qty <= 0 ? 'SEM ESTOQUE' : $product->stock_qty . ' un' }}
          </span>
        </td>
        <td><a href="{{ route('admin.products') }}" class="btn-sm btn-sm-red">Ajustar</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

</div>
@endsection
