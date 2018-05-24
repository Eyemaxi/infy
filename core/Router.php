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

use Infy\Core\App\Exception\InfyException;
use Infy\Core\Config\Config;

/**
 * Class Router
 *
 * @category Core
 * @package Infy\Core
 * @author <maksimglaz@gmail.com>
 */
final class Router
{
    /**
     * Object routes.
     *
     * @var \SimpleXMLElement
     * @access private
     */
    private $routes;

    /**
     * @var Config\Xml\Router
     */
    protected $_routerXml;

    /**
     * @var Config\Config
     */
    protected $_config;

    /**
     * @var App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var App\Http\Request 
     */
    protected $_request;

    /**
     * Router constructor.
     * @param \Infy\Core\Config\Xml\Router $routerXml
     * @param Config $config
     * @param App\ObjectManager $objectManager
     * @param App\Http\Request $request
     */
    public function __construct(
        \Infy\Core\Config\Xml\Router $routerXml,
        Config $config,
        \Infy\Core\App\ObjectManager $objectManager,
        \Infy\Core\App\Http\Request $request
    ) {
        $this->_routerXml = $routerXml;
        $this->_config = $config;
        $this->_objectManager = $objectManager;
        $this->_request = $request;
        /* Get all routes */
        $this->routes = $this->_routerXml->getRoutes();
    }

    /**
     * Creating object class Controller and executing Action method
     *
     * @access public
     * @return void
     * @throws App\Exception\InfyException
     */
    public function run()
    {
        /* Get Controller and Action for route */
        $currentRouteValues = $this->getRouteValues();
        $controllerPath = $currentRouteValues['controller'];
        $actionName = $currentRouteValues['action'];
        $actionParams = $currentRouteValues['params'];
        $this->_request->setUriParams($actionParams);

        if (class_exists($controllerPath)) {
            $controllerName = $this->_objectManager->getObject($controllerPath);
            if (method_exists($controllerPath, $actionName)) {
                $controllerName->$actionName();
            } else {
                throw new InfyException(
                    'ACTION_DOES_NOT_EXISTS',
                    InfyException::ERROR_TYPE_CRITICAL,
                    ['controller' => $controllerPath, 'action' => $actionName]
                );
            }
        } else {
            throw new InfyException(
                'CONTROLLER_DOES_NOT_EXISTS',
                InfyException::ERROR_TYPE_CRITICAL,
                ['controller' => $controllerPath]
            );
        }
    }

    /**
     * Return Controller Path, Action with Parameters
     *
     * @access private
     * @return array
     */
    private function getRouteValues()
    {
        /* Get URI parameters */
        $uriParams = $this->_request->getUriParams();

        /* Path to Controller */
        $controllerPath = [];

        /* Saving Module name and Action name */
        $currentRoute = $this->routes->routes;

        /* Is exist route */
        $isRoute = false;

        /* Other URI parameters */
        $valuesRoute = null;

        foreach ($uriParams as $key => $value) {
            if (isset($currentRoute->$value)) {
                $isRoute = true;
                array_push($controllerPath, $value);
                $currentRoute = $currentRoute->$value;
            } else {
                if ($isRoute) {
                    /* Write down the remaining values */
                    $valuesRoute = array_slice($uriParams, $key);
                    break;
                }
            }
        }

        if ($isRoute && isset($currentRoute->module)) {
            return $this->getActionController($controllerPath, $currentRoute->module, $valuesRoute);
        } else {
            /********************************* PAGE NOT FOUND *********************************/
            echo '<p>Page not found</p>';
            die();
        }
    }

    /**
     * Get Controller Path, Action with Parameters
     *
     * @access private
     * @param $controllerPath
     * @param $moduleName
     * @param $uriParams
     * @return array
     */
    private function getActionController($controllerPath, $moduleName, $uriParams)
    {
        $controllerPath = implode('\\', [
            $this->_config->getModuleNamespaceByAlias($moduleName),
            $this->_config->getFileNamespace($controllerPath, Config::MODULE_CLASS_TYPE_CONTROLLER)
        ]);
        $actionName = $this->_config->getActionName($controllerPath, $uriParams);
        return ['controller' => $controllerPath, 'action' => $actionName, 'params' => $uriParams];
    }

}