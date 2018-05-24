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

namespace Infy\Core\App\Http;

/**
 * Class Request
 *
 * @category Core
 * @package Infy\Core\App\Http
 * @author <maksimglaz@gmail.com>
 */
class Request
{
    protected $params;

    /**
     * @var Redirect
     */
    protected $_redirect;

    public function __construct(\Infy\Core\App\Http\Redirect $redirect)
    {
        $this->_redirect = $redirect;
    }

    public function getParams()
    {

    }

    public function getParam()
    {

    }

    public function setParams()
    {

    }

    public function setParam()
    {

    }

    /**
     * Return URI parameters
     *
     * @access private
     * @return array
     */
    public function getUriParams()
    {
        $uri = strval($this->getURI());
        $uriParams = explode('/', $uri);
        $this->_redirect->isRedirect($uriParams);
        return $uriParams;
    }

    public function setUriParams($uriParams)
    {
        if (!is_null($uriParams)) {

        }
    }

    /**
     * Returns request string or redirect to root
     *
     * @access private
     * @return string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            return trim($_SERVER['REQUEST_URI'], '/');
        } else {
            $this->_redirect->isRootRedirect();
        }
    }
}