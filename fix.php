<?php
// Access: yoursite.com/fix.php?run=fireout2025
if (($_GET['run'] ?? '') !== 'fireout2025') {
    http_response_code(403);
    exit('Forbidden');
}

echo '<pre>';

// ── Locate project root ───────────────────────────────────────────────────────
$root = null;
foreach ([__DIR__, dirname(__DIR__) . '/fire-out', '/home/fireout/fire-out'] as $c) {
    if (file_exists("$c/.env") && file_exists("$c/artisan")) { $root = $c; break; }
}
if (!$root) { echo "❌ Project root not found\n</pre>"; exit; }
echo "Root: $root\n\n";

// ── Locate PHP 8+ binary ──────────────────────────────────────────────────────
function findPhp(): string {
    $binary = PHP_BINARY;

    // PHP_BINARY may point to php-fpm instead of the CLI interpreter.
    // Derive the CLI binary from the fpm path: /usr/sbin/php-fpm8.4 → /usr/bin/php8.4
    if (str_contains($binary, 'fpm')) {
        $version = preg_replace('/.*fpm/', '', $binary);           // e.g. "8.4"
        $cli     = '/usr/bin/php' . $version;
        $test    = trim((string) @shell_exec("$cli -r 'echo 1;' 2>/dev/null"));
        if ($test === '1') return $cli;

        // fallback: try bare php{version} in PATH
        $test = trim((string) @shell_exec("php$version -r 'echo 1;' 2>/dev/null"));
        if ($test === '1') return "php$version";
    }

    return $binary;
}

// ── Artisan runner ────────────────────────────────────────────────────────────
// Usage: fix.php?run=fireout2025&artisan=migrate
//        fix.php?run=fireout2025&artisan=migrate:fresh
//        fix.php?run=fireout2025&artisan=db:seed
//        fix.php?run=fireout2025&artisan=cache:clear
//        fix.php?run=fireout2025&artisan=route:list
$allowed_artisan = ['migrate', 'migrate:fresh', 'migrate:rollback', 'db:seed',
                    'cache:clear', 'config:clear', 'view:clear', 'optimize:clear', 'route:list', 'queue:work'];
if (isset($_GET['artisan'])) {
    $cmd = $_GET['artisan'];
    if (!in_array($cmd, $allowed_artisan)) {
        echo "❌ Command not in allowlist: $cmd\n</pre>"; exit;
    }
    $php = findPhp();
    echo "PHP   : $php\n";
    echo "CMD   : php artisan $cmd --force\n\n";
    $out = shell_exec("$php $root/artisan $cmd --force 2>&1");
    echo htmlspecialchars((string) $out) . "\n";
    echo '</pre>';
    exit;
}

// ── PHP binary info (shown on main run) ──────────────────────────────────────
$php = findPhp();
echo "PHP   : $php (" . PHP_VERSION . ")\n\n";

// ── 1. Clear stale caches ─────────────────────────────────────────────────────
echo "── Cache ──\n";
foreach (['config.php', 'routes-v7.php', 'routes.php', 'services.php'] as $f) {
    $p = "$root/bootstrap/cache/$f";
    if (file_exists($p)) { unlink($p); echo "✅ Deleted $f\n"; }
}
$n = 0;
foreach (glob("$root/storage/framework/views/*.php") ?: [] as $f) { unlink($f); $n++; }
echo "✅ Cleared $n compiled view(s)\n\n";

