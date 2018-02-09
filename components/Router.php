<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20.12.2017
 * Time: 17:21
 */

class Router
{
    private $routes;

    public function __construct() {
        $xml = simplexml_load_file(ROOT . '/config/config.xml');
        $this->routes = $xml->routes; //Получаем все наши маршруты
    }

    /**
     * Returns request string
     * @return string
     */
    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!='/') {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * @param $uri
     * @return array
     */
    private function getUriParams($uri){
        $uriParams = explode('/', $uri); //Разбиваем строку запроса и заносим в массив
        return $uriParams;
    }

    /**
     * @return array|bool|SimpleXMLElement[]
     */
    private function getRouteValues() {
        $uri = $this->getURI(); //Получаем строку запроса
        $uriParams = $this->getUriParams($uri); //Получаем параметры запроса

        $currentRoute = $this->routes;
        $isRoute = true;
        $valuesRoute = null;

        //Проверяем на существование данного маршрута
        foreach ($uriParams as $key => $value) {
            if(isset($currentRoute->$value)) {
                $currentRoute = $currentRoute->$value; // Если встречаем в xml наш параметр маршрута - сохраняем его
            }
            elseif (isset($currentRoute->dynamic_values)) {
                $currentRoute = $currentRoute->dynamic_values; // Если наш параметр маршрута - динамическое значение (id или т.п.) - сохраняем его
                $valuesRoute = array_slice($uriParams, $key); // Записываем остальные (непройденные) элементы массива
                break;
            } else {
                $isRoute = false; //Если не нашли такого, записываем, что не существует такой маршрут
                break;
            }
        }

        if(!empty($valuesRoute)) {
            return array('currentRoute' => $currentRoute,'valuesRoute' =>  $valuesRoute);
        }
        elseif($isRoute) {
            return $currentRoute;
        }
        elseif (!$isRoute)
        {
            return false;
        }

    }

    public function run() {
        //Получаем найденные значения (Controller и Action) текущего route

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

}