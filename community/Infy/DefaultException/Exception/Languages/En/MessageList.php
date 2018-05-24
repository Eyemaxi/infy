<?php
/**
 * Infy Framework
 *
 * @author    <maksimglaz@gmail.com>
 * @category  Community
 * @package   Infy\DefaultException
 * @copyright Copyright (c) 2018 Infy
 * @license   https://www.infy-team.com/license.txt
 */

namespace Community\Infy\DefaultException\Exception\Languages\En;

use Infy\Core\App\Exception\ExceptionList;
use Infy\Core\App\Exception\InfyException as ErrorType;

/**
 * Class MessageList
 *
 * @category Community
 * @package Community\Infy\DefaultException\Exception\Languages\En
 * @author <maksimglaz@gmail.com>
 */
class MessageList implements ExceptionList\MessageListInterface
{
    /**
     * Execute MessageList
     *
     * @access public
     * @param ExceptionList\MessageList $messageList
     * @return mixed|void
     */
    public function execute(ExceptionList\MessageList $messageList)
    {
        $message = '[class_name] is not instantiable';
        $messageList->addMessage('IS_NOT_INSTANTIABLE', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Class [class_name] does not exists';
        $messageList->addMessage('CLASS_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = '[var] is not object';
        $messageList->addMessage('NOT_OBJECT', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Invalid directory name [dir]. Type dir: [type]. Read more ...';
        $messageList->addMessage('INVALID_DIR_NAME', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'There is no such action [action] for the controller [controller]. Read more ...';
        $messageList->addMessage('ACTION_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'The controller class [controller] does not exists. Read more ...';
        $messageList->addMessage('CONTROLLER_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'The module config file [file_path] is incorrect. Read more ...';
        $messageList->addMessage('MODULE_XML_IS_INVALID', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'The config file [file_path] is incorrect. Read more ...';
        $messageList->addMessage('CONFIG_XML_IS_INVALID', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'The config file [file_path] was not found. Read more ...';
        $messageList->addMessage('CONFIG_XML_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);



        $message = 'Object class [object_class] does not exists in DI container.';
        $messageList->addMessage('OBJECT_DOES_NOT_EXISTS_IN_DIC', ErrorType::ERROR_TYPE_WARNING, $message);

        $message = 'Collision of modules [modules] in the route [route]. Read more ...';
        $messageList->addMessage('MODULES_COLLISION_IN_ROUTE', ErrorType::ERROR_TYPE_WARNING, $message);

        $message = 'The config file [file_path] was not found. Read more ...';
        $messageList->addMessage('MODULE_XML_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_WARNING, $message);
    }
}