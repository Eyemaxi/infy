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

namespace Infy\Core\App\Exception\Factory;

use Infy\Core\App\Exception\InfyException;
use Infy\Core\Config\Config;

/**
 * Class ExceptionFactory
 *
 * @category Core
 * @package Infy\Core\App\Exception
 * @author <maksimglaz@gmail.com>
 */
final class ExceptionFactory
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
     * @var \Infy\Core\App\Exception\ExceptionList\ErrorList
     */
    protected $_errorList;

    /**
     * @var \Infy\Core\App\Exception\ExceptionList\MessageList
     */
    protected $_messageList;

    /**
     * ErrorFactory constructor.
     * @param Config $config
     * @param \Infy\Core\App\ObjectManager $objectManager
     * @param \Infy\Core\App\Exception\ExceptionList\ErrorList $errorList
     * @param \Infy\Core\App\Exception\ExceptionList\MessageList $messageList
     */
    public function __construct(
        Config $config,
        \Infy\Core\App\ObjectManager $objectManager,
        \Infy\Core\App\Exception\ExceptionList\ErrorList $errorList,
        \Infy\Core\App\Exception\ExceptionList\MessageList $messageList
    ) {
        $this->_config = $config;
        $this->_objectManager = $objectManager;
        $this->_errorList = $errorList;
        $this->_messageList = $messageList;
    }

    /**
     * Merge error list and messages for all modules
     *
     * @access public
     * @return void
     * @throws InfyException
     */
    public function mergeErrors()
    {
        $this->mergeErrorsByDirectory(Config::DIRECTORY_TYPE_COMMUNITY);
        $this->mergeErrorsByDirectory(Config::DIRECTORY_TYPE_EXTENDS);
    }

    /**
     * Merge error list and message by directory
     *
     * @access private
     * @param $dir
     * @return void
     * @throws InfyException
     */
    private function mergeErrorsByDirectory($dir)
    {
        $modules = $this->_config->getModulesByDir($dir);
        if (!is_null($modules)) {
            foreach ($modules as $module) {
                $exceptionPath = implode('/', [$module['dir_path'], 'Exception']);
                $moduleNamespace = $module['namespace'];
                $errorListClass = implode('\\', [$moduleNamespace, 'Exception', 'ErrorList']);
                if (class_exists($errorListClass)) {
                    /* @var $errorList \Infy\Core\App\Exception\ExceptionList\ErrorListInterface */
                    $errorList = $this->_objectManager->getObject($errorListClass);
                    $errorList->execute($this->_errorList);
                    $this->_objectManager->removeObject($errorList);
                }
                $exceptionLanguagesPath = implode('/', [$exceptionPath, 'Languages']);
                if (file_exists($exceptionLanguagesPath)) {
                    $exceptionLanguageDirs = $this->_config->getDirsByDirPath(
                        $exceptionLanguagesPath,
                        Config::DIRECTORY_TYPE_MODULE
                    );
                    foreach ($exceptionLanguageDirs as $exceptionLanguageDir) {
                        $messageListClass = implode('\\', [
                            $moduleNamespace,
                            'Exception',
                            'Languages',
                            $exceptionLanguageDir,
                            'MessageList'
                        ]);
                        if (class_exists($messageListClass)) {
                            /* @var $messageList \Infy\Core\App\Exception\ExceptionList\MessageListInterface */
                            $messageList = $this->_objectManager->getObject($messageListClass);
                            $this->_messageList->setLanguage($exceptionLanguageDir);
                            $messageList->execute($this->_messageList);
                            $this->_objectManager->removeObject($messageList);
                        }
                    }
                }
            }
        }

    }

    /**
     * ExceptionFactory destruct
     */
    public function __destruct()
    {
        try {
            $this->_objectManager->removeObject($this->_errorList);
            unset($this->_errorList);
            $this->_objectManager->removeObject($this->_messageList);
            unset($this->_messageList);
        } catch (InfyException $e) {
            $e->getErrorMessage();
        }
    }
}