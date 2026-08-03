@extends('layouts.app')
@section('title', 'Fire Out — Minha Conta')

@push('styles')
<style>
.profile-wrap { max-width:1100px; margin:0 auto; padding:calc(var(--nav-h) + 48px) 48px 120px; }
.profile-header { margin-bottom:40px; }
.profile-header h1 { font-family:'Bebas Neue',sans-serif; font-size:42px; letter-spacing:1px; color:var(--ink); }
.profile-header p { font-size:15px; color:var(--warm); margin-top:4px; }
.profile-grid { display:grid; grid-template-columns:260px 1fr; gap:32px; align-items:start; }
.profile-nav { background:#fff; border-radius:16px; border:1.5px solid rgba(0,0,0,.07); overflow:hidden; position:sticky; top:calc(var(--nav-h) + 20px); }
.profile-nav-item { display:flex; align-items:center; gap:10px; padding:14px 20px; font-size:14px; font-weight:500; color:var(--warm); border-bottom:1px solid rgba(0,0,0,.05); cursor:pointer; transition:background .15s, color .15s; }
.profile-nav-item:last-child { border-bottom:none; }
.profile-nav-item:hover { background:var(--off); color:var(--ink); }
.profile-nav-item.active { color:var(--red); font-weight:700; background:rgba(215,43,43,.04); }
.profile-nav-item svg { width:16px; height:16px; stroke:currentColor; fill:none; stroke-width:2; flex-shrink:0; }
.profile-section { display:none; }
.profile-section.active { display:block; }
.card { background:#fff; border-radius:16px; border:1.5px solid rgba(0,0,0,.07); padding:28px; margin-bottom:20px; }
.card-title { font-family:'Bebas Neue',sans-serif; font-size:20px; letter-spacing:1px; color:var(--ink); margin-bottom:20px; padding-bottom:12px; border-bottom:1.5px solid rgba(0,0,0,.06); }
.form-row2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-group { margin-bottom:16px; }
.form-label { display:block; font-size:11px; font-weight:700; letter-spacing:.5px; text-transform:uppercase; color:var(--ink); margin-bottom:6px; }
.form-input { width:100%; padding:10px 13px; border:1.5px solid rgba(0,0,0,.1); border-radius:8px; font-size:14px; font-family:inherit; color:var(--ink); outline:none; transition:border-color .15s; }
.form-input:focus { border-color:var(--red); }
.form-input.error { border-color:#ef4444; }
.form-error { font-size:12px; color:#ef4444; margin-top:4px; }
.btn-save { padding:10px 24px; background:var(--red); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:inherit; cursor:pointer; transition:background .15s; }
.btn-save:hover { background:var(--red-d); }
.btn-ghost-sm { padding:10px 20px; background:none; border:1.5px solid rgba(0,0,0,.12); color:var(--warm); border-radius:8px; font-size:13px; font-weight:600; font-family:inherit; cursor:pointer; transition:all .15s; }
.btn-ghost-sm:hover { border-color:var(--ink); color:var(--ink); }
.btn-danger-sm { padding:10px 20px; background:none; border:1.5px solid rgba(239,68,68,.25); color:#ef4444; border-radius:8px; font-size:13px; font-weight:600; font-family:inherit; cursor:pointer; transition:all .15s; }
.btn-danger-sm:hover { background:rgba(239,68,68,.06); }
.alert-success { padding:10px 14px; background:rgba(22,163,74,.08); border:1px solid rgba(22,163,74,.2); border-radius:8px; font-size:13px; color:#15803d; margin-bottom:16px; }

/* Addresses */
.address-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px; }
.address-card { padding:16px 18px; border:1.5px solid rgba(0,0,0,.08); border-radius:12px; position:relative; }
.address-card.default { border-color:var(--red); background:rgba(215,43,43,.025); }
.address-label { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); margin-bottom:4px; }
.address-card.default .address-label { color:var(--red); }
.address-text { font-size:13px; color:var(--ink); line-height:1.6; }
.address-actions { display:flex; gap:8px; margin-top:12px; }
.address-badge { position:absolute; top:10px; right:12px; font-size:10px; font-weight:700; letter-spacing:.5px; background:var(--red); color:#fff; padding:2px 8px; border-radius:4px; text-transform:uppercase; }

/* Orders table */
.orders-table { width:100%; border-collapse:collapse; font-size:14px; }
.orders-table th { padding:10px 14px; text-align:left; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--warm); border-bottom:1.5px solid rgba(0,0,0,.07); }
.orders-table td { padding:14px; border-bottom:1px solid rgba(0,0,0,.05); color:var(--ink); }
.orders-table tr:last-child td { border-bottom:none; }
.status-pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }

@media(max-width:900px) {
  .profile-grid { grid-template-columns:1fr; }
  .profile-nav { position:static; display:flex; overflow-x:auto; border-radius:12px; }
  .profile-nav-item { flex-shrink:0; border-bottom:none; border-right:1px solid rgba(0,0,0,.05); }
  .form-row2 { grid-template-columns:1fr; }
  .address-grid { grid-template-columns:1fr; }
}
@media(max-width:600px) { .profile-wrap { padding:calc(var(--nav-h) + 24px) 20px 80px; } }
</style>
@endpush

@section('content')
<div class="profile-wrap">
  <div class="profile-header">
    <h1>OLÁ, {{ strtoupper(explode(' ', $user->name)[0]) }}</h1>
    <p>Gerencie seus dados, endereços e acompanhe seus pedidos.</p>
  </div>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  <div class="profile-grid">
    <!-- SIDE NAV -->
    <nav class="profile-nav">
      <div class="profile-nav-item active" onclick="showTab('info',this)">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Meus Dados
      </div>
      <div class="profile-nav-item" onclick="showTab('addresses',this)">
        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Endereços
      </div>
      <div class="profile-nav-item" onclick="showTab('orders',this)">
        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
        Meus Pedidos
      </div>
      <div class="profile-nav-item" onclick="showTab('password',this)">
        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Alterar Senha
      </div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="profile-nav-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:var(--warm);">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Sair
        </button>
      </form>
    </nav>

    <!-- CONTENT -->
    <div>

      <!-- INFO TAB -->
      <div class="profile-section active" id="tab-info">
        <div class="card">
          <div class="card-title">MEUS DADOS</div>
          <form method="POST" action="{{ route('profile.info') }}">
            @csrf
            <div class="form-row2">
              <div class="form-group">
                <label class="form-label">Nome completo</label>
                <input class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                <label class="form-label">Telefone / WhatsApp</label>
                <input class="form-input" type="tel" name="phone" value="{{ old('phone', $user->phone) }}">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">E-mail</label>
              <input class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
              @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-save">Salvar Dados</button>
          </form>
        </div>
      </div>

      <!-- ADDRESSES TAB -->
      <div class="profile-section" id="tab-addresses">
        <div class="card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:12px;border-bottom:1.5px solid rgba(0,0,0,.06);">
            <span class="card-title" style="margin:0;padding:0;border:none;">MEUS ENDEREÇOS</span>
            <button class="btn-save" onclick="toggleAddrForm()" id="btnAddAddr" style="display:flex;align-items:center;gap:7px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Adicionar Endereço
            </button>
          </div>

          @if($addresses->isNotEmpty())
            <div class="address-grid">
              @foreach($addresses as $addr)
              <div class="address-card {{ $addr->is_default ? 'default' : '' }}">
                @if($addr->is_default)<span class="address-badge">Padrão</span>@endif
                <div class="address-label">{{ $addr->label }}</div>
                <div class="address-text">{{ $addr->full_address }}</div>
                <div class="address-actions">
                  @if(!$addr->is_default)
                  <form method="POST" action="{{ route('profile.address.default', $addr) }}">@csrf
                    <button type="submit" class="btn-ghost-sm">Definir padrão</button>
                  </form>
                  @endif
                  <form method="POST" action="{{ route('profile.address.delete', $addr) }}">@csrf @method('DELETE')
                    <button type="submit" class="btn-danger-sm" onclick="return confirm('Remover endereço?')">Remover</button>
                  </form>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <div style="text-align:center;padding:32px 20px;background:var(--off);border-radius:12px;margin-bottom:20px;">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,.2)" stroke-width="1.5" style="display:block;margin:0 auto 12px;"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <p style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px;">Nenhum endereço cadastrado</p>
              <p style="font-size:13px;color:var(--warm);margin-bottom:16px;">Adicione um endereço para agilizar seus pedidos.</p>
              <button class="btn-save" onclick="toggleAddrForm()" style="display:inline-flex;align-items:center;gap:7px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Adicionar meu primeiro endereço
              </button>
            </div>
          @endif

          {{-- Add address form (hidden by default, shown via button) --}}
          <div id="addr-form" style="display:none;border-top:1.5px solid rgba(0,0,0,.06);padding-top:20px;margin-top:4px;">
            <div style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:16px;">Novo endereço</div>
            <form method="POST" action="{{ route('profile.address.store') }}">
              @csrf
              <div class="form-row2">
                <div class="form-group">
                  <label class="form-label">Rótulo</label>
                  <input class="form-input" type="text" name="label" placeholder="Ex: Casa, Obra, Empresa" value="Casa">
                </div>
                <div class="form-group">
                  <label class="form-label">CEP</label>
                  <input class="form-input" type="text" name="zipcode" placeholder="00000-000" maxlength="9">
                </div>
              </div>
              <div class="form-row2">
                <div class="form-group">
                  <label class="form-label">Rua / Avenida</label>
                  <input class="form-input" type="text" name="street" placeholder="Rua das Flores">
                </div>
                <div class="form-group">
                  <label class="form-label">Número</label>
                  <input class="form-input" type="text" name="number" placeholder="123">
                </div>
              </div>
              <div class="form-row2">
                <div class="form-group">
                  <label class="form-label">Complemento <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:10px;">(opcional)</span></label>
                  <input class="form-input" type="text" name="complement" placeholder="Apto 4, Bloco B…">
                </div>
                <div class="form-group">
                  <label class="form-label">Bairro</label>
                  <input class="form-input" type="text" name="neighborhood" placeholder="Centro">
                </div>
              </div>
              <div class="form-row2">
                <div class="form-group">
                  <label class="form-label">Cidade</label>
                  <input class="form-input" type="text" name="city" placeholder="Rio de Janeiro">
                </div>
                <div class="form-group">
                  <label class="form-label">Estado (UF)</label>
                  <input class="form-input" type="text" name="state" placeholder="RJ" maxlength="2" style="text-transform:uppercase;">
                </div>
              </div>
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                <input type="checkbox" name="is_default" id="default_new" value="1" style="accent-color:var(--red);" {{ $addresses->isEmpty() ? 'checked' : '' }}>
                <label for="default_new" style="font-size:13px;color:var(--warm);">Definir como endereço padrão</label>
              </div>
              <div style="display:flex;gap:10px;">
                <button type="submit" class="btn-save">Salvar Endereço</button>
                <button type="button" class="btn-ghost-sm" onclick="toggleAddrForm()">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- ORDERS TAB -->
      <div class="profile-section" id="tab-orders">
        <div class="card">
          <div class="card-title">MEUS PEDIDOS</div>
          @if($orders->isEmpty())
            <p style="color:var(--warm);font-size:14px;">Nenhum pedido realizado ainda. <a href="{{ route('produtos') }}" style="color:var(--red);font-weight:600;">Ver produtos →</a></p>
          @else
          <table class="orders-table">
            <thead>
              <tr>
                <th>Nº Pedido</th>
                <th>Data</th>
                <th>Itens</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
              <tr>
                <td style="font-weight:700;color:var(--red);">{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->items->sum('qty') }} {{ $order->items->sum('qty') === 1 ? 'item' : 'itens' }}</td>
                <td style="font-weight:600;">{{ $order->total_display }}</td>
                <td><span class="status-pill" style="background:{{ $order->status_color }}20;color:{{ $order->status_color }};">{{ $order->status_label }}</span></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>
      </div>

      <!-- PASSWORD TAB -->
      <div class="profile-section" id="tab-password">
        <div class="card">
          <div class="card-title">ALTERAR SENHA</div>
          <form method="POST" action="{{ route('profile.password') }}" style="max-width:400px;">
            @csrf
            <div class="form-group">
              <label class="form-label">Senha atual</label>
              <input class="form-input {{ $errors->has('current_password') ? 'error' : '' }}" type="password" name="current_password">
              @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label">Nova senha</label>
              <input class="form-input {{ $errors->has('password') ? 'error' : '' }}" type="password" name="password" placeholder="Min. 8 caracteres">
              @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label">Confirmar nova senha</label>
              <input class="form-input" type="password" name="password_confirmation">
            </div>
            <button type="submit" class="btn-save">Alterar Senha</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function showTab(tab, el) {
  document.querySelectorAll('.profile-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.profile-nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  el.classList.add('active');
}

function toggleAddrForm() {
  const form = document.getElementById('addr-form');
  const btn  = document.getElementById('btnAddAddr');
  const open = form.style.display === 'none';
  form.style.display = open ? 'block' : 'none';
  if (btn) btn.textContent = open ? '✕ Fechar' : '+ Adicionar Endereço';
  if (open) form.querySelector('input[name="label"]').focus();
}

// Auto-open form if there was a validation error on address fields
@if($errors->hasAny(['street','number','neighborhood','city','state','zipcode','label']))
  document.addEventListener('DOMContentLoaded', () => {
    showTab('addresses', document.querySelector('[onclick*="addresses"]'));
    toggleAddrForm();
  });
@endif
</script>
@endpush
