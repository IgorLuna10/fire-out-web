<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine basePath (supports both production /home/fireout/fire-out + www and standard local setup)
$serverApp = __DIR__.'/../fire-out';
$appPath = (is_dir($serverApp) && file_exists($serverApp.'/vendor/autoload.php')) ? $serverApp : __DIR__.'/..';

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
