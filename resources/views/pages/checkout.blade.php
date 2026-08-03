@extends('layouts.app')

@section('title', 'Fire Out — Finalizar Pedido')
@section('description', 'Confirme seu pedido e escolha a forma de pagamento.')

@push('styles')
<style>
.checkout-hero {
  margin-top:var(--nav-h); background:var(--ink);
  padding:52px 48px 56px; position:relative; overflow:hidden;
}
.checkout-hero::before {
  content:'CHECKOUT'; position:absolute; right:-10px; top:50%; transform:translateY(-50%);
  font-family:'Bebas Neue',sans-serif; font-size:180px; letter-spacing:-4px;
  color:rgba(255,255,255,.025); pointer-events:none; white-space:nowrap;
}
.checkout-hero-inner { max-width:1200px; margin:0 auto; }
.checkout-hero-pill {
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--red-l); margin-bottom:16px;
}
.checkout-hero-pill::before { content:''; width:24px; height:2px; background:var(--red-l); }
.checkout-hero h1 {
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(40px,6vw,68px); line-height:.95; letter-spacing:2px;
  color:#fff; margin-bottom:10px;
}
.checkout-hero p { font-size:15px; color:rgba(255,255,255,.45); }

.checkout-body {
  max-width:1200px; margin:0 auto; padding:64px 48px 120px;
  display:grid; grid-template-columns:1fr 1.5fr; gap:48px; align-items:start;
}

/* ── ORDER SUMMARY ── */
.order-summary {
  background:#fff; border:1.5px solid rgba(0,0,0,.07);
  border-radius:20px; overflow:hidden; position:sticky; top:calc(var(--nav-h) + 20px);
}
.order-summary-hdr {
  padding:22px 24px 18px; border-bottom:1px solid rgba(0,0,0,.07);
  display:flex; align-items:center; justify-content:space-between;
}
.order-summary-title {
  font-family:'Bebas Neue',sans-serif; font-size:20px; letter-spacing:1px; color:var(--ink);
}
.order-item-list { padding:12px 24px; }
.order-item {
  display:flex; align-items:flex-start; justify-content:space-between;
  padding:12px 0; border-bottom:1px solid rgba(0,0,0,.05); gap:12px;
}
.order-item:last-child { border-bottom:none; }
.order-item-name { font-size:14px; font-weight:600; color:var(--ink); line-height:1.3; }
.order-item-variant { font-size:12px; color:var(--warm); margin-top:2px; }
.order-item-qty {
  font-size:12px; font-weight:700; color:#fff;
  background:var(--ink); border-radius:4px;
  padding:2px 7px; white-space:nowrap; flex-shrink:0; margin-top:2px;
}
.order-footer {
  padding:18px 24px; background:var(--off); border-top:1px solid rgba(0,0,0,.07);
}
.order-footer-note {
  font-size:12px; color:var(--warm); line-height:1.6; margin-bottom:10px;
}
.order-footer-note strong { color:var(--ink); }
.order-back { font-size:12px; font-weight:600; color:var(--red); display:inline-flex; align-items:center; gap:4px; }
.order-back svg { width:12px; height:12px; stroke:currentColor; fill:none; stroke-width:2.5; }

/* ── FORM CARD ── */
.checkout-form-card {
  background:#fff; border:1.5px solid rgba(0,0,0,.07);
  border-radius:20px; padding:44px 40px;
}
.checkout-form-title {
  font-family:'Bebas Neue',sans-serif; font-size:28px; letter-spacing:1px;
  color:var(--ink); margin-bottom:6px;
}
.checkout-form-sub { font-size:14px; color:var(--warm); margin-bottom:32px; line-height:1.6; }

