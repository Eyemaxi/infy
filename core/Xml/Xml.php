<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 26.02.18
 * Time: 12:58
 */

namespace Infy\Core\Xml;


class Xml
{
    /**
     * @return \SimpleXMLElement
     */
    public static function getRedirects()
    {
        $redirects = simplexml_load_file(ROOT . '/cfg/redirects.xml');
        return $redirects;
    }

    /**
     * @return \SimpleXMLElement
     */
    public static function getRoutes()
    {
        $router = simplexml_load_file(ROOT . '/cfg/router.xml');
        return $router->routes;
    }

}