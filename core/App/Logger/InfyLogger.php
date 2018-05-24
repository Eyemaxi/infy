<?php

namespace Infy\Core\App\Logger;
use Psr\Log;


class InfyLogger extends Log\LogLevel implements Log\LoggerInterface
{

    const CRITICAL_LOG_FILE_PATH = ROOT . '/var/log/critical.log';
    const WARNING_LOG_FILE_PATH = ROOT . '/var/log/warning.log';

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->writeExceptionLog($message, $context, self::CRITICAL_LOG_FILE_PATH, self::CRITICAL);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->writeExceptionLog($message, $context, self::WARNING_LOG_FILE_PATH, self::WARNING);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        // TODO: Implement info() method.
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
    }

    /**
     * Infy Framework errors.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function infy($message, array $context = array())
    {
        $this->writeExceptionLog($message, $context, self::CRITICAL_LOG_FILE_PATH);
    }

    /**
     * Write log to var/log/*
     *
     * @access protected
     * @param $message
     * @param $errorInfo
     * @param $errorType
     * @param $filePath
     * @return void
     */
    protected function writeExceptionLog($message, $errorInfo, $filePath, $errorType = 'core')
    {
        if (!file_exists($filePath)) {
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }
            $file = fopen($filePath, 'a');
            fclose($file);
        }
        $fileLog = file_get_contents($filePath);
        $dateTime = date('d-M-Y G:i:s');
        $traceLog = 'Trace:' . "\t\n";
        $traceLog .= 'FILE: ' . $errorInfo['file'] . ' LINE: ' . $errorInfo['line'] . "\t\n";
        if (isset($errorInfo['trace'])) {
            foreach ($errorInfo['trace'] as $trace) {
                $traceLog .= 'FILE: ' . $trace['file'] . ' LINE: ' . $trace['line'] . ' FUNCTION: ' . $trace['class'] . '::' . $trace['function'] . "\t\n";
            }
        }
        $headerLog = '[' . $dateTime . '] ' . '[' . strtoupper($errorType). '] ' . '['. $errorInfo['error_name'] . '] ' . "\t\n";
        if (!empty($message)) {
            $message .= "\t\n";
        }
        $fileLog .= $headerLog . $message . $traceLog . "\t\n";
        file_put_contents($filePath, $fileLog);
    }
}