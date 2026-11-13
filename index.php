<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure HTTPS is recognized when behind Cloudflare / Reverse Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// Locate Laravel application root:
// 1. Production cPanel server: /home/fireout/fire-out (DocumentRoot is /home/fireout/www)
// 2. Relative sibling: /www/../fire-out
// 3. Local development root or parent
if (file_exists('/home/fireout/fire-out/vendor/autoload.php')) {
    $appPath = '/home/fireout/fire-out';
} elseif (file_exists(__DIR__.'/../fire-out/vendor/autoload.php')) {
    $appPath = __DIR__.'/../fire-out';
} elseif (file_exists(__DIR__.'/vendor/autoload.php')) {
    $appPath = __DIR__;
} else {
    $appPath = dirname(__DIR__);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appPath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
