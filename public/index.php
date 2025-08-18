<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Better error handling for shared hosting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Check if vendor directory exists
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    die('Vendor directory not found. Please run: composer install');
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Check if bootstrap directory exists
if (!file_exists(__DIR__.'/../bootstrap/app.php')) {
    die('Bootstrap directory not found. Laravel installation may be incomplete.');
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
