<?php

use Infy\Core\Router;

//FRONT CONTROLLER

// 1. General settings
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Include files of system
define('ROOT', dirname(__FILE__));
//require_once(ROOT . '/components/Router.php');
//require_once(ROOT . '/components/Db.php');
require ROOT . '/vendor/autoload.php';

// 3. Install connecting to the DB

// 4. Calling the Router
$router = new Router();
$router->run();