<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20.12.2017
 * Time: 17:21
 */
namespace Infy\Core;
use Infy\Core\Xml\Xml;
use Infy\Core\Infy;

final class Router extends Infy
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
        $this->redirects = Xml::getRedirects();
        /* Get all routes */
        $this->routes = Xml::getRoutes();
    }

    public function run() {
        /* Get ModulePath, Controller and Action for route */
        $currentRouteValues = $this -> getRouteValues();

        if(isset($currentRouteValues['valuesRoute'])) {
            $currentRouteValues = $currentRouteValues['currentRoute'];
            $valuesRoute = $currentRouteValues['valuesRoute'];
        }

        if($currentRouteValues!= false) {
            // Подключить файл класса-контроллера
            $controllerFile = ROOT . '/controllers/' . $currentRouteValues->controller . '.php';

            if(file_exists($controllerFile)) {
                include_once($controllerFile);
            }

            // Создать объект, вызвать метод (т.е. action)
            $controllerName = strval($currentRouteValues->controller);
            $actionName = strval($currentRouteValues->action);

            $controllerObject = new $controllerName;
            if(isset($valuesRoute))
            {
                $controllerObject->$actionName($valuesRoute);
            }
            else {
                $controllerObject->$actionName();
            }

        }
        else {
            echo '<p>Такой страницы не существует!</p>';
        }
    }


    private function getRouteValues() {
        /* Get URI parameters */
        $uriParams = $this->getUriParams();

        /* Path to Controller */
        $controllerPath = [];

        /* Saving Module name and Action name */
        $currentRoute = $this->routes;

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
        }
    }

    private function getActionController($controllerPath, $moduleName, $uriParams)
    {

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
                $this->_redirect(strval($this->redirects->root));
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
            $this->_redirect($redirectUri);
        }
    }

}