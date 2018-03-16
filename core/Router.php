<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20.12.2017
 * Time: 17:21
 */
namespace Infy\Core;
use Infy\Core\Config\Xml\Router as RouterXml;
use Infy\Core\Config\Config;
use Infy\Core\Infy;

final class Router
{
    /**
     * @var \SimpleXMLElement
     */
    private $redirects;

    /**
     * @var \SimpleXMLElement
     */
    private $routes;

    public function __construct() {
        /* Get all redirects */
        $this->redirects = RouterXml::getRedirects();
        /* Get all routes */
        $this->routes = RouterXml::getRoutes();
    }

    /**
     * Creating object class Controller and executing Action method
     */
    public function run() {
        /* Get Controller and Action for route */
        $currentRouteValues = $this -> getRouteValues();
        $controllerPath = $currentRouteValues['controller'];
        $actionName = $currentRouteValues['action'];
        $actionParams = $currentRouteValues['params'];

        try {
            $controllerName = new $controllerPath();
            $controllerName -> $actionName($actionParams);
        } catch (Exception $ex) {
            echo '<p>ERROR</p>';
        }
    }

    /**
     * Return Controller Path, Action with Parameters
     * @return array
     */
    private function getRouteValues() {
        /* Get URI parameters */
        $uriParams = $this->getUriParams();

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

        if ($isRoute) {
            return $this->getActionController($controllerPath, $currentRoute, $valuesRoute);
        } else {
            echo '<p>Page not found</p>';
            die();
        }
    }

    /**
     * Get Controller Path, Action with Parameters
     * @param $controllerPath
     * @param $moduleName
     * @param $uriParams
     * @return array
     */
    private function getActionController($controllerPath, $moduleName, $uriParams)
    {
        $controllerPath = Config::getModulePath($moduleName->module) . '\\' . Config::getFilePath($controllerPath, 'controller') . 'Controller';
        if (isset($moduleName->action)) {
            $actionName = $moduleName->action . 'Action';
        } else {
            /*********************       CUSTOM_ERROR: PAGE DOES NOT EXIST        *************************/
            //$actionName = 'indexAction';
        }
        return ['controller' => $controllerPath, 'action' => $actionName, 'params' => $uriParams];
    }

    /**
     * Return URI parameters
     * @return array
     */
    private function getUriParams(){
        $uri = strval($this->getURI());
        $uriParams = explode('/', $uri);
        $this->isRedirect($uriParams);
        return $uriParams;
    }

    /**
     * Returns request string
     * @return string
     */
    private function getURI(){
        if (!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!='/') {
            return trim($_SERVER['REQUEST_URI'], '/');
        } else {
            if (isset($this->redirects->root)) {
                Infy::_redirect(strval($this->redirects->root));
            }
        }
    }

    /**
     * Looking for redirects
     * @param $uriParams
     */
    private function isRedirect($uriParams)
    {
        $redirectUri = '';
        $currentRoute = $this->redirects->redirects;
        foreach ($uriParams as $uriParam) {
            if (isset($currentRoute->$uriParam)) {
                if (isset($currentRoute->$uriParam->new_route_param)) {
                    $redirectUri .= $currentRoute->$uriParam->new_route_param . '/';
                    $currentRoute = $currentRoute->$uriParam;
                }
            }
        }

        if ($redirectUri != '') {
            Infy::_redirect($redirectUri);
        }
    }

}