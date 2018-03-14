<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 22.02.18
 * Time: 19:37
 */
namespace Infy\Core;

class Infy
{
    /**
     * Redirect by URI
     * @param $uri
     */
    public static function _redirect($uri)
    {
        header('Location: ' . $uri);
        die();
    }
}