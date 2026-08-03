@extends('layouts.app')
@section('title', 'Fire Out — Admin Pedidos')

@push('styles')
<style>
.admin-wrap { max-width:1200px; margin:0 auto; padding:calc(var(--nav-h) + 40px) 48px 120px; }
.admin-title { font-family:'Bebas Neue',sans-serif; font-size:38px; letter-spacing:1.5px; color:var(--ink); margin-bottom:8px; }
.admin-nav { display:flex; gap:8px; margin-bottom:28px; flex-wrap:wrap; }
.admin-nav a { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:var(--warm); background:rgba(0,0,0,.05); transition:all .15s; text-decoration:none; }
.admin-nav a:hover, .admin-nav a.active { background:var(--ink); color:#fff; }
.filter-bar { display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
.filter-input { padding:9px 14px; border:1.5px solid rgba(0,0,0,.1); border-radius:8px; font-size:13px; font-family:inherit; outline:none; color:var(--ink); }
.filter-input:focus { border-color:var(--red); }
.btn-filter { padding:9px 20px; background:var(--ink); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; font-family:inherit; cursor:pointer; }
.data-table { width:100%; border-collapse:collapse; font-size:14px; background:#fff; border-radius:14px; overflow:hidden; border:1.5px solid rgba(0,0,0,.07); }
.data-table th { padding:12px 16px; text-align:left; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); background:var(--off); border-bottom:1px solid rgba(0,0,0,.07); }
.data-table td { padding:14px 16px; border-bottom:1px solid rgba(0,0,0,.05); color:var(--ink); vertical-align:middle; }
.data-table tr:last-child td { border-bottom:none; }
.data-table tr:hover td { background:rgba(0,0,0,.015); }
.status-pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.btn-sm { padding:6px 14px; border-radius:6px; font-size:12px; font-weight:700; font-family:inherit; cursor:pointer; border:none; background:var(--ink); color:#fff; transition:background .15s; text-decoration:none; }
.btn-sm:hover { background:#2d2d2d; }
.btn-logout { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:#fff; background:#ef4444; border:none; cursor:pointer; font-family:inherit; }
.btn-logout:hover { background:#dc2626; }
@media(max-width:900px) { .admin-wrap { padding:calc(var(--nav-h) + 24px) 24px 80px; } }
</style>
@endpush

@section('content')
<div class="admin-wrap">
  <div class="admin-title">PEDIDOS</div>
  <nav class="admin-nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.orders') }}" class="active">Pedidos</a>
    <a href="{{ route('admin.products') }}">Estoque</a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf
      <button type="submit" class="btn-logout">Sair</button>
    </form>
  </nav>

  <form method="GET" class="filter-bar">
    <input class="filter-input" type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nº, nome ou e-mail…">
    <select class="filter-input" name="status">
      <option value="">Todos os status</option>
      @foreach(['pending'=>'Pendente','confirmed'=>'Confirmado','processing'=>'Em Processamento','completed'=>'Concluído','cancelled'=>'Cancelado'] as $val => $label)
        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn-filter">Filtrar</button>
  </form>

  <table class="data-table">
    <thead>
      <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Entrega</th>
        <th>Data</th>
        <th>Total</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
      <tr>
        <td style="font-weight:700;color:var(--red);">{{ $order->order_number }}</td>
        <td>
          <div style="font-weight:600;">{{ $order->customer_name }}</div>
          <div style="font-size:12px;color:var(--warm);">{{ $order->customer_phone }}</div>
        </td>
        <td style="font-size:12px;color:var(--warm);max-width:180px;">
          {{ $order->shipping_address ?? '—' }}
        </td>
        <td style="white-space:nowrap;">{{ $order->created_at->format('d/m/Y') }}</td>
        <td style="font-weight:600;">{{ $order->total_display }}</td>
        <td><span class="status-pill" style="background:{{ $order->status_color }}20;color:{{ $order->status_color }};">{{ $order->status_label }}</span></td>
        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn-sm">Ver</a></td>
      </tr>
      @empty
      <tr><td colspan="7" style="color:var(--warm);text-align:center;padding:32px;">Nenhum pedido encontrado.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $orders->links() }}
</div>
@endsection
