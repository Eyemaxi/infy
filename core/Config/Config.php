<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 14.03.18
 * Time: 17:34
 */

namespace Infy\Core\Config;


class Config
{
    /**
     * @param string $path
     * @return string
     */
    public static function getModulePath($path)
    {
        return '\\' . (implode( '\\', explode('_', $path)));
    }

    /**
     * @param array $path
     * @param null $type
     * @return string
     */
    public static function getFilePath(array $path, $type = null)
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

    public static function extendsNamesValidate($extendsList)
    {
        $patternExtends = "/^[a-zA-Z0-9]+$/";
        foreach ($extendsList as $key => $value) {
            if (!preg_match($patternExtends, $value)) {
                unset($extendsList[$key]);
            }
        }

        return $extendsList;
    }

}