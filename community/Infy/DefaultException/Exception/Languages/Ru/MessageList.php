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

namespace Community\Infy\DefaultException\Exception\Languages\Ru;

use Infy\Core\App\Exception\ExceptionList;
use Infy\Core\App\Exception\InfyException as ErrorType;

/**
 * Class MessageList
 *
 * @category Community
 * @package Community\Infy\DefaultException\Exception\Languages\Ru
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
        $message = 'Невозможно создать экземпляр класса [class_name]';
        $messageList->addMessage('IS_NOT_INSTANTIABLE', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Класс [class_name] не существует';
        $messageList->addMessage('CLASS_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = '[var] не является объектом';
        $messageList->addMessage('NOT_OBJECT', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Некорректное имя каталога [dir]. Тип каталога: [type]. Подробнее ...';
        $messageList->addMessage('INVALID_DIR_NAME', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Такого action [action] для контроллера [controller] не существует. Подробнее ...';
        $messageList->addMessage('ACTION_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Класс контроллер [controller] не существует. Подробнее ...';
        $messageList->addMessage('CONTROLLER_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Конфигурационный файл [file_path] заполнен некорректно. Подробнее ...';
        $messageList->addMessage('MODULE_XML_IS_INVALID', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Конфигурационный файл [file_path] заполнен некорректно. Подробнее ...';
        $messageList->addMessage('CONFIG_XML_IS_INVALID', ErrorType::ERROR_TYPE_CRITICAL, $message);

        $message = 'Не найден конфигурационный файл [file_path]. Подробнее ...';
        $messageList->addMessage('CONFIG_XML_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_CRITICAL, $message);



        $message = 'Объект класса [object_class] не существует в DI-контейнере.';
        $messageList->addMessage('OBJECT_DOES_NOT_EXISTS_IN_DIC', ErrorType::ERROR_TYPE_WARNING, $message);

        $message = 'Произошла коллизия модулей [modules] для маршрута [route]. Подробнее ...';
        $messageList->addMessage('MODULES_COLLISION_IN_ROUTE', ErrorType::ERROR_TYPE_WARNING, $message);

        $message = 'Не найден конфигурационный файл [file_path]. Подробнее ...';
        $messageList->addMessage('MODULE_XML_DOES_NOT_EXISTS', ErrorType::ERROR_TYPE_WARNING, $message);
    }
}