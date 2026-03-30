<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

// Force the base URL if we are in a subdirectory to prevent 404s
// This ensures that Laravel's router correctly identifies the path relative to /LocalTrade
if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/LocalTrade')) {
    $_SERVER['SCRIPT_NAME'] = '/LocalTrade/index.php';
}

$request = Request::capture();

$app->handleRequest($request);
