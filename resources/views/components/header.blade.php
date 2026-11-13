<style>
.nav-logo-img { height:94px; width:auto; max-width:300px; aspect-ratio:1/1; display:block; object-fit:contain; }
</style>

<nav class="fo-nav">
  <div class="fo-nav-inner">
    <a href="{{ route('home') }}" class="nav-logo" style="flex-shrink:0;">
      <img src="{{ asset('img/logo/light-logo.webp') }}" alt="Fire Out" class="nav-logo-img" width="94" height="94">
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}"          class="{{ request()->routeIs('home')          ? 'active' : '' }}">Fire Out</a>
      <a href="{{ route('porta-corta-fogo') }}" class="{{ request()->routeIs('porta-corta-fogo') ? 'active' : '' }}">Portas Corta-Fogo</a>
      <a href="{{ route('produtos') }}"      class="{{ request()->routeIs('produtos')      ? 'active' : '' }}">Produtos</a>
      <a href="{{ route('servicos') }}"      class="{{ request()->routeIs('servicos')      ? 'active' : '' }}">Serviços</a>
      <a href="{{ route('contato') }}"       class="{{ request()->routeIs('contato')       ? 'active' : '' }}">Contato</a>
      @auth
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:rgba(215,43,43,.08);color:var(--red);padding:7px 14px;border-radius:6px;opacity:1!important;">Admin</a>
          <form method="POST" action="{{ route('logout') }}" style="display:contents;">
            @csrf
            <button type="submit" style="font-size:14px;font-weight:500;color:var(--ink);opacity:.65;transition:opacity .2s;background:none;border:none;cursor:pointer;padding:0;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity='.65'">Sair</button>
          </form>
        @endif
      @endauth
      <a href="{{ route('contato') }}" class="btn-nav">Solicitar Orçamento</a>
    </div>
    <button class="nav-hamburger" aria-label="Menu principal" aria-expanded="false" aria-controls="mobile-nav"><span></span><span></span><span></span></button>
  </div>
</nav>
<nav class="nav-mobile-menu" id="mobile-nav" aria-label="Navegação móvel">
  <a href="{{ route('home') }}">Fire Out</a>
  <a href="{{ route('porta-corta-fogo') }}">Portas Corta-Fogo</a>
  <a href="{{ route('produtos') }}">Produtos</a>
  <a href="{{ route('servicos') }}">Serviços</a>
  <a href="{{ route('contato') }}">Contato</a>
  @auth
    @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.dashboard') }}">Painel Admin</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="width:100%;text-align:left;padding:16px 28px;font-size:16px;font-weight:500;color:var(--warm);background:none;border:none;border-bottom:1px solid rgba(0,0,0,.06);cursor:pointer;font-family:inherit;">Sair</button>
      </form>
    @endif
  @endauth
  <a href="{{ route('contato') }}" class="btn-nav-mob">Solicitar Orçamento</a>
</nav>
