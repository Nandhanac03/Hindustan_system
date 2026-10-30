<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Direct handler for /clear-data and /clear-data.php (supports single or multiple leading slashes like //clear-data)
$rawRequestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$rawUriPath = explode('?', $rawRequestUri, 2)[0];
$reqUriPath = '/' . trim($rawUriPath, '/');
if ($reqUriPath === '/clear-data' || $reqUriPath === '/clear-data.php') {
    require __DIR__ . '/clear-data.php';
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
