<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 25.04.18
 * Time: 13:02
 */

namespace Nure\Lab1\Exception\Languages\Ru;

use Infy\Core\App\Exception\InfyException as ErrorType;
use Infy\Core\App\Exception\ExceptionList;

class MessageList implements ExceptionList\MessageListInterface
{
    public function execute(ExceptionList\MessageList $messageList)
    {
        $message = 'Класс [class_name] не книга';
        $messageList->addMessage('NOT_BOOK', ErrorType::ERROR_TYPE_CRITICAL, $message);
    }
}