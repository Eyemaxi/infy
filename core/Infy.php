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
    protected function _redirect($uri)
    {
        header('Location: ' . $uri);
        die();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getModulePath(string $path)
    {
        return(implode( '\\', explode('_', $path)));
    }

    /**
     * @param array $path
     * @return string
     */
    protected function getFilePath(array $path)
    {
        foreach ($path as $key => $item) {
            $path[$key] = ucfirst($item);
        }
        return(implode('\\', $path));
    }
}