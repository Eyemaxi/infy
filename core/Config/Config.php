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

namespace Infy\Core\Config;

use Infy\Core\App\Exception\InfyException;

/**
 * Class Config
 *
 * @author <maksimglaz@gmail.com>
 * @package Infy\Core\Config
 * @category Core
 */
class Config
{
    const DIRECTORY_TYPE_COMMUNITY = 'community';
    const DIRECTORY_TYPE_EXTENDS = 'extends';
    const DIRECTORY_PATH_COMMUNITY = ROOT . '/community/';
    const DIRECTORY_PATH_EXTENDS = ROOT . '/extends/';
    const MODULE_CLASS_TYPE_CONTROLLER = 'controller';
    const DIRECTORY_TYPE_MODULE = 'module';
    const MODULE_LIST_FILE_PATH = ROOT . '/var/modules/modules.ser';
    const CONFIG_TYPE_LANGUAGE = 'language';

    protected $moduleCollection;

    /**
     * @var \Infy\Core\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var \Infy\Core\App\Logger\InfyLogger
     */
    protected $_infyLogger;

    public function __construct(\Infy\Core\App\ObjectManager $objectManager, \Infy\Core\App\Logger\InfyLogger $logger)
    {
        $this->_objectManager = $objectManager;
        $this->_infyLogger = $logger;
    }

    /**
     * Gets the directories of all modules
     *
     * @access public
     * @param $dir
     * @return \Generator
     * @throws InfyException
     */
    public function loopModulesByDirectory($dir)
    {
        $namespaceList = $this->getDirsByDirPath($dir, self::DIRECTORY_TYPE_MODULE);
        foreach ($namespaceList as $namespace) {
            $namespaceDir = implode('', [$dir, $namespace]);
            $moduleList = $this->getDirsByDirPath($namespaceDir, self::DIRECTORY_TYPE_MODULE);
            foreach ($moduleList as $module) {
                $moduleDir = implode('/', [$namespaceDir, $module]);
                yield $moduleDir;
            }
        }
    }

    /**
     * Get directories by dir path
     *
     * @access public
     * @param $dir
     * @param null $type
     * @return mixed
     * @throws InfyException
     */
    public function getDirsByDirPath($dir, $type = null)
    {
        return $this->dirNamesValidate(array_diff(scandir($dir), [".", ".."]), $type);
    }

    /**
     * Get module namespace by DIRECTORY_PATH
     *
     * @access public
     * @param $moduleDir
     * @param $dirType
     * @return string
     */
    public function getModuleNamespaceByPath($moduleDir, $dirType)
    {
        $namespaceParts = explode('/', str_replace($dirType, '', $moduleDir));
        switch ($dirType) {
            case self::DIRECTORY_PATH_COMMUNITY:
                array_unshift($namespaceParts, 'Community');
                break;
        }
        return implode('\\', $namespaceParts);
    }

    /**
     * Get module namespace by alias module name
     *
     * @access public
     * @param string $aliasModuleName
     * @return string
     */
    public function getModuleNamespaceByAlias($aliasModuleName)
    {
        return implode('\\', explode('_', $aliasModuleName));
    }

    /**
     * Get class namespace by class type
     *
     * @access public
     * @param array $path
     * @param null $type
     * @return string
     */
    public function getFileNamespace(array $path, $type = null)
    {
        switch ($type) {
            case self::MODULE_CLASS_TYPE_CONTROLLER:
                $moduleCatalog = ucfirst(self::MODULE_CLASS_TYPE_CONTROLLER);
                $suffixClass = ucfirst(self::MODULE_CLASS_TYPE_CONTROLLER);
                break;
            default:
                $moduleCatalog = ucfirst($type);
                $suffixClass = '';
        }

        foreach ($path as $key => $item) {
            $path[$key] = ucfirst($item);
        }

        if (!is_null($type)) {
            return implode('\\', [$moduleCatalog, (implode('\\', $path))]) . $suffixClass;
        } else {
            return implode('\\', $path);
        }
    }

