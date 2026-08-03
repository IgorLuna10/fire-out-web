@extends('layouts.app')
@section('title', 'Fire Out — Pedido ' . $order->order_number)

@push('styles')
<style>
.admin-wrap { max-width:1100px; margin:0 auto; padding:calc(var(--nav-h) + 40px) 48px 120px; }
.admin-title { font-family:'Bebas Neue',sans-serif; font-size:38px; letter-spacing:1.5px; color:var(--ink); margin-bottom:4px; }
.back-link { font-size:13px; color:var(--warm); font-weight:600; display:inline-flex; align-items:center; gap:6px; margin-bottom:24px; text-decoration:none; }
.back-link:hover { color:var(--ink); }
.order-grid { display:grid; grid-template-columns:1fr 340px; gap:24px; }
.card { background:#fff; border-radius:16px; border:1.5px solid rgba(0,0,0,.07); padding:28px; margin-bottom:20px; }
.card-title { font-family:'Bebas Neue',sans-serif; font-size:18px; letter-spacing:1px; color:var(--ink); margin-bottom:18px; padding-bottom:12px; border-bottom:1.5px solid rgba(0,0,0,.06); }
.info-row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid rgba(0,0,0,.05); font-size:14px; }
.info-row:last-child { border-bottom:none; }
.info-key { color:var(--warm); font-weight:500; }
.info-val { font-weight:600; color:var(--ink); text-align:right; max-width:60%; }
.items-table { width:100%; border-collapse:collapse; font-size:14px; }
.items-table th { padding:10px 0; text-align:left; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); border-bottom:1.5px solid rgba(0,0,0,.07); }
.items-table td { padding:12px 0; border-bottom:1px solid rgba(0,0,0,.05); vertical-align:middle; }
.items-table tr:last-child td { border-bottom:none; }
.total-row { display:flex; justify-content:space-between; align-items:center; padding-top:14px; border-top:1.5px solid rgba(0,0,0,.08); margin-top:4px; }
.status-form { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
.select-status { padding:9px 14px; border:1.5px solid rgba(0,0,0,.1); border-radius:8px; font-size:13px; font-family:inherit; color:var(--ink); outline:none; }
.select-status:focus { border-color:var(--red); }
.btn-update { padding:9px 20px; background:var(--red); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:inherit; cursor:pointer; }
.btn-update:hover { background:var(--red-d); }
.status-pill { display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
.alert-success { padding:10px 14px; background:rgba(22,163,74,.08); border:1px solid rgba(22,163,74,.2); border-radius:8px; font-size:13px; color:#15803d; margin-bottom:16px; }
.btn-logout { padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; color:#fff; background:#ef4444; border:none; cursor:pointer; font-family:inherit; }
.btn-logout:hover { background:#dc2626; }
@media(max-width:900px) { .order-grid { grid-template-columns:1fr; } .admin-wrap { padding:calc(var(--nav-h) + 24px) 24px 80px; } }
</style>
@endpush

@section('content')
<div class="admin-wrap">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <a class="back-link" href="{{ route('admin.orders') }}" style="margin-bottom:0;">← Voltar para pedidos</a>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('admin.dashboard') }}" style="padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;color:var(--warm);background:rgba(0,0,0,.05);text-decoration:none;">Dashboard</a>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="btn-logout">Sair</button>
      </form>
    </div>
  </div>
  <div class="admin-title">{{ $order->order_number }}</div>
  <div style="font-size:14px;color:var(--warm);margin-bottom:28px;">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</div>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  <div class="order-grid">
    <div>
      <!-- ITEMS -->
      <div class="card">
        <div class="card-title">ITENS DO PEDIDO</div>
        <table class="items-table">
          <thead>
            <tr>
              <th style="width:50%">Produto</th>
              <th style="text-align:center">Qtd</th>
              <th style="text-align:right">Unit.</th>
              <th style="text-align:right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
            <tr>
              <td>
                <div style="font-weight:600;">{{ $item->product_name }}</div>
                @if($item->variant)<div style="font-size:12px;color:var(--warm);">{{ $item->variant }}</div>@endif
              </td>
              <td style="text-align:center;font-weight:700;">{{ $item->qty }}</td>
              <td style="text-align:right;">{{ $item->price_display }}</td>
              <td style="text-align:right;font-weight:700;color:var(--red);">
                {{ $item->unit_price !== null ? 'R$ ' . number_format($item->subtotal, 2, ',', '.') : 'Sob Consulta' }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="total-row">
          <span style="font-size:14px;font-weight:600;color:var(--ink);">Total do pedido</span>
          <span style="font-size:16px;font-weight:700;color:var(--red);">{{ $order->total_display }}</span>
        </div>
      </div>

      <!-- DELIVERY ADDRESS -->
      @if($order->shipping_address)
      <div class="card">
        <div class="card-title">ENDEREÇO DE ENTREGA</div>
        <p style="font-size:14px;color:var(--ink);line-height:1.8;">{{ $order->shipping_address }}</p>
      </div>
      @endif

      <!-- NOTES -->
      @if($order->notes)
      <div class="card">
        <div class="card-title">OBSERVAÇÕES</div>
        <p style="font-size:14px;color:#555;line-height:1.7;">{{ $order->notes }}</p>
      </div>
      @endif
    </div>

    <div>
      <!-- CUSTOMER INFO -->
      <div class="card">
        <div class="card-title">CLIENTE</div>
        <div class="info-row"><span class="info-key">Nome</span><span class="info-val">{{ $order->customer_name }}</span></div>
        <div class="info-row"><span class="info-key">E-mail</span><span class="info-val">{{ $order->customer_email }}</span></div>
        <div class="info-row"><span class="info-key">Telefone</span><span class="info-val">{{ $order->customer_phone }}</span></div>
        @if($order->company)
        <div class="info-row"><span class="info-key">Empresa</span><span class="info-val">{{ $order->company }}</span></div>
        @endif
        @if($order->document_number)
        <div class="info-row">
          <span class="info-key">{{ strtoupper($order->document_type ?? 'CPF') }}</span>
          <span class="info-val">{{ $order->document_number }}</span>
        </div>
        @endif
        @if($order->user)
        <div class="info-row"><span class="info-key">Conta</span><span class="info-val" style="color:var(--red);">Cadastrado</span></div>
        @endif
      </div>

      <!-- PAYMENT -->
      <div class="card">
        <div class="card-title">PAGAMENTO</div>
        <div class="info-row">
          <span class="info-key">Forma</span>
          <span class="info-val">{{ ['cartao'=>'Cartão','pix'=>'PIX','boleto'=>'Boleto'][$order->payment_method] ?? $order->payment_method }}</span>
        </div>
        @if($order->installments > 1)
        <div class="info-row"><span class="info-key">Parcelas</span><span class="info-val">{{ $order->installments }}x</span></div>
        @endif
      </div>

      <!-- STATUS -->
      <div class="card">
        <div class="card-title">STATUS</div>
        <div style="margin-bottom:16px;">
          <span class="status-pill" style="background:{{ $order->status_color }}20;color:{{ $order->status_color }};">{{ $order->status_label }}</span>
        </div>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="status-form">
          @csrf
          <select name="status" class="select-status">
            @foreach(['pending'=>'Pendente','confirmed'=>'Confirmado','processing'=>'Em Processamento','completed'=>'Concluído','cancelled'=>'Cancelado'] as $val => $label)
              <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn-update">Atualizar</button>
        </form>
      </div>

      <!-- WA LINK -->
      <a href="https://wa.me/55{{ preg_replace('/\D/','',$order->customer_phone) }}?text={{ urlencode('Olá ' . $order->customer_name . '! Aqui é a Fire Out. Sobre seu pedido ' . $order->order_number . ':') }}"
         target="_blank"
         style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;background:#25D366;color:#fff;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;transition:background .15s;"
         onmouseover="this.style.background='#1cb955'" onmouseout="this.style.background='#25D366'">
        <svg width="16" height="16" fill="white" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.345.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        Contatar via WhatsApp
      </a>
    </div>
  </div>
</div>
@endsection
