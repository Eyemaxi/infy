<?php


namespace Infy\Core\App\Database\Factory;

use Infy\Core\Config\Config;

class TableFactory
{

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var \Infy\Core\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var \Infy\Core\App\Database\Install
     */
    protected $_dbInstall;

    public function __construct(
        Config $config,
        \Infy\Core\App\ObjectManager $objectManager,
        \Infy\Core\App\Database\Install $dbInstall
    ) {
        $this->_config = $config;
        $this->_objectManager = $objectManager;
        $this->_dbInstall = $dbInstall;
    }

    /**
     * Save modules tables
     *
     * @access public
     * @return void
     */
    public function saveTables()
    {
        $this->setTablesByDirectory(Config::DIRECTORY_TYPE_COMMUNITY);
        $this->setTablesByDirectory(Config::DIRECTORY_TYPE_EXTENDS);
    }


    private function setTablesByDirectory($dir)
    {
        $modules = $this->_config->getModulesByDir($dir);
        if (!is_null($modules)) {
            foreach ($modules as $module) {
                $moduleNamespace = $module['namespace'];
                $installTableClass = implode('\\', [$moduleNamespace, 'Database', 'Install']);
                if (class_exists($installTableClass)) {
                    /* @var $installTable \Infy\Core\App\Database\InstallInterface */
                    $installTable = $this->_objectManager->getObject($installTableClass);
                    $installTable->install($this->_dbInstall);
                    $this->_objectManager->removeObject($installTable);
                    unset($installTable);
                }
            }
        }
    }

    public function __destruct()
    {
        $this->_objectManager->removeObject($this->_dbInstall);
        unset($this->_dbInstall);
    }
}