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

namespace Infy\Core\App\Exception\ExceptionList;

use Infy\Core\App\Exception\InfyException as ErrorType;

/**
 * Class MessageList
 *
 * @category Core
 * @package Infy\Core\App\Exception
 * @author <maksimglaz@gmail.com>
 */
final class MessageList
{
    const ERROR_MESSAGES_FILE_PATH = ROOT . '/var/errors/messages';
    const CRITICAL_MESSAGE_FILE = 'critical.ser';
    const WARNING_MESSAGE_FILE = 'warning.ser';

    /**
     * Language current dir
     *
     * @var string
     */
    private $language;

    /**
     * List all language critical messages
     *
     * @var array
     */
    private $criticalMessages;

    /**
     * List all language warning messages
     *
     * @var array
     */
    private $warningMessages;

    /**
     * @var \Infy\Core\Config\Config
     */
    protected $_config;

    /**
     * MessageList constructor.
     * @param \Infy\Core\Config\Config $config
     * @throws \Infy\Core\App\Exception\InfyException
     */
    public function __construct(\Infy\Core\Config\Config $config)
    {
        $this->_config = $config;
        $this->getMessageList();
    }

    /**
     * Get critical and warning message list
     *
     * @access private
     * @return void
     * @throws \Infy\Core\App\Exception\InfyException
     */
    private function getMessageList()
    {
        if (file_exists(self::ERROR_MESSAGES_FILE_PATH)) {
            $languageDirs = $this->_config->getDirsByDirPath(self::ERROR_MESSAGES_FILE_PATH);
            foreach ($languageDirs as $languageDir) {
                $criticalMessageFile = implode('/', [
                    self::ERROR_MESSAGES_FILE_PATH,
                    $languageDir,
                    self::CRITICAL_MESSAGE_FILE
                ]);
                if (file_exists($criticalMessageFile)) {
                    $this->criticalMessages[$languageDir] = unserialize(file_get_contents($criticalMessageFile));
                }

                $warningMessageFile = implode('/', [
                    self::ERROR_MESSAGES_FILE_PATH,
                    $languageDir,
                    self::WARNING_MESSAGE_FILE
                ]);
                if (file_exists($warningMessageFile)) {
                    $this->warningMessages[$languageDir] = unserialize(file_get_contents($warningMessageFile));
                }
            }

        } else {
            mkdir(self::ERROR_MESSAGES_FILE_PATH, 0777, true);
        }

    }

    /**
     * Set current language
     *
     * @access public
     * @param $language
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = mb_strtolower($language);
    }

    /**
     * Add message to messages by error type
     *
     * @access public
     * @param $name
     * @param $type
     * @param $message
     * @return void
     */
    public function addMessage($name, $type, $message)
    {
        switch ($type) {
            case ErrorType::ERROR_TYPE_CRITICAL:
                $this->criticalMessages[$this->language][$name]['message'] = $message;
                break;
            case ErrorType::ERROR_TYPE_WARNING:
                $this->warningMessages[$this->language][$name]['message'] = $message;
                break;
        }
    }

    /**
     * Save messages
     *
     * @access private
     * @param $messages
     * @param $type
     * @return void
     */
    private function saveMessages($messages, $type)
    {
        switch ($type) {
            case ErrorType::ERROR_TYPE_CRITICAL:
                $file = self::CRITICAL_MESSAGE_FILE;
                break;
            case ErrorType::ERROR_TYPE_WARNING:
                $file = self::WARNING_MESSAGE_FILE;
                break;
            default:
                $file = self::CRITICAL_MESSAGE_FILE;
        }

        if (!is_null($messages)) {
            foreach ($messages as $language => $message) {
                $message = serialize($message);
                $pathToFile = implode('/', [self::ERROR_MESSAGES_FILE_PATH, $language, $file]);
                $pathToFileDir = implode('/', [self::ERROR_MESSAGES_FILE_PATH, $language]);
                if (!file_exists($pathToFileDir)) {
                    mkdir($pathToFileDir, 0777, true);
                }
                $messageListFile = fopen($pathToFile, 'w+');
                fwrite($messageListFile, $message);
                fclose($messageListFile);
            }
        }

    }

    /**
     * MessageList destructor
     */
    public function __destruct()
    {
        $this->saveMessages($this->criticalMessages, ErrorType::ERROR_TYPE_CRITICAL);
        $this->saveMessages($this->warningMessages, ErrorType::ERROR_TYPE_WARNING);
    }

}