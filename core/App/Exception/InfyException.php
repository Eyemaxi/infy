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

namespace Infy\Core\App\Exception;

use Infy\Core\App\ObjectManager;

/**
 * Class InfyException
 *
 * @category Core
 * @package Infy\Core\App\Exception
 * @author <maksimglaz@gmail.com>
 */
class InfyException extends \Exception
{
    const ERROR_TYPE_CRITICAL = 'critical';
    const ERROR_TYPE_WARNING = 'warning';
    const DEFAULT_LANGUAGE = 'en';

    protected $error;

    /**
     * @var ObjectManager|object
     */
    protected $_objectManager;

    /**
     * @var \Infy\Core\App\Logger\InfyLogger
     */
    protected $_infyLogger;

    /**
     * @var \Infy\Core\Config\Config
     */
    protected $_config;

    /**
     * InfyException constructor.
     * @param $errorName
     * @param $type
     * @param array|null $args
     */
    public function __construct($errorName, $type, array $args = null)
    {
        $this->error['error_name'] = $errorName;
        $this->error['type'] = $type;
        $this->error['args'] = $args;

        $this->_objectManager = ObjectManager::getSingleton();
        $this->_infyLogger = $this->_objectManager->getObject('Infy\Core\App\Logger\InfyLogger');
        /* @var $this ->_infyLogger \Infy\Core\App\Logger\InfyLogger */
        $this->_config = $this->_objectManager->getObject('Infy\Core\Config\Config');
        /* @var $this ->_config \Infy\Core\Config\Config */
    }

    /**
     * Get error message
     *
     * @access public
     * @return void
     */
    public function getErrorMessage()
    {
        $errorName = $this->error['error_name'];
        $errorType = $this->error['type'];
        $errorArgs = $this->error['args'];
        $message = $this->generateMessage($errorName, $errorType, $errorArgs);
        /**************************************** Layout exception block ****************************************/
        echo '<p>' . $message . '</p>';
        $this->setLog($message, $errorType);
        if ($errorType == self::ERROR_TYPE_CRITICAL) {
            die();
        }
    }

    /**
     * Generate message
     *
     * @access private
     * @param $errorName
     * @param $errorType
     * @param $errorArgs
     * @return mixed|null|string
     */
    private function generateMessage($errorName, $errorType, $errorArgs)
    {
        $message = $this->getInfyMessage($errorName, $errorType);
        if (!is_null($message)) {
            foreach ($errorArgs as $argName => $arg) {
                if (is_array($arg)) {
                    $arg = implode(' & ', $arg);
                }
                $message = str_replace("[$argName]", "[$arg]", $message);
                return $message;
            }
        } else {
            return '';
        }
    }

    /**
     * Get message from error message list
     *
     * @access private
     * @param $errorName
     * @param $errorType
     * @return null|string
     */
    private function getInfyMessage($errorName, $errorType)
    {
        $language = $this->_config->getLanguage();
        if (is_null($language)) {
            $language = self::DEFAULT_LANGUAGE;
        }
        $message = $this->_config->getMessageByErrorName($errorName, $errorType, $language);
        return $message;
    }

    /**
     * Set message to log
     *
     * @access private
     * @param $message
     * @param $errorType
     * @return void
     */
    private function setLog($message, $errorType)
    {
        switch ($errorType) {
            case self::ERROR_TYPE_CRITICAL:
                $this->_infyLogger->critical($message, [
                    'error_name' => $this->error['error_name'],
                    'file' => $this->file,
                    'line' => $this->line,
                    'trace' => $this->getTrace()
                ]);
                break;
            case self::ERROR_TYPE_WARNING:
                $this->_infyLogger->warning($message, [
                    'error_name' => $this->error['error_name'],
                    'file' => $this->file,
                    'line' => $this->line,
                    'trace' => $this->getTrace()
                ]);
                break;
            default:
                $this->_infyLogger->log($this->code, $this->message, ['trace' => $this->getTrace()]);
        }
    }
}