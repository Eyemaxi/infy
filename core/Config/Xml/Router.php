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

namespace Infy\Core\Config\Xml;

use Infy\Core\App\Exception\InfyException;
use Infy\Core\Config\Config;

/**
 * Class Router
 *
 * @category Core
 * @package Infy\Core\Config\Xml
 * @author <maksimglaz@gmail.com>
 */
final class Router
{
    const PATH_MAIN_ROUTER = ROOT . '/cfg/router.xml';

    /**
     * @var Config
     */
    protected $_config;

    /**
     * Router constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    /**
     * Get redirects
     *
     * @access public
     * @param string $path
     * @return \SimpleXMLElement
     */
    public function getRedirects($path = ROOT)
    {
        if (file_exists($path . '/cfg/redirects.xml')) {
            $redirects = simplexml_load_file($path . '/cfg/redirects.xml');
            return $redirects;
        } else {
            return null;
        }
    }

    /**
     * Get routes
     *
     * @access public
     * @param string $path
     * @return \SimpleXMLElement
     */
    public function getRoutes($path = ROOT)
    {
        if (file_exists($path . '/cfg/router.xml')) {
            $router = simplexml_load_file($path . '/cfg/router.xml');
            return $router;
        } else {
            return null;
        }
    }

    /**
     * Merge all routes
     *
     * @access public
     * @return void
     * @throws InfyException
     */
    public function mergeRoutes()
    {
        $domxml = new \DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $xml = new \SimpleXMLElement('<config/>');

        $xml = $this->mergeRoutesByDirectory($xml, Config::DIRECTORY_TYPE_COMMUNITY);
        $xml = $this->mergeRoutesByDirectory($xml, Config::DIRECTORY_TYPE_EXTENDS);

        $domxml->loadXML($xml->asXML());
        $domxml->save(self::PATH_MAIN_ROUTER);
    }

    /**
     * Merge routes by directory
     *
     * @access private
     * @param $xml
     * @param $dir
     * @return \SimpleXMLElement
     * @throws InfyException
     */
    private function mergeRoutesByDirectory($xml, $dir)
    {
        $modules = $this->_config->getModulesByDir($dir);
        if (!is_null($modules)) {
            foreach ($modules as $module) {
                $moduleRoutes = $this->getModuleRoutes($module);
                if (!is_null($moduleRoutes)) {
                    $xml = $this->mergeSimpleXmls($xml, $moduleRoutes);
                }
            }
        }

        return $xml;
    }

    /**
     * Get module routes
     *
     * @access private
     * @param $module
     * @return mixed|null
     */
    private function getModuleRoutes($module)
    {
        if ($module['active'] == 'true') {
            $moduleName = $module['namespace'];
            $routesXml = $this->getRoutes($module['dir_path']);
            if (!is_null($routesXml)) {
                $this->bindModuleRoute($moduleName, $routesXml);
                return $routesXml;
            }
        }
        return null;
    }

    /**
     * Bind module to route
     *
     * @access private
     * @param $moduleName
     * @param \SimpleXMLElement $routesXmls
     * @return void
     */
    private function bindModuleRoute($moduleName, \SimpleXMLElement &$routesXmls)
    {
        foreach ($routesXmls as $key => $value) {
            if (is_object($value)) {
                if (isset($value->use) && !isset($value->module)) {
                    $value->addChild('module', $moduleName);
                    $dom = dom_import_simplexml($value->use);
                    $dom->parentNode->removeChild($dom);
                }
                $this->bindModuleRoute($moduleName, $value);
            }
        }
    }


    /**
     * Merge two SimpleXml objects
     *
     * @access private
     * @param \SimpleXMLElement $xml
     * @param \SimpleXMLElement $routes
     * @return \SimpleXMLElement
     * @throws InfyException
     */
    private function mergeSimpleXmls(\SimpleXMLElement $xml, \SimpleXMLElement $routes)
    {
        $router1 = $this->xmlToArray($xml);
        $router2 = $this->xmlToArray($routes);
        $router = array_merge_recursive($router2, $router1);

        /* Creating object of SimpleXMLElement */
        $xml = new \SimpleXMLElement("<config/>");

        /* Function call to convert array to xml */
        $this->arrayToXml($router, $xml);

        return $xml;
    }

    /**
     * Convert array to xml object
     *
     * @access private
     * @param $array
     * @param $xml
     * @return void
     * @throws InfyException
     */
    private function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($key != 'module') {
                    if (!is_numeric($key)) {
                        $subnode = $xml->addChild("$key");
                        $this->arrayToXml($value, $subnode);
                    } else {
                        $subnode = $xml->addChild("item$key");
                        $this->arrayToXml($value, $subnode);
                    }
                } else {
                    try {
                        throw new InfyException(
                            'MODULES_COLLISION_IN_ROUTE',
                            InfyException::ERROR_TYPE_WARNING,
                            ['modules' => $value, 'route' => $_GET['route']]
                        );
                    } catch (InfyException $e) {
                        $e->getErrorMessage();
                    }
                    $xml->addChild("$key", htmlspecialchars("$value[0]"));
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * Convert xml object to array
     *
     * @access private
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    private function xmlToArray($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            if (!is_numeric($index)) {
                if (is_object($node)) {
                    $out[$index] = $this->xmlToArray($node);
                } else {
                    $out[$index] = $node;
                }
            }
        }
        return $out;
    }

}