// ── 2. Database ───────────────────────────────────────────────────────────────
echo "── Database ──\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=fireout;charset=utf8", "fireout", "Cc@2108577");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // users columns
    $cols = $pdo->query("SHOW COLUMNS FROM `users`")->fetchAll(PDO::FETCH_COLUMN);
    foreach ([
        'role'              => "VARCHAR(20) NOT NULL DEFAULT 'customer' AFTER `email`",
        'phone'             => "VARCHAR(20) NULL AFTER `role`",
        'email_verified_at' => "TIMESTAMP NULL",
    ] as $col => $def) {
        if (!in_array($col, $cols)) {
            $pdo->exec("ALTER TABLE `users` ADD COLUMN `$col` $def");
            echo "✅ users.$col added\n";
        }
    }

    // tables
    foreach ([
        'addresses'   => "CREATE TABLE `addresses` (`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,`user_id` BIGINT UNSIGNED NOT NULL,`label` VARCHAR(50) NOT NULL DEFAULT 'Casa',`street` VARCHAR(150) NOT NULL,`number` VARCHAR(20) NOT NULL,`complement` VARCHAR(80) NULL,`neighborhood` VARCHAR(100) NOT NULL,`city` VARCHAR(100) NOT NULL,`state` VARCHAR(2) NOT NULL,`zipcode` VARCHAR(9) NOT NULL,`is_default` TINYINT(1) NOT NULL DEFAULT 0,`created_at` TIMESTAMP NULL,`updated_at` TIMESTAMP NULL)",
        'products'    => "CREATE TABLE `products` (`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,`sku` VARCHAR(100) NOT NULL UNIQUE,`name` VARCHAR(255) NOT NULL,`category` VARCHAR(100) NOT NULL,`price` DECIMAL(10,2) NULL,`stock_qty` INT NOT NULL DEFAULT 0,`created_at` TIMESTAMP NULL,`updated_at` TIMESTAMP NULL)",
        'orders'      => "CREATE TABLE `orders` (`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,`order_number` VARCHAR(50) NOT NULL UNIQUE,`user_id` BIGINT UNSIGNED NULL,`status` VARCHAR(30) NOT NULL DEFAULT 'pending',`customer_name` VARCHAR(100) NOT NULL,`customer_email` VARCHAR(150) NOT NULL,`customer_phone` VARCHAR(20) NOT NULL,`company` VARCHAR(100) NULL,`document_type` VARCHAR(10) NULL,`document_number` VARCHAR(20) NULL,`ship_street` VARCHAR(150) NULL,`ship_number` VARCHAR(20) NULL,`ship_complement` VARCHAR(80) NULL,`ship_neighborhood` VARCHAR(100) NULL,`ship_city` VARCHAR(100) NULL,`ship_state` VARCHAR(2) NULL,`ship_zipcode` VARCHAR(9) NULL,`payment_method` VARCHAR(20) NULL,`installments` INT NOT NULL DEFAULT 1,`subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0,`has_consulta` TINYINT(1) NOT NULL DEFAULT 0,`notes` TEXT NULL,`created_at` TIMESTAMP NULL,`updated_at` TIMESTAMP NULL)",
        'order_items' => "CREATE TABLE `order_items` (`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,`order_id` BIGINT UNSIGNED NOT NULL,`product_sku` VARCHAR(100) NOT NULL,`product_name` VARCHAR(255) NOT NULL,`variant` VARCHAR(100) NULL,`unit_price` DECIMAL(10,2) NULL,`qty` INT NOT NULL,`subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0,`created_at` TIMESTAMP NULL,`updated_at` TIMESTAMP NULL)",
    ] as $table => $sql) {
        if (!$pdo->query("SHOW TABLES LIKE '$table'")->fetch()) {
            $pdo->exec($sql);
            echo "✅ $table created\n";
        }
    }

    // admin upsert — mirrors AdminSeeder
    $hash     = password_hash('FireOut@1996!', PASSWORD_BCRYPT, ['cost' => 12]);
    $existing = $pdo->query(
        "SELECT id FROM `users` WHERE `email` IN ('admin@fireout.com.br','fireout@fireout.com.br') OR `role` = 'admin' LIMIT 1"
    )->fetch();
    if ($existing) {
        $pdo->prepare("UPDATE `users` SET `name`=?,`email`=?,`password`=?,`role`=?,`phone`=? WHERE `id`=?")
            ->execute(['Fire Out Admin', 'fireout@fireout.com.br', $hash, 'admin', '(21) 96696-9836', $existing['id']]);
        echo "✅ Admin updated → fireout@fireout.com.br / FireOut@1996!\n";
    } else {
        $pdo->prepare("INSERT INTO `users` (`name`,`email`,`role`,`phone`,`password`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,NOW(),NOW())")
            ->execute(['Fire Out Admin', 'fireout@fireout.com.br', 'admin', '(21) 96696-9836', $hash]);
        echo "✅ Admin created → fireout@fireout.com.br / FireOut@1996!\n";
    }

    // remove old single-unit abraçadeira SKUs
    $oldAbrac = ['abrac-1','abrac-15','abrac-2','abrac-25','abrac-3','abrac-4','abrac-6'];
    $in = implode(',', array_map(fn($s) => $pdo->quote($s), $oldAbrac));
    $deleted = $pdo->exec("DELETE FROM `products` WHERE `sku` IN ($in)");
    if ($deleted > 0) echo "✅ Removed $deleted old single-unit abraçadeira SKU(s)\n";

    // products seed — mirrors ProductSeeder
    $products = [
        ['mangueira-t1-15', 'Mangueira Tipo 1 — 1½"',         'Mangueiras',    280.00],
        ['mangueira-t2-15', 'Mangueira Tipo 2 — 1½"',         'Mangueiras',    340.00],
        ['mangueira-t2-25', 'Mangueira Tipo 2 — 2½"',         'Mangueiras',    540.00],
        ['esguicho-15',     'Esguicho Regulável 1½"',          'Mangueiras',    140.00],
        ['esguicho-25',     'Esguicho Regulável 2½"',          'Mangueiras',    199.00],
        ['adaptador-2515',  'Adaptador 2½" × 1½"',             'Mangueiras',    110.00],
        ['adaptador-25',    'Adaptador 2½"',                   'Mangueiras',    180.00],
        ['extintor-abc',    'Extintor ABC — 6 kg',             'Mangueiras',    250.00],
        ['porta-cf',        'Porta Corta-Fogo P-90',           'Portas',        null],
        ['moldura-7050',    'Moldura 70×50 cm',                'Portas',        250.00],
        ['moldura-6040',    'Moldura 60×40 cm',                'Portas',        200.00],
        ['antipanico',      'Barra Antipânico',                'Portas',        null],
        ['portas-duplas',   'Portas Duplas / Fora de Padrão',  'Portas',        null],
        ['abrac-1-10',   'Abraçadeira 1" — Kit 10 un.',   'Abraçadeiras',   8.00],
        ['abrac-1-50',   'Abraçadeira 1" — Kit 50 un.',   'Abraçadeiras',  40.00],
        ['abrac-1-100',  'Abraçadeira 1" — Kit 100 un.',  'Abraçadeiras',  80.00],
        ['abrac-15-10',  'Abraçadeira 1½" — Kit 10 un.',  'Abraçadeiras',  14.00],
        ['abrac-15-50',  'Abraçadeira 1½" — Kit 50 un.',  'Abraçadeiras',  70.00],
        ['abrac-15-100', 'Abraçadeira 1½" — Kit 100 un.', 'Abraçadeiras', 140.00],
        ['abrac-2-10',   'Abraçadeira 2" — Kit 10 un.',   'Abraçadeiras',  15.00],
        ['abrac-2-50',   'Abraçadeira 2" — Kit 50 un.',   'Abraçadeiras',  75.00],
        ['abrac-2-100',  'Abraçadeira 2" — Kit 100 un.',  'Abraçadeiras', 150.00],
        ['abrac-25-10',  'Abraçadeira 2½" — Kit 10 un.',  'Abraçadeiras',  23.00],
        ['abrac-25-50',  'Abraçadeira 2½" — Kit 50 un.',  'Abraçadeiras', 115.00],
        ['abrac-25-100', 'Abraçadeira 2½" — Kit 100 un.', 'Abraçadeiras', 230.00],
        ['abrac-3-10',   'Abraçadeira 3" — Kit 10 un.',   'Abraçadeiras',  27.00],
        ['abrac-3-50',   'Abraçadeira 3" — Kit 50 un.',   'Abraçadeiras', 135.00],
        ['abrac-3-100',  'Abraçadeira 3" — Kit 100 un.',  'Abraçadeiras', 270.00],
        ['abrac-4-10',   'Abraçadeira 4" — Kit 10 un.',   'Abraçadeiras',  28.00],
        ['abrac-4-50',   'Abraçadeira 4" — Kit 50 un.',   'Abraçadeiras', 140.00],
        ['abrac-4-100',  'Abraçadeira 4" — Kit 100 un.',  'Abraçadeiras', 280.00],
        ['abrac-6-10',   'Abraçadeira 6" — Kit 10 un.',   'Abraçadeiras',  54.00],
        ['abrac-6-50',   'Abraçadeira 6" — Kit 50 un.',   'Abraçadeiras', 270.00],
        ['abrac-6-100',  'Abraçadeira 6" — Kit 100 un.',  'Abraçadeiras', 540.00],
        ['canopla',         'Canopla em Aço Inox ½"',          'Suportes',      4.00],
        ['tripe',           'Tripé Reforçado com Haste',       'Suportes',      50.00],
    ];
    $stmt = $pdo->prepare(
        "INSERT INTO `products` (`sku`,`name`,`category`,`price`,`stock_qty`,`created_at`,`updated_at`)
         VALUES (?,?,?,?,0,NOW(),NOW())
         ON DUPLICATE KEY UPDATE `name`=VALUES(`name`),`category`=VALUES(`category`),`price`=VALUES(`price`)"
    );
    $inserted = 0;
    foreach ($products as [$sku, $name, $cat, $price]) {
        $stmt->execute([$sku, $name, $cat, $price]);
        if ($stmt->rowCount() > 0) $inserted++;
    }
    echo "✅ Products: $inserted upserted (" . count($products) . " total)\n";

    echo "\n✅ Done. Delete fix.php from the server.\n";

} catch (Exception $e) {
    echo "❌ " . $e->getMessage() . "\n";
}

echo '</pre>';
