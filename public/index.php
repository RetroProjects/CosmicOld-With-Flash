<?php
use Cosmic\App\Config;
use Cosmic\System\RouterService;
use Cosmic\System\DatabaseService;

include_once __DIR__ . '/../src/System/Helpers/Helper.php';
include_once __DIR__ . '/../vendor/autoload.php';

ini_set("display_errors", 1);

/**
 *  Set session
 */

session_start();

/**
 *  Set QueryBuilder
 */

new DatabaseService;
new Config;

/**
 *  Dispatch URI
 */

$router = new RouterService();
$router->init();
