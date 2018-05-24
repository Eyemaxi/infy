<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 20.04.18
 * Time: 17:12
 */

namespace Nure\Lab1\Exception;
use Infy\Core\App\Exception\ExceptionList;

class ErrorList implements ExceptionList\ErrorListInterface
{
    public function execute(ExceptionList\ErrorList $errorList)
    {
        $errorList->setCritical('NOT_BOOK', ['class_name']);
    }
}