.c-form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.c-form-group { display:flex; flex-direction:column; gap:6px; margin-bottom:18px; }
.c-label {
  font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:var(--warm);
}
.c-input {
  padding:13px 16px; border:1.5px solid rgba(0,0,0,.1); border-radius:10px;
  font-size:15px; font-family:inherit; color:var(--ink); background:#fff;
  transition:border-color .2s, box-shadow .2s; outline:none;
}
.c-input:focus { border-color:var(--red); box-shadow:0 0 0 3px rgba(215,43,43,.1); }
.c-input.is-invalid { border-color:#e53e3e; }

/* ── PAYMENT TABS ── */
.pay-tabs-wrap { margin-bottom:20px; }
.pay-tabs-label {
  font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase;
  color:var(--warm); margin-bottom:12px; display:block;
}
.pay-tabs {
  display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; margin-bottom:20px;
}
.pay-tab {
  display:flex; flex-direction:column; align-items:center; gap:6px;
  padding:16px 10px; border:2px solid rgba(0,0,0,.09); border-radius:12px;
  cursor:pointer; background:none; font-family:inherit;
  transition:border-color .15s, background .15s;
}
.pay-tab:hover { border-color:rgba(215,43,43,.3); background:rgba(215,43,43,.02); }
.pay-tab.active { border-color:var(--red); background:rgba(215,43,43,.04); }
.pay-tab-icon { font-size:22px; line-height:1; }
.pay-tab-name { font-size:12px; font-weight:700; color:var(--ink); }
.pay-tab-desc { font-size:10px; color:var(--warm); text-align:center; line-height:1.3; }

.pay-panel { display:none; }
.pay-panel.active { display:block; }

.pay-info-box {
  background:var(--off); border-radius:12px; padding:20px 22px;
  font-size:14px; color:var(--warm); line-height:1.7; border-left:3px solid var(--red);
}
.pay-info-box strong { color:var(--ink); display:block; margin-bottom:6px; font-size:15px; }

.card-fields { display:grid; gap:14px; }
.card-fields-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.parcelas-select {
  padding:13px 16px; border:1.5px solid rgba(0,0,0,.1); border-radius:10px;
  font-size:15px; font-family:inherit; color:var(--ink); background:#fff;
  transition:border-color .2s; outline:none; width:100%;
  appearance:none; cursor:pointer;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat; background-position:right 14px center; background-size:16px;
  padding-right:40px;
}
.parcelas-select:focus { border-color:var(--red); box-shadow:0 0 0 3px rgba(215,43,43,.1); }

/* ── CPF/CNPJ TOGGLE ── */
.doc-toggle { display:flex; gap:8px; margin-bottom:10px; }
.doc-toggle-btn { flex:1; padding:9px 12px; border:1.5px solid rgba(0,0,0,.1); border-radius:8px; font-size:13px; font-weight:600; font-family:inherit; cursor:pointer; background:none; color:var(--warm); transition:all .15s; }
.doc-toggle-btn.active { border-color:var(--red); background:rgba(215,43,43,.05); color:var(--red); }
.doc-toggle-btn:hover:not(.active) { border-color:rgba(0,0,0,.2); color:var(--ink); }

/* ── SUBMIT ── */
.btn-checkout-submit {
  width:100%; display:flex; align-items:center; justify-content:center; gap:10px;
  background:var(--red); color:#fff;
  padding:17px; border-radius:12px; border:none; cursor:pointer;
  font-size:16px; font-weight:700; font-family:inherit;
  transition:background .2s, transform .15s; margin-top:28px;
}
.btn-checkout-submit:hover { background:#b91c1c; transform:translateY(-1px); }
.btn-checkout-submit svg { width:18px; height:18px; stroke:#fff; fill:none; stroke-width:2.5; }

.checkout-secure {
  display:flex; align-items:center; justify-content:center; gap:6px;
  font-size:12px; color:var(--warm); margin-top:14px;
}
.checkout-secure svg { width:13px; height:13px; stroke:var(--warm); fill:none; stroke-width:2; }

@media(max-width:900px) {
  .checkout-body { grid-template-columns:1fr; gap:32px; padding:40px 24px 80px; }
  .checkout-hero { padding:40px 24px 48px; }
  .checkout-form-card { padding:32px 24px; }
  .c-form-row { grid-template-columns:1fr; }
  .order-summary { position:static; }
}
@media(max-width:560px) {
  .pay-tabs { grid-template-columns:1fr; }
  .card-fields-row { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="checkout-hero">
  <div class="checkout-hero-inner">
    <div class="checkout-hero-pill">Finalizar Pedido</div>
    <h1>CONFIRMAR<br>PEDIDO</h1>
    <p>Preencha seus dados e escolha a forma de pagamento. Nossa equipe retorna em até 24h.</p>
  </div>
</div>

<div class="checkout-body">

  <!-- ORDER SUMMARY -->
  <div class="order-summary">
    <div class="order-summary-hdr">
      <span class="order-summary-title">SEU PEDIDO</span>
      <span style="font-size:12px;color:var(--warm);">{{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'itens' }}</span>
    </div>
    <div class="order-item-list">
      @foreach($cart as $item)
      <div class="order-item">
        <div>
          <div class="order-item-name">{{ $item['name'] }}</div>
          @if(!empty($item['variant']))
            <div class="order-item-variant">{{ $item['variant'] }}</div>
          @endif
        </div>
        <span class="order-item-qty">×{{ $item['qty'] }}</span>
      </div>
      @endforeach
    </div>
    <div class="order-footer">
      @if($cartSubtotal > 0)
        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px;">
          <span style="font-size:13px;font-weight:600;color:var(--ink);">Subtotal</span>
          <span style="font-size:16px;font-weight:800;color:var(--red);">R$ {{ number_format($cartSubtotal,2,',','.') }}</span>
        </div>
      @endif
      <p class="order-footer-note">
        @if($cartSubtotal > 0)
          <strong>Pagamento processado via Mercado Pago.</strong>
          Ambiente seguro com criptografia SSL.
        @else
          <strong>Orçamento — preços a confirmar.</strong>
          Nossa equipe entrará em contato em até 24h.
        @endif
      </p>
      <a href="{{ route('produtos') }}" class="order-back">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Voltar ao catálogo
      </a>
    </div>
  </div>

  <!-- CUSTOMER FORM -->
  <div class="checkout-form-card">
    <div class="checkout-form-title">SEUS DADOS</div>
    <p class="checkout-form-sub">Preencha corretamente — usaremos essas informações para confirmar seu pedido.</p>

    @if($errors->any())
      <div style="background:#fff5f5;border:1px solid #fed7d7;color:#c53030;padding:14px 18px;border-radius:10px;font-size:14px;margin-bottom:24px;">
        <ul style="padding-left:16px;margin:0;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('checkout.submit') }}" method="POST" id="checkoutSubmitForm">
      @csrf

      <div class="c-form-row">
        <div class="c-form-group">
          <label class="c-label" for="nome">Nome *</label>
          <input type="text" class="c-input @error('nome') is-invalid @enderror"
                 id="nome" name="nome" value="{{ old('nome') }}" autocomplete="given-name" required>
        </div>
        <div class="c-form-group">
          <label class="c-label" for="telefone">Telefone / WhatsApp *</label>
          <input type="tel" class="c-input @error('telefone') is-invalid @enderror"
                 id="telefone" name="telefone" value="{{ old('telefone') }}"
                 placeholder="(21) 99999-9999" autocomplete="tel" required>
        </div>
      </div>

      <div class="c-form-group">
        <label class="c-label" for="email">E-mail *</label>
        <input type="email" class="c-input @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
      </div>

      <div class="c-form-group">
        <label class="c-label" for="empresa">Empresa <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
        <input type="text" class="c-input" id="empresa" name="empresa" value="{{ old('empresa') }}" autocomplete="organization">
      </div>

      <div class="c-form-group">
        <span class="c-label">CPF / CNPJ *</span>
        <div class="doc-toggle">
          <button type="button" class="doc-toggle-btn {{ old('document_type','cpf') === 'cpf' ? 'active' : '' }}" onclick="setDocType('cpf',this)">Pessoa Física — CPF</button>
          <button type="button" class="doc-toggle-btn {{ old('document_type') === 'cnpj' ? 'active' : '' }}"  onclick="setDocType('cnpj',this)">Pessoa Jurídica — CNPJ</button>
        </div>
        <input type="hidden" name="document_type" id="documentType" value="{{ old('document_type','cpf') }}">
        <input type="text" class="c-input @error('document_number') is-invalid @enderror"
        id="documentNumber" name="document_number"
               value="{{ old('document_number') }}"
               placeholder="{{ old('document_type') === 'cnpj' ? '00.000.000/0000-00' : '000.000.000-00' }}"
               maxlength="{{ old('document_type') === 'cnpj' ? 18 : 14 }}"
               oninput="maskDoc(this)" autocomplete="off" required>
        @error('document_number')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
      </div>

      <!-- PAYMENT METHOD -->
      <div class="pay-tabs-wrap" style="margin-bottom: 28px;">
        <span class="pay-tabs-label">Forma de Pagamento</span>
        <div class="pay-info-box" style="background: rgba(220, 38, 38, 0.04); border: 1px solid rgba(220, 38, 38, 0.15); padding: 18px; border-radius: 12px; margin-top: 8px;">
          <strong style="color: var(--red); display: block; margin-bottom: 6px; font-size: 14px;">Consultar Pagamento</strong>
          <span style="font-size: 13.5px; color: var(--warm); line-height: 1.6; display: block;">
            Para maior segurança, a forma de pagamento (PIX, Boleto ou Cartão) será combinada diretamente com a nossa equipe comercial logo após a finalização do seu pedido. Finalize o formulário abaixo e nós entraremos em contato ou você poderá nos chamar direto no WhatsApp.
          </span>
        </div>
        <input type="hidden" name="pagamento" id="pagamentoInput" value="consulta">
      </div>

      {{-- ADDRESS SECTION --}}
      <div style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span class="c-label" style="margin:0;">Endereço de Entrega</span>
          @if($defaultAddress)
            <span style="font-size:11px;color:#16a34a;font-weight:600;">
              ✓ Carregado do seu perfil
            </span>
          @else
            <span id="addr-required-note" style="font-size:11px;color:var(--warm);display:none;">
              * Obrigatório para Boleto
            </span>
          @endif
        </div>
        @if($defaultAddress && !old('rua'))
          <div style="padding:12px 16px;background:rgba(22,163,74,.06);border:1px solid rgba(22,163,74,.2);border-radius:10px;font-size:13px;color:#15803d;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <span>{{ $defaultAddress->full_address }}</span>
            <button type="button" onclick="toggleAddrFields()" style="font-size:12px;font-weight:600;color:#15803d;background:none;border:none;cursor:pointer;white-space:nowrap;">Alterar</button>
          </div>
          <div id="addr-fields" style="display:none;">
        @else
          <div id="addr-fields">
        @endif
          <div class="c-form-row">
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">CEP</label>
              <input type="text" class="c-input @error('cep') is-invalid @enderror" name="cep"
                     value="{{ old('cep', $defaultAddress?->zipcode) }}" placeholder="00000-000" maxlength="9">
            </div>
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">Número</label>
              <input type="text" class="c-input @error('numero') is-invalid @enderror" name="numero"
                     value="{{ old('numero', $defaultAddress?->number) }}" placeholder="123">
            </div>
          </div>
          <div class="c-form-group" style="margin-bottom:0;margin-top:14px;">
            <label class="c-label">Rua / Avenida</label>
            <input type="text" class="c-input @error('rua') is-invalid @enderror" name="rua"
                   value="{{ old('rua', $defaultAddress?->street) }}" placeholder="Rua das Flores, Av. Paulista…">
          </div>
          <div class="c-form-row" style="margin-top:14px;">
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">Bairro</label>
              <input type="text" class="c-input @error('bairro') is-invalid @enderror" name="bairro"
                     value="{{ old('bairro', $defaultAddress?->neighborhood) }}" placeholder="Centro">
            </div>
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">Complemento <small style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</small></label>
              <input type="text" class="c-input" name="complemento"
                     value="{{ old('complemento', $defaultAddress?->complement) }}" placeholder="Apto 4, Bloco B…">
            </div>
          </div>
          <div class="c-form-row" style="margin-top:14px;">
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">Cidade</label>
              <input type="text" class="c-input @error('cidade') is-invalid @enderror" name="cidade"
                     value="{{ old('cidade', $defaultAddress?->city) }}" placeholder="Rio de Janeiro">
            </div>
            <div class="c-form-group" style="margin-bottom:0;">
              <label class="c-label">Estado (UF)</label>
              <input type="text" class="c-input @error('estado') is-invalid @enderror" name="estado"
                     value="{{ old('estado', $defaultAddress?->state) }}" placeholder="RJ" maxlength="2" style="text-transform:uppercase;">
            </div>
          </div>
        </div>

        @if(!$defaultAddress)
          <p style="font-size:12px;color:var(--warm);margin-top:10px;line-height:1.5;">
            Salve endereços no seu
            @auth
              <a href="{{ route('profile') }}#addresses" style="color:var(--red);font-weight:600;">perfil</a>
            @else
              <a href="{{ route('register') }}" style="color:var(--red);font-weight:600;">perfil</a>
            @endauth
            para preenchimento automático nos próximos pedidos.
          </p>
        @endif
      </div>

      <div class="c-form-group">
        <label class="c-label" for="mensagem">Observações <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
        <textarea class="c-input" id="mensagem" name="mensagem" rows="3"
                  style="resize:vertical;min-height:80px;" placeholder="Prazo necessário, local de entrega, dúvidas...">{{ old('mensagem') }}</textarea>
      </div>

      <button type="submit" class="btn-checkout-submit">
        <svg viewBox="0 0 24 24"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/></svg>
        Confirmar Pedido
      </button>
    </form>

    <p class="checkout-secure">
      <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
      Seus dados estão protegidos e não serão compartilhados com terceiros.
    </p>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
/* ── Payment tab selector ── */
function selectPay(method, btn) {
  document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel-' + method).classList.add('active');
  document.getElementById('pagamentoInput').value = method;
  // Show "required" note for boleto
  const note = document.getElementById('addr-required-note');
  if (note) note.style.display = method === 'boleto' ? 'inline' : 'none';
  // Auto-expand address fields when boleto is selected
  if (method === 'boleto') {
    const fields = document.getElementById('addr-fields');
    if (fields && fields.style.display === 'none') toggleAddrFields();
  }
}

/* ── Address fields toggle (when default address is loaded) ── */
function toggleAddrFields() {
  const f = document.getElementById('addr-fields');
  if (!f) return;
  f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

/* ── CPF / CNPJ ── */
function setDocType(type, btn) {
  document.querySelectorAll('.doc-toggle-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('documentType').value = type;
  const input = document.getElementById('documentNumber');
  input.value = '';
  input.placeholder = type === 'cpf' ? '000.000.000-00' : '00.000.000/0000-00';
  input.maxLength   = type === 'cpf' ? 14 : 18;
  input.focus();
}

function maskDoc(el) {
  const type = document.getElementById('documentType').value;
  let v = el.value.replace(/\D/g, '');
  if (type === 'cpf') {
    v = v.substring(0, 11);
    v = v.replace(/(\d{3})(\d)/,      '$1.$2')
         .replace(/(\d{3})(\d)/,      '$1.$2')
         .replace(/(\d{3})(\d{1,2})$/,'$1-$2');
  } else {
    v = v.substring(0, 14);
    v = v.replace(/(\d{2})(\d)/,      '$1.$2')
         .replace(/(\d{3})(\d)/,      '$1.$2')
         .replace(/(\d{3})(\d)/,      '$1/$2')
         .replace(/(\d{4})(\d{1,2})$/,'$1-$2');
  }
  el.value = v;
}

/* ── Card input formatting ── */
function fmtCard(el) {
  let v = el.value.replace(/\D/g,'').substring(0,16);
  el.value = v.replace(/(.{4})/g,'$1 ').trim();
}

function fmtExpiry(el) {
  let v = el.value.replace(/\D/g,'').substring(0,4);
  if (v.length >= 3) v = v.substring(0,2) + '/' + v.substring(2);
  el.value = v;
}

/* ── Mercado Pago card tokenization ── */
const _mpPublicKey   = '{{ config("services.mercadopago.public_key") }}';
const _cartTotal     = '{{ number_format($cartSubtotal, 2, ".", "") }}';
let   _mpCardForm    = null;
let   _mpSkipIntercept = false;

function mountMPCardForm() {
  if (_mpCardForm || !_mpPublicKey) return;
  try {
    const mp = new MercadoPago(_mpPublicKey, { locale: 'pt-BR' });
    _mpCardForm = mp.cardForm({
      amount: _cartTotal,
      iframe: false,
      form: {
        id: 'checkoutSubmitForm',
        cardNumber:     { id: 'form-checkout__cardNumber',     placeholder: '0000 0000 0000 0000' },
        expirationDate: { id: 'form-checkout__expirationDate', placeholder: 'MM/AA' },
        securityCode:   { id: 'form-checkout__securityCode',   placeholder: 'CVV' },
        cardholderName: { id: 'form-checkout__cardholderName' },
        issuer:         { id: 'form-checkout__issuer' },
        installments:   { id: 'form-checkout__installments' },
      },
      callbacks: {
        onFormMounted: err => { if (err) console.warn('MP mount:', err); },
        onSubmit: event => {
          event.preventDefault();
          if (_mpSkipIntercept) { _mpSkipIntercept = false; return; }

          const payMethod = document.getElementById('pagamentoInput').value;
          if (payMethod !== 'cartao') {
            // PIX / Boleto — bypass tokenisation and submit directly
            _mpSkipIntercept = true;
            document.getElementById('checkoutSubmitForm').submit();
            return;
          }

          // Cartão — get token from MP
          const data = _mpCardForm.getCardFormData();
          const errEl = document.getElementById('mp-card-error');
          if (!data.token) {
            errEl.textContent = 'Não foi possível processar o cartão. Verifique os dados e tente novamente.';
            errEl.style.display = 'block';
            return;
          }
          errEl.style.display = 'none';
          document.getElementById('mpCardToken').value         = data.token;
          document.getElementById('mpPaymentMethodId').value   = data.paymentMethodId || '';
          _mpSkipIntercept = true;
          document.getElementById('checkoutSubmitForm').submit();
        },
        onError: errs => {
          console.error('MP CardForm errors:', errs);
        },
      },
    });
  } catch(e) {
    console.error('MP init error:', e);
  }
}

// For PIX/Boleto: ensure form submits normally even if MP intercepts
document.getElementById('checkoutSubmitForm').addEventListener('submit', function(e) {
  const payMethod = document.getElementById('pagamentoInput').value;
  if (payMethod !== 'cartao' && !_mpCardForm) return; // no MP loaded, submit normally
});
</script>
@endpush
