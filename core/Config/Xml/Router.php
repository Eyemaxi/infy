<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 26.02.18
 * Time: 12:58
 */

namespace Infy\Core\Config\Xml;
use Infy\Core\Config\Config;


class Router
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
            return $router;
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

    public static function mergeRoutes()
    {
        $domxml = new \DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $xml = new \SimpleXMLElement('<config/>');

        $dir = ROOT.'/extends';
        $namespaceList = Config::extendsNamesValidate(scandir($dir));
        foreach ($namespaceList as $namespace) {
            $dirNamespace = $dir . '/' . $namespace;
            $moduleList = Config::extendsNamesValidate(scandir($dirNamespace));
            foreach ($moduleList as $module) {
                $dirModule = $dirNamespace . '/' . $module;
                $moduleRoutes = self::getModuleRoutes($dirModule);
                if ($moduleRoutes != null) {
                    $xml = self::mergeSimpleXml($xml, $moduleRoutes);
                }
            }
        }

        $domxml->loadXML($xml->asXML());
        $domxml->save(ROOT . '/cfg/router.xml');
    }

    private static function getModuleRoutes($dir)
    {
        $cfgXmls = self::getCfgXmls($dir);
        if ($cfgXmls != null) {
            $moduleXml = $cfgXmls['module'];
            if ($moduleXml->active == 'true') {
                $moduleName = strval($moduleXml->Namespace) . '_' . strval($moduleXml->ModuleName);
                $routesXmls = $cfgXmls['routes'];
                self::relationModuleController($moduleName, $routesXmls);
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

    private static function relationModuleController($moduleName, \SimpleXMLElement &$routesXmls)
    {
        foreach ($routesXmls as $key => $value) {
            if(is_object($value)) {
                if (isset($value->action) && !isset($value->module)) {
                    $value->addChild('module', $moduleName);
                }
                self::relationModuleController($moduleName, $value);
            }
        }
    }



    private static function mergeSimpleXml(\SimpleXMLElement $xml, \SimpleXMLElement $routes)
    {
        $router1 = self::xmlToArray($xml);
        $router2 = self::xmlToArray($routes);
        $router = array_merge_recursive($router2, $router1);
        array_multisort($router, SORT_ASC, SORT_NATURAL);

        //creating object of SimpleXMLElement
        $xml = new \SimpleXMLElement("<config/>");

        //function call to convert array to xml
        self::arrayToXml($router,$xml);

        return $xml;
    }

    private static function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($key != 'action' && $key != 'module') {
                    if (!is_numeric($key)) {
                        $subnode = $xml->addChild("$key");
                        self::arrayToXml($value, $subnode);
                    } else {
                        $subnode = $xml->addChild("item$key");
                        self::arrayToXml($value, $subnode);
                    }
                } else {
                    /********************       WARNING        ***************************/
                    $xml->addChild("$key",htmlspecialchars("$value[0]"));
                }
            } else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }
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