<?php

namespace Nure\Lab1\Model;
use Infy\Core\Database\DataBase;

/**
 * Class LibraryAbstract
 * @package Nure\Lab1\Model
 */
abstract class LibraryAbstract
{
    /**
     * Name book|journal|newspaper
     */
    protected $name;

    protected $_pdo;

    public function __construct()
    {
        $this->_pdo = new Database();
    }

    //abstract public function getName();

}