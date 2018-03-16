<?php

//FRONT CONTROLLER

// 1. General settings
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Include files of system
define('ROOT', dirname(__FILE__));
require ROOT . '/vendor/autoload.php';

// 3. Install connecting to the DB

// 4. Start
Infy\Core\Bootstrap::start();