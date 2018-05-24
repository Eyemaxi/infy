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

namespace Infy\Core\App;

use Infy\Core\App\Exception\InfyException;
use \Infy\Core\Config\Config;

/**
 * Class ModuleFactory
 *
 * @category Core
 * @package Infy\Core\App
 * @author <maksimglaz@gmail.com>
 */
class ModuleFactory
{
    /**
     * Module collection
     *
     * @var array
     */
    private $modules;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * ModuleFactory constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    /**
     * Get module collection
     *
     * @access public
     * @return void
     */
    public function getModuleCollection()
    {
        $this->getModuleCollectionByDirectory(Config::DIRECTORY_TYPE_COMMUNITY);
        $this->getModuleCollectionByDirectory(Config::DIRECTORY_TYPE_EXTENDS);
        $this->saveModuleCollection();
    }

    /**
     * Get module collection by directory
     *
     * @access private
     * @param $dirType
     * @return void
     */
    private function getModuleCollectionByDirectory($dirType)
    {
        try {
            $dir = ROOT . '/' . $dirType . '/';
            $moduleDirs = $this->_config->loopModulesByDirectory($dir);
            foreach ($moduleDirs as $moduleDir) {
                $moduleXmlPath = $moduleDir . '/cfg/module.xml';
                if (file_exists($moduleXmlPath)) {
                    $module = simplexml_load_file($moduleXmlPath);
                    if (!isset($module->module->Namespace) ||
                        !isset($module->module->ModuleName) ||
                        !isset($module->module->active)
                    ) {
                        throw new InfyException(
                            'MODULE_XML_IS_INVALID',
                            InfyException::ERROR_TYPE_CRITICAL,
                            ['file_path' => $moduleXmlPath]
                        );
                    }
                    $moduleName = implode('_', [$module->module->Namespace, $module->module->ModuleName]);
                    $this->modules[$dirType][$moduleName]['active'] = strval($module->module->active);
                    $this->modules[$dirType][$moduleName]['dir_path'] = $moduleDir;
                    $this->modules[$dirType][$moduleName]['namespace'] = $this->_config->getModuleNamespaceByPath($moduleDir, $dir);
                } else {
                    try {
                        throw new InfyException(
                            'MODULE_XML_DOES_NOT_EXISTS',
                            InfyException::ERROR_TYPE_WARNING,
                            ['file_path' => $moduleXmlPath]
                        );
                    } catch (InfyException $e) {
                        $e->getErrorMessage();
                    }
                }
            }
        } catch (InfyException $e) {
            $e->getErrorMessage();
        }
    }

    /**
     * Save module collection to serialize file
     *
     * @access private
     * @return void
     */
    private function saveModuleCollection()
    {
        if (!file_exists(dirname(Config::MODULE_LIST_FILE_PATH))) {
            mkdir(dirname(Config::MODULE_LIST_FILE_PATH), 0777, true);
        }
        $moduleList = serialize($this->modules);
        $moduleListFile = fopen(Config::MODULE_LIST_FILE_PATH, 'w');
        fwrite($moduleListFile, $moduleList);
    }

}