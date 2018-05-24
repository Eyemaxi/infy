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

namespace Community\Infy\DefaultException\Exception;
use Infy\Core\App\Exception\ExceptionList;

/**
 * Class ErrorList
 *
 * @category Community
 * @package Community\Infy\DefaultException\Exception
 * @author <maksimglaz@gmail.com>
 */
class ErrorList implements ExceptionList\ErrorListInterface
{

    /**
     * Execute ErrorList
     *
     * @access public
     * @param ExceptionList\ErrorList $errorList
     * @return mixed|void
     */
    public function execute(ExceptionList\ErrorList $errorList)
    {
        $errorList->setCritical('IS_NOT_INSTANTIABLE', ['class_name']);
        $errorList->setCritical('CLASS_DOES_NOT_EXISTS', ['class_name']);
        $errorList->setCritical('NOT_OBJECT', ['var']);
        $errorList->setCritical('INVALID_DIR_NAME', ['dir', 'type']);
        $errorList->setCritical('ACTION_DOES_NOT_EXISTS', ['controller', 'action']);
        $errorList->setCritical('CONTROLLER_DOES_NOT_EXISTS', ['controller']);
        $errorList->setCritical('MODULE_XML_IS_INVALID', ['file_path']);
        $errorList->setCritical('CONFIG_XML_IS_INVALID', ['file_path']);
        $errorList->setCritical('CONFIG_XML_DOES_NOT_EXISTS', ['file_path']);

        $errorList->setWarning('OBJECT_DOES_NOT_EXISTS_IN_DIC', ['object_class']);
        $errorList->setWarning('MODULES_COLLISION_IN_ROUTE', ['modules', 'route']);
        $errorList->setWarning('MODULE_XML_DOES_NOT_EXISTS', ['file_path']);
    }
}