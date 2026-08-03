<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirme seu e-mail — Fire Out</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#F5F2ED; font-family:'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing:antialiased; }
    .wrapper { max-width:580px; margin:40px auto; padding:0 20px 60px; }

    /* header */
    .header { background:#1A1A1A; border-radius:16px 16px 0 0; padding:32px 40px; text-align:center; }
    .brand  { font-size:28px; font-weight:900; letter-spacing:4px; color:#D72B2B; text-transform:uppercase; }

    /* card */
    .card { background:#ffffff; padding:44px 48px; border-radius:0 0 16px 16px; border:1px solid rgba(0,0,0,.07); border-top:none; }
    .greeting { font-size:22px; font-weight:700; color:#1A1A1A; margin-bottom:14px; }
    .body-text { font-size:15px; color:#6B6259; line-height:1.75; margin-bottom:32px; }

    /* button */
    .btn-wrap { text-align:center; margin-bottom:36px; }
    .btn {
      display:inline-block; background:#D72B2B; color:#ffffff !important;
      text-decoration:none; padding:16px 40px; border-radius:10px;
      font-size:16px; font-weight:700; letter-spacing:.3px;
    }

    /* fallback link */
    .fallback { font-size:12px; color:#8A8278; line-height:1.65; padding:18px 20px; background:#F5F2ED; border-radius:10px; word-break:break-all; }
    .fallback a { color:#D72B2B; }

    /* footer */
    .footer { text-align:center; margin-top:28px; font-size:12px; color:#8A8278; line-height:1.7; }
    .footer strong { color:#1A1A1A; }

    @media(max-width:480px) {
      .card   { padding:32px 24px; }
      .header { padding:24px 24px; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="brand">Fire Out</div>
    </div>
    <div class="card">
      <p class="greeting">Olá, {{ explode(' ', $name)[0] }}!</p>
      <p class="body-text">
        Obrigado por criar sua conta na <strong>Fire Out</strong>.<br>
        Para confirmar seu cadastro e acessar todos os recursos, clique no botão abaixo:
      </p>

      <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">Verificar meu e-mail</a>
      </div>

      <div class="fallback">
        Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br>
        <a href="{{ $url }}">{{ $url }}</a>
      </div>
    </div>

    <div class="footer">
      Este link expira em <strong>60 minutos</strong>.<br>
      Se você não criou uma conta, ignore este e-mail.<br><br>
      Fire Out — Proteção Contra Incêndio<br>
      <a href="https://fireout.com.br" style="color:#D72B2B;">fireout.com.br</a>
    </div>
  </div>
</body>
</html>
