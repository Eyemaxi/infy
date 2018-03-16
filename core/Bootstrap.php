<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 16.03.18
 * Time: 13:20
 */

namespace Infy\Core;
use Infy\Core\Config\Xml\Router as RouterXml;

class Bootstrap
{

    public static function start()
    {
        RouterXml::mergeXmls();
        // Calling the Router
        $router = new Router();
        $router->run();
    }

}