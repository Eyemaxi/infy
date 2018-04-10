<?php
/**
 * Infy Framework
 *
 * @author    <maksimglaz@gmail.com>
 * @category  Core
 * @package   Infy\Core
 * @copyright Copyright (c) 2018 Infy
 * @license   https://www.infy-team.com/license.txt
 */

namespace Infy\Core;
use Infy\Core\Config\Xml\Router as RouterXml;

/**
 * Class Bootstrap
 *
 * @category Core
 * @package  Infy\Core
 * @author   <maksimglaz@gmail.com>
 */
class Bootstrap
{
    /**
     * Launching the page
     *
     * @access public
     * @return void
     */
    public static function start()
    {
        RouterXml::mergeRoutes();
        /* Calling the Router */
        $router = new Router();
        /*$constructMethod = new \ReflectionMethod('\Infy\Core\Router', '__construct');
        $constructParameters = $constructMethod->getParameters();
        foreach ($constructParameters as $parameter) {
            echo $parameter->getClass()->getName();
        }*/
        //$ps = $pp[0]->getClass()->getName();
        $router->run();
    }

}