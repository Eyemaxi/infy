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
 * Interface MessageListInterface
 *
 * @category Core
 * @package Infy\Core\App\Exception\ExceptionList
 * @author <maksimglaz@gmail.com>
 */
interface MessageListInterface
{
    /**
     * Execute MessageList
     *
     * @access public
     * @param MessageList $messageList
     * @return mixed
     */
    public function execute(MessageList $messageList);
}