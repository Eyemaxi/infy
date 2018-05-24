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

namespace Infy\Core\App\Database;

/**
 * Class Install
 *
 * @category Core
 * @package Infy\Core\App\Database
 * @author <maksimglaz@gmail.com>
 */
class Install extends Table
{
    protected $tableName;
    protected $tableColumns;

    protected $_queryManager;

    public function __construct(Management\QueryManager $queryManager)
    {
        $this->_queryManager = $queryManager;
    }

    public function createTable($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function addColumn($name, $type, $size, $values)
    {
        $columnIsPrimary = false;
        $columnIsUnsigned = false;
        $columnIsAI = false;
        $columnIsNull = true;

        $name = strval(mb_strtolower($name));
        if (isset($values['primary']) && is_bool($values['primary'])) {
            $columnIsPrimary = $values['primary'];
        }

        if (isset($values['unsigned']) && is_bool($values['unsigned'])) {
            $columnIsUnsigned = $values['unsigned'];
        }

        if (isset($values['auto_increment']) && is_bool($values['auto_increment'])) {
            $columnIsAI = $values['auto_increment'];
        }

        if (isset($values['null']) && is_bool($values['null'])) {
            $columnIsNull = $values['null'];
        }

        $this->tableColumns[$name] = [
            'type' => $type,
            'size' => $size,
            'primary' => $columnIsPrimary,
            'unsigned' => $columnIsUnsigned,
            'auto_increment' => $columnIsAI,
            'null' => $columnIsNull
        ];

        return $this;
    }

    public function addForeignKey()
    {

    }

    public function save()
    {
        $this->setEntity($this->tableName, $this->tableColumns);
        $this->tableColumns = null;
    }


}