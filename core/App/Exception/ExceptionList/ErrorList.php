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

/**
 * Class ErrorList
 *
 * @category Core
 * @package Infy\Core\App\Excecption
 * @author <maksimglaz@gmail.com>
 */
final class ErrorList
{
    const CRITICAL_LIST_FILE_PATH = ROOT . '/var/errors/critical.ser';
    const WARNING_LIST_FILE_PATH = ROOT . '/var/errors/warning.ser';

    /**
     * Critical list
     *
     * @var array
     */
    protected $criticalList;

    /**
     * Warning list
     *
     * @var array
     */
    protected $warningList;

    /**
     * ErrorList constructor.
     */
    public function __construct()
    {
        $this->getCriticalList();
        $this->getWarningList();
    }

    /**
     * Get critical list
     *
     * @access private
     * @return void
     */
    private function getCriticalList()
    {
        if (file_exists(dirname(self::CRITICAL_LIST_FILE_PATH))) {
            if (file_exists(self::CRITICAL_LIST_FILE_PATH)) {
                $this->criticalList = unserialize(file_get_contents(self::CRITICAL_LIST_FILE_PATH));
            }
        } else {
            mkdir(dirname(self::CRITICAL_LIST_FILE_PATH), 0777, true);
        }

    }

    /**
     * Get warning list
     *
     * @access private
     * @return void
     */
    private function getWarningList()
    {
        if (file_exists(dirname(self::WARNING_LIST_FILE_PATH))) {
            if (file_exists(self::WARNING_LIST_FILE_PATH)) {
                $this->warningList = unserialize(file_get_contents(self::WARNING_LIST_FILE_PATH));
            }
        } else {
            mkdir(dirname(self::WARNING_LIST_FILE_PATH), 0777, true);
        }
    }

    /**
     * Set critical
     *
     * @access public
     * @param $criticalName
     * @param null $args
     * @return void
     */
    public function setCritical($criticalName, $args = null)
    {
        if (!isset($this->criticalList[$criticalName])) {
            if (!is_null($args)) {
                $this->criticalList[$criticalName] = $args;
            }
        }
    }

    /**
     * Set warning
     *
     * @access public
     * @param $warningName
     * @param null $args
     * @return void
     */
    public function setWarning($warningName, $args = null)
    {
        if (!isset($this->warningList[$warningName])) {
            if (!is_null($args)) {
                $this->warningList[$warningName] = $args;
            }
        }
    }

    /**
     * Save critical list
     *
     * @access private
     * @return void
     */
    private function saveCriticalList()
    {
        $criticalList = serialize($this->criticalList);
        $criticalListFile = fopen(self::CRITICAL_LIST_FILE_PATH, 'w+');
        fwrite($criticalListFile, $criticalList);
        fclose($criticalListFile);
    }

    /**
     * Save warning list
     *
     * @access private
     * @return void
     */
    private function saveWarningList()
    {
        $warningList = serialize($this->warningList);
        $warningListFile = fopen(self::WARNING_LIST_FILE_PATH, 'w+');
        fwrite($warningListFile, $warningList);
        fclose($warningListFile);
    }

    /**
     * ErrorList destructor.
     */
    public function __destruct()
    {
        $this->saveCriticalList();
        $this->saveWarningList();
    }

}