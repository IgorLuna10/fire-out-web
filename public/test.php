<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Fire Out — Diagnóstico do Servidor</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f1117; color: #e2e8f0; padding: 40px 20px; }
  .box { max-width: 700px; margin: 0 auto; background: #1a1e29; border: 1px solid #334155; border-radius: 12px; padding: 30px; }
  h2 { color: #f87171; margin-top: 0; }
  .ok { color: #4ade80; font-weight: bold; }
  .bad { color: #f87171; font-weight: bold; }
  pre { background: #0b0d13; padding: 12px; border-radius: 6px; overflow-x: auto; color: #cbd5e1; font-size: 13px; }
  a.btn { display: inline-block; background: #e11d48; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; }
</style>
</head>
<body>
<div class="box">
  <h2>Fire Out — Diagnóstico e Limpeza de Cache</h2>

  <?php
  // 1. Reset OPcache
  if (function_exists('opcache_reset')) {
      $reset = @opcache_reset();
      echo "<p class='ok'>✓ OPcache do PHP limpo com sucesso! (" . ($reset ? 'true' : 'false') . ")</p>";
  }

  $dir = __DIR__;
  $homeVendor = '/home/fireout/fire-out/vendor/autoload.php';

  echo "<h3>Verificação de Arquivos:</h3>";
  echo "<p><strong>1. Autoload:</strong> ";
  if (file_exists($homeVendor)) {
      echo "<span class='ok'>ENCONTRADO ✓</span> (" . filesize($homeVendor) . " bytes)</p>";
  } else {
      echo "<span class='bad'>NÃO ENCONTRADO ✗</span></p>";
  }

  // 2. Check CSS manifest and compiled file
  $manifestFile = $dir . '/build/manifest.json';
  echo "<p><strong>2. Vite Manifest (/www/build/manifest.json):</strong> ";
  if (file_exists($manifestFile)) {
      $manifest = json_decode(file_get_contents($manifestFile), true);
      $cssRel = $manifest['resources/css/app.css']['file'] ?? null;
      echo "<span class='ok'>ENCONTRADO ✓</span></p>";
      echo "<p>— Arquivo CSS apontado: <code>" . htmlspecialchars($cssRel ?? 'N/A') . "</code><br>";
      $fullCssPath = $dir . '/build/' . $cssRel;
      if ($cssRel && file_exists($fullCssPath)) {
          echo "— Arquivo no disco: <span class='ok'>ENCONTRADO ✓</span> (" . filesize($fullCssPath) . " bytes)</p>";
      } else {
          echo "— Arquivo no disco: <span class='bad'>NÃO ENCONTRADO NO DISCO ✗</span> (Envie a pasta public/build para /www/build!)</p>";
      }
  } else {
      echo "<span class='bad'>NÃO ENCONTRADO ✗</span> (Envie a pasta public/build para /www/build!)</p>";
  }

  // 3. Check HTTPS detection
  $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME'] ?? 'http';
  $https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || $proto === 'https';
  echo "<p><strong>3. Protocolo HTTPS:</strong> " . ($https ? "<span class='ok'>ATIVO (https) ✓</span>" : "<span class='bad'>INATIVO (http) - Pode causar bloqueio de CSS</span>") . "</p>";
  ?>

  <br>
  <a class="btn" href="/">Abrir Site Principal (fireout.com.br) →</a>
</div>
</body>
</html>
