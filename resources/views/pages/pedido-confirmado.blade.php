@extends('layouts.app')

@section('title', 'Fire Out — Pedido Recebido!')
@section('description', 'Seu pedido foi recebido. Nossa equipe entrará em contato em breve.')

@push('styles')
<style>
.confirm-wrap {
  margin-top:var(--nav-h); min-height:calc(100vh - var(--nav-h));
  display:flex; align-items:center; justify-content:center;
  padding:60px 24px; background:var(--off);
}
.confirm-card {
  background:#fff; border-radius:24px; padding:56px 52px;
  max-width:680px; width:100%; text-align:center;
  box-shadow:0 32px 80px rgba(0,0,0,.08);
}
.confirm-icon {
  width:72px; height:72px; border-radius:50%; background:rgba(22,163,74,.1);
  display:flex; align-items:center; justify-content:center; margin:0 auto 24px;
}
.confirm-icon svg { width:36px; height:36px; stroke:#16a34a; fill:none; stroke-width:2; }
.confirm-num {
  font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--warm); margin-bottom:12px;
}
.confirm-num span {
  font-family:'Bebas Neue',sans-serif; font-size:18px; letter-spacing:3px;
  color:var(--red); margin-left:8px;
}
.confirm-title {
  font-family:'Bebas Neue',sans-serif; font-size:clamp(36px,5vw,52px);
  letter-spacing:1px; color:var(--ink); line-height:1; margin-bottom:16px;
}
.confirm-sub { font-size:15px; color:var(--warm); line-height:1.75; margin-bottom:36px; }

.confirm-pay-box {
  border-radius:14px; padding:22px 24px;
  text-align:left; margin-bottom:28px;
}
.confirm-pay-box.pix    { background:#f0fdf4; border:1px solid #bbf7d0; }
.confirm-pay-box.boleto { background:#eff6ff; border:1px solid #bfdbfe; }
.confirm-pay-box.cartao { background:#fefce8; border:1px solid #fef08a; }
.confirm-pay-title {
  font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.8px;
  margin-bottom:10px;
}
.confirm-pay-box.pix    .confirm-pay-title { color:#15803d; }
.confirm-pay-box.boleto .confirm-pay-title { color:#1d4ed8; }
.confirm-pay-box.cartao .confirm-pay-title { color:#854d0e; }
.confirm-pay-text { font-size:14px; line-height:1.7; }
.confirm-pay-box.pix    .confirm-pay-text { color:#166534; }
.confirm-pay-box.boleto .confirm-pay-text { color:#1e3a8a; }
.confirm-pay-box.cartao .confirm-pay-text { color:#713f12; }

.confirm-items {
  text-align:left; background:var(--off); border-radius:12px;
  padding:18px 22px; margin-bottom:28px;
}
.confirm-items-title {
  font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase;
  color:var(--warm); margin-bottom:12px;
}
.confirm-item {
  display:flex; align-items:center; justify-content:space-between;
  padding:8px 0; border-bottom:1px solid rgba(0,0,0,.06); gap:12px;
  font-size:14px;
}
.confirm-item:last-child { border-bottom:none; }
.confirm-item-name { font-weight:600; color:var(--ink); }
.confirm-item-variant { font-size:12px; color:var(--warm); }
.confirm-item-qty {
  font-size:11px; font-weight:700; background:var(--ink); color:#fff;
  padding:2px 7px; border-radius:4px; white-space:nowrap; flex-shrink:0;
}

.confirm-actions { display:flex; gap:12px; flex-wrap:wrap; justify-content:center; margin-top:8px; }
.confirm-actions a { flex:1; min-width:160px; }

@media(max-width:600px) {
  .confirm-card { padding:36px 24px; }
}
</style>
@endpush

@section('content')
<div class="confirm-wrap">
  <div class="confirm-card">

    <div class="confirm-icon">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>

    <div class="confirm-num">
      Número do Pedido <span>#{{ $order['numero'] }}</span>
    </div>

    <h1 class="confirm-title">PEDIDO<br>RECEBIDO!</h1>
    <p class="confirm-sub">
      Olá, <strong>{{ $order['nome'] }}</strong>! Seu pedido foi registrado com sucesso.<br>
      Nossa equipe entrará em contato via <strong>{{ $order['email'] }}</strong>
      @if(!empty($order['telefone'])) e <strong>{{ $order['telefone'] }}</strong>@endif
      com o orçamento e os próximos passos.
    </p>

    @php $pay = $order['pagamento']; @endphp

    <div class="confirm-pay-box" style="background:#fefce8; border:1px solid #fef08a; margin-bottom: 28px; text-align: left; padding: 22px 24px; border-radius: 14px;">
      <div class="confirm-pay-title" style="color:#854d0e; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; margin-bottom:10px;">📋 Consulta de Pagamento</div>
      <div class="confirm-pay-text" style="color:#713f12; font-size:14px; line-height:1.7;">
        Para sua segurança, a forma de pagamento (PIX, Boleto ou Cartão) será combinada diretamente com a nossa equipe comercial. Clique no botão <strong>"Enviar via WhatsApp"</strong> abaixo para enviar o detalhamento do seu pedido para o nosso atendimento rápido, ou aguarde o nosso contato.
      </div>
    </div>

    <div class="confirm-items">
      <div class="confirm-items-title">Itens do Pedido</div>
      @foreach($order['cart'] as $item)
      <div class="confirm-item">
        <div>
          <div class="confirm-item-name">{{ $item['name'] }}</div>
          @if(!empty($item['variant']))
            <div class="confirm-item-variant">{{ $item['variant'] }}</div>
          @endif
        </div>
        <span class="confirm-item-qty">×{{ $item['qty'] }}</span>
      </div>
      @endforeach
    </div>

    <div class="confirm-actions">
      <a href="{{ route('produtos') }}" class="btn-dark">Continuar Comprando</a>
      <a href="{{ $waUrl }}" class="btn-wa" target="_blank">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
        Enviar via WhatsApp
      </a>
    </div>

  </div>
</div>
@push('scripts')
<script>
function copyPix() {
  const el = document.getElementById('pixKey');
  if (!el) return;
  el.select(); el.setSelectionRange(0, 99999);
  navigator.clipboard?.writeText(el.value).catch(() => document.execCommand('copy'));
  const btn = event.target;
  btn.textContent = '✓ Copiado!';
  setTimeout(() => btn.textContent = 'Copiar', 2000);
}
</script>
@endpush

@endsection
