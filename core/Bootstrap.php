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

namespace Infy\Core;

use Infy\Core\App\Exception\InfyException;
use Infy\Core\App\ObjectManager;

/**
 * Class Bootstrap
 *
 * @category Core
 * @package Infy\Core
 * @author <maksimglaz@gmail.com>
 */
class Bootstrap
{
    /**
     * @var ObjectManager
     */
    private $_objectManager;

    /**
     * Bootstrap constructor.
     * @param App\ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create Bootstrap class
     *
     * @access public
     * @return Bootstrap
     */
    public static function create()
    {
        $objectManager = ObjectManager::getSingleton();
        return new self($objectManager);
    }

    /**
     * Install components Infy Framework
     *
     * @access public
     * @return void
     */
    public function install()
    {
        try {

            $moduleFactory = $this->_objectManager->getObject('Infy\Core\App\ModuleFactory');
            /* @var $moduleFactory \Infy\Core\App\ModuleFactory */
            $moduleFactory->getModuleCollection();
            $this->_objectManager->removeObject($moduleFactory);
            unset($moduleFactory);

            $exceptionFactory = $this->_objectManager->getObject('Infy\Core\App\Exception\Factory\ExceptionFactory');
            /* @var $exceptionFactory \Infy\Core\App\Exception\Factory\ExceptionFactory */
            $exceptionFactory->mergeErrors();
            $this->_objectManager->removeObject($exceptionFactory);
            unset($exceptionFactory);

            $tableFactory = $this->_objectManager->getObject('Infy\Core\App\Database\Factory\TableFactory');
            /* @var $tableFactory \Infy\Core\App\Database\Factory\TableFactory */
            $tableFactory->saveTables();
            $this->_objectManager->removeObject($tableFactory);
            unset($tableFactory);

            $routerXml = $this->_objectManager->getObject('Infy\Core\Config\Xml\Router');
            /* @var $routerXml \Infy\Core\Config\Xml\Router */
            $routerXml->mergeRoutes();
        } catch (InfyException $e) {
            $e->getErrorMessage();
        }

    }

    /**
     * Launching the page
     *
     * @access public
     * @return void
     */
    public function start()
    {
        try {
            $router = $this->_objectManager->getObject('Infy\Core\Router');
            /* @var $router \Infy\Core\Router */
            $router->run();
        } catch (InfyException $e) {
            $e->getErrorMessage();
        }

    }

}