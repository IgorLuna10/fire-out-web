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
  } else {
      echo "<p>ℹ OPcache não está ativo nesta versão do PHP.</p>";
  }

  $dir = __DIR__;
  $homeVendor = '/home/fireout/fire-out/vendor/autoload.php';
  $relVendor = $dir . '/../fire-out/vendor/autoload.php';

  echo "<h3>Verificação de Pastas:</h3>";
  echo "<p><strong>__DIR__:</strong> <code>$dir</code></p>";

  echo "<p><strong>1. /home/fireout/fire-out/vendor/autoload.php:</strong> ";
  if (file_exists($homeVendor)) {
      echo "<span class='ok'>ENCONTRADO ✓</span> (" . filesize($homeVendor) . " bytes)</p>";
  } else {
      echo "<span class='bad'>NÃO ENCONTRADO ✗</span></p>";
  }

  echo "<p><strong>2. Caminho relativo (../fire-out/vendor/autoload.php):</strong> ";
  if (file_exists($relVendor)) {
      echo "<span class='ok'>ENCONTRADO ✓</span></p>";
  } else {
      echo "<span class='bad'>NÃO ENCONTRADO ✗</span></p>";
  }

  $indexFile = $dir . '/index.php';
  if (file_exists($indexFile)) {
      $lines = file($indexFile);
      echo "<h3>Conteúdo de /www/index.php (linhas 1 a 20):</h3>";
      echo "<pre>";
      for ($i = 0; $i < min(20, count($lines)); $i++) {
          echo ($i + 1) . ": " . htmlspecialchars($lines[$i]);
      }
      echo "</pre>";
  }
  ?>

  <br>
  <a class="btn" href="/">Abrir Site Principal (fireout.com.br) →</a>
</div>
</body>
</html>
