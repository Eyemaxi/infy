<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 26.02.18
 * Time: 12:58
 */

namespace Infy\Core\Config;


class Xml
{
    /**
     * @return \SimpleXMLElement
     */
    public static function getRedirects()
    {
        if (file_exists(ROOT . '/cfg/redirects.xml')) {
            $redirects = simplexml_load_file(ROOT . '/cfg/redirects.xml');
            return $redirects;
        }
    }

    /**
     * @return \SimpleXMLElement
     */
    public static function getRoutes()
    {
        if (file_exists(ROOT . '/cfg/router.xml')) {
            $router = simplexml_load_file(ROOT . '/cfg/router.xml');
            return $router->routes;
        }
    }

    public static function joinXmls()
    {
        $q = new SplStack();

        $dir = ROOT.'/extends';
        $extendsList = Config::extendsListValidate(scandir($dir));


        return $extendsList;
    }

}