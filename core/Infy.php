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
    protected function getModulePath($path)
    {
        return '\\' . (implode( '\\', explode('_', $path)));
    }

    /**
     * @param array $path
     * @param null $type
     * @return string
     */
    protected function getFilePath(array $path, $type = null)
    {
        foreach ($path as $key => $item) {
            $path[$key] = ucfirst($item);
        }

        if ($type != null) {
            return ucfirst($type) . '\\' . (implode('\\', $path));
        } else {
            return(implode('\\', $path));
        }
    }
}