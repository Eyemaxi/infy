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
     * @param string $path
     * @return \SimpleXMLElement
     */
    public static function getRedirects($path = ROOT)
    {
        if (file_exists($path . '/cfg/redirects.xml')) {
            $redirects = simplexml_load_file($path . '/cfg/redirects.xml');
            return $redirects;
        }
    }

    /**
     * @param string $path
     * @return \SimpleXMLElement
     */
    public static function getRoutes($path = ROOT)
    {
        if (file_exists($path . '/cfg/router.xml')) {
            $router = simplexml_load_file($path . '/cfg/router.xml');
            return $router->routes;
        }
    }

    /**
     * @param $modulePath
     * @return \SimpleXMLElement
     */
    public static function getModuleName($modulePath)
    {
        if (file_exists($modulePath . '/cfg/module.xml')) {
            $module = simplexml_load_file($modulePath . '/cfg/module.xml');
            return $module->module;
        }
    }

    public static function mergeXmls()
    {
//        $domxml = new \DOMDocument('1.0');
//        $domxml->preserveWhiteSpace = false;
//        $domxml->formatOutput = true;
//        /* @var $xml SimpleXMLElement */
//
//
//        $xml = new \SimpleXMLElement('<config/>');
//        $routes = $xml->addChild('routes');
//        $main = $routes->addChild('main');
//        $module = $main->addChild('module', 'Infy_Test');
//        $module->addChild('action', 'test');
//        $main->addChild('action', 'test');
//        $routes->addChild('name')->addChild('module', 'Infy_Name');
//        $routes->addChild('qwerty')->addChild('module', 'Qwerty_Name');

        $routes = simplexml_load_file(ROOT . '/cfg/router.xml');
        $routes = $routes->routes;

        $dir = ROOT.'/extends';
        $namespaceList = Config::extendsNamesValidate(scandir($dir));
        foreach ($namespaceList as $namespace) {
            $dirNamespace = $dir . '/' . $namespace;
            $moduleList = Config::extendsNamesValidate(scandir($dirNamespace));
            foreach ($moduleList as $module) {
                $dirModule = $dirNamespace . '/' . $module;
                $moduleRoutes = self::getModuleRoutes($dirModule);
                if ($moduleRoutes != null) {
                    self::mergeSimpleXml($routes, $moduleRoutes);
                }
            }
        }

//        $domxml->loadXML($xml->asXML());
//        $domxml->save(ROOT . '/cfg/qwerty.xml');

        return $namespaceList;
    }

    private static function getModuleRoutes($dir)
    {
        $cfgXmls = self::getCfgXmls($dir);
        if ($cfgXmls != null) {
            $moduleXml = $cfgXmls['module'];
            if ($moduleXml->active == 'true') {
                $moduleName = strval($moduleXml->Namespace) . '_' . strval($moduleXml->ModuleName);
                $routesXmls = $cfgXmls['routes'];
                return $routesXmls;
            }
        } else {
            return null;
        }
    }

    private static function getCfgXmls($dir)
    {
        if (file_exists($dir . '/cfg/module.xml') && file_exists($dir . '/cfg/router.xml')) {
            $moduleXml = self::getModuleName($dir);
            $routesXml = self::getRoutes($dir);
            return ['module' => $moduleXml, 'routes' => $routesXml];
        } else {
            return null;
        }
    }



    private static function mergeSimpleXml(\SimpleXMLElement $router1, \SimpleXMLElement $router2)
    {
        $xml = $router2;
        $router1 = self::xmlToArray($router1);
        $router2 = self::xmlToArray($router2);
        $router = array_merge($router2, $router1);
        $path = '';

        while (1==1) {
            foreach ($router1 as $key => $value) {
                if (isset($router2[$key])) {
                    $router1 = $value;
                    $path = $xml->$value;
                    continue;
                } else {

                }
            }
        }

        return $router;
    }

    private static function xmlToArray ($xmlObject, $out = array())
    {
        foreach ((array) $xmlObject as $index => $node) {
            if (is_object($node)) {
                $out[$index] = self::xmlToArray($node);
            } else {
                $out[$index] = $node;
            }
        }
        return $out;
    }



}