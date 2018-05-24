<?php

namespace Infy\Core\App\Database\Management;

use Infy\Core\App\Database\DataBase;

class QueryManager
{
    const QUERY_TYPE_INSERT = 'insert';

    public function saveTable($tableName, $table)
    {
        DataBase::createTable($tableName, $table);
    }
}