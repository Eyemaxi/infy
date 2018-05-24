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
 * Class Redirect
 *
 * @category Core
 * @package Infy\Core\App\Http
 * @author <maksimglaz@gmail.com>
 */
class Redirect
{
    /**
     * Object redirects.
     *
     * @var \SimpleXMLElement
     * @access private
     */
    private $redirects;

    /**
     * @var \Infy\Core\Config\Xml\Router
     */
    protected $_routerXml;

    /**
     * Redirect constructor.
     * @param \Infy\Core\Config\Xml\Router $routerXml
     */
    public function __construct(\Infy\Core\Config\Xml\Router $routerXml)
    {
        $this->_routerXml = $routerXml;
        /* Get all redirects */
        $this->redirects = $this->_routerXml->getRedirects();
    }

    /**
     * Looking for redirects
     *
     * @access private
     * @param $uriParams
     * @return void
     */
    public function isRedirect($uriParams)
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

    /**
     * Check is root redirect and execute it
     *
     * @access public
     * @return void
     */
    public function isRootRedirect()
    {
        if (isset($this->redirects->root)) {
            $this->_redirect(strval($this->redirects->root));
        }
    }

    /**
     * Redirect by URI
     *
     * @access public
     * @param $uri
     * @return void
     */
    public function _redirect($uri)
    {
        header('Location: ' . $uri);
        die();
    }
}