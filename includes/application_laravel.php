<?php
/**
 * @copyright Copyright 2003-2024 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2024 Apr 10 Modified in v2.0.1 $
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Events\Dispatcher;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => DB_TYPE,
    'host'      => DB_SERVER,
    'database'  => DB_DATABASE,
    'username'  => DB_SERVER_USERNAME,
    'password'  => DB_SERVER_PASSWORD,
    'charset'   => DB_CHARSET,
    // do not pass prefix; this is included in the table definition
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container = new Container;
$lRequest = Request::capture();
$container->instance('Illuminate\Http\Request', $lRequest);
$events = new Dispatcher($container);
$router = new Router($events, $container);
require_once DIR_FS_CATALOG . 'laravel/routes/routes.php';

$redirect = new \Illuminate\Routing\Redirector(new \Illuminate\Routing\UrlGenerator($router->getRoutes(), $lRequest));
