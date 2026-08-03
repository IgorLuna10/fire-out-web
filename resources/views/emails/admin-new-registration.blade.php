<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Novo Cadastro — Fire Out</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#F5F2ED; font-family:'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing:antialiased; }
    .wrapper { max-width:560px; margin:40px auto; padding:0 20px 60px; }

    .header { background:#1A1A1A; border-radius:16px 16px 0 0; padding:28px 40px; display:flex; align-items:center; justify-content:space-between; }
    .brand  { font-size:24px; font-weight:900; letter-spacing:4px; color:#D72B2B; text-transform:uppercase; }
    .header-label { font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,.35); }

    .card { background:#ffffff; padding:40px 44px; border-radius:0 0 16px 16px; border:1px solid rgba(0,0,0,.07); border-top:none; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#D72B2B; margin-bottom:16px; }
    .title { font-size:22px; font-weight:700; color:#1A1A1A; margin-bottom:20px; }

    .info-table { width:100%; border-collapse:collapse; font-size:14px; margin-bottom:28px; }
    .info-table tr { border-bottom:1px solid rgba(0,0,0,.06); }
    .info-table tr:last-child { border-bottom:none; }
    .info-table td { padding:10px 0; vertical-align:top; }
    .info-table td:first-child { color:#8A8278; font-weight:600; width:110px; }
    .info-table td:last-child { color:#1A1A1A; font-weight:500; }

    .btn-wrap { text-align:center; }
    .btn { display:inline-block; background:#1A1A1A; color:#ffffff !important; text-decoration:none; padding:14px 36px; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:.3px; }

    .footer { text-align:center; margin-top:28px; font-size:12px; color:#8A8278; line-height:1.7; }

    @media(max-width:480px) {
      .card   { padding:28px 24px; }
      .header { padding:22px 24px; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="brand">Fire Out</div>
      <div class="header-label">Admin</div>
    </div>
    <div class="card">
      <div class="section-label">Novo Cadastro</div>
      <div class="title">Um novo cliente se registrou</div>

      <table class="info-table">
        <tr>
          <td>Nome</td>
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <td>E-mail</td>
          <td>{{ $user->email }}</td>
        </tr>
        <tr>
          <td>Telefone</td>
          <td>{{ $user->phone ?? '—' }}</td>
        </tr>
        <tr>
          <td>Cadastrado em</td>
          <td>{{ $user->created_at->format('d/m/Y \à\s H:i') }}</td>
        </tr>
        <tr>
          <td>E-mail verificado</td>
          <td>{{ $user->hasVerifiedEmail() ? 'Sim' : 'Pendente' }}</td>
        </tr>
      </table>

      <div class="btn-wrap">
        <a href="{{ config('app.url') }}/admin" class="btn">Ver no Painel Admin</a>
      </div>
    </div>

    <div class="footer">
      Fire Out — Proteção Contra Incêndio<br>
      <a href="{{ config('app.url') }}" style="color:#D72B2B;">fireout.com.br</a>
    </div>
  </div>
</body>
</html>