    /**
     * Clear uncorrected symbols for dirs
     *
     * @access public
     * @param $list
     * @param $type
     * @return mixed
     * @throws InfyException
     */
    public function dirNamesValidate($list, $type = 'default')
    {
        switch ($type) {
            case self::DIRECTORY_TYPE_MODULE:
                $pattern = "/^[a-zA-Z0-9]+$/";
                break;
            default:
                $pattern = "/^[a-zA-Z]+$/";
        }

        foreach ($list as $key => $value) {
            if (!preg_match($pattern, $value)) {
                throw new InfyException(
                    'INVALID_DIR_NAME',
                    InfyException::ERROR_TYPE_CRITICAL,
                    ['dir' => $value, 'type' => $type]
                );
            }
        }

        return $list;
    }

    /**
     * Get action name for controller
     *
     * @access public
     * @param $controller
     * @param $params
     * @return string
     */
    public function getActionName($controller, &$params)
    {
        $actionName = $params[0] . 'Action';
        if (method_exists($controller, $actionName)) {
            unset($params[0]);
            return $actionName;
        } else {
            return 'indexAction';
        }
    }

    /**
     * Get module list by directory
     *
     * @access public
     * @param $typeDir
     * @return mixed
     */
    public function getModulesByDir($typeDir)
    {
        if (!is_null($this->moduleCollection)) {
            if (isset($this->moduleCollection[$typeDir])) {
                return $this->moduleCollection[$typeDir];
            } else {
                return null;
            }
        } else {
            if (!file_exists(self::MODULE_LIST_FILE_PATH)) {
                $moduleFactory = $this->_objectManager->getObject('Infy\Core\App\ModuleFactory');
                /* @var $moduleFactory \Infy\Core\App\ModuleFactory */
                $moduleFactory->getModuleCollection();
            }
            $this->moduleCollection = unserialize(file_get_contents(self::MODULE_LIST_FILE_PATH));
            if (isset($this->moduleCollection[$typeDir])) {
                return $this->moduleCollection[$typeDir];
            } else {
                return null;
            }
        }
    }

    /**
     * Get config by item
     *
     * @access private
     * @param $item
     * @return null|\SimpleXMLElement
     */
    private function getConfigItem($item)
    {
        if (file_exists(ROOT . '/cfg/config.xml')) {
            $config = simplexml_load_file(ROOT . '/cfg/config.xml');
            if (isset($config->$item)) {
                return $config->$item;
            } else {
                $this->_infyLogger->infy(
                    'The file ' . ROOT . '/cfg/config.xml is invalid.',
                    ['error_name' => 'CONFIG_XML_IS_INVALID', 'file' => __FILE__, 'line' => __LINE__]
                );
                return null;
            }
        } else {
            $this->_infyLogger->infy(
                'The file config.xml in ' . ROOT . '/cfg/ does not exists.',
                ['error_name' => 'CONFIG_XML_DOES_NOT_EXISTS', 'file' => __FILE__, 'line' => __LINE__]
            );
            return null;
        }
    }

    /**
     * Get config language
     *
     * @access public
     * @return null|string
     */
    public function getLanguage()
    {
        $language = $this->getConfigItem(self::CONFIG_TYPE_LANGUAGE);
        if (!is_null($language)) {
            return strval($language);
        } else {
            return null;
        }
    }

    /**
     * Get message by error name, error type and current config language
     *
     * @access public
     * @param $errorName
     * @param $errorType
     * @param $language
     * @return null|string
     */
    public function getMessageByErrorName($errorName, $errorType, $language)
    {
        $filePath = ROOT . '/var/errors/messages/' . $language . '/' . $errorType . '.ser';

        if (file_exists($filePath)) {
            $errorList = unserialize(file_get_contents($filePath));
            if (isset($errorList[$errorName]['message'])) {
                $message = strval($errorList[$errorName]['message']);
                return $message;
            } else {
                /************************* ERROR_NAME_DOES_NOT_EXISTS_OR_UNCORRECTED *************************/
                $this->_infyLogger->infy(
                    'The error_name [' . $errorName . '] does not exists or uncorrected.',
                    ['error_name' => 'ERROR_NAME_DOES_NOT_EXISTS_OR_UNCORRECTED', 'file' => __FILE__, 'line' => __LINE__]
                );
                return null;
            }
        } else {
            /************************* FILE_DOES_NOT_EXISTS *************************/
            $this->_infyLogger->infy(
                'The file ' . $filePath . ' does not exists.',
                ['error_name' => 'FILE_DOES_NOT_EXISTS', 'file' => __FILE__, 'line' => __LINE__]
            );
            return null;
        }
    }

}