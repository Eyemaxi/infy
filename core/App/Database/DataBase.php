<?php
namespace Infy\Core\App\Database;

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20.12.2017
 * Time: 17:24
 */
class DataBase
{
    const TYPE_QUERY_CREATE_TABLE_IF_NOT_EXISTS = "CREATE TABLE IF NOT EXISTS `[table_name]` ([columns])";

    public static function getConnection() {
        $xml = simplexml_load_file(ROOT . '/cfg/database.xml');
        $params = $xml->database;

        $dsn = "mysql:host={$params->host};dbname={$params->db};charset={$params->charset}";
        try {
            $pdo = new \PDO($dsn, $params->user, $params->pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
        //$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        return $pdo;
    }

    public static function createTable($tableName, $values)
    {
        $query = self::TYPE_QUERY_CREATE_TABLE_IF_NOT_EXISTS;
        $query = str_replace("[table_name]", "{$tableName}", $query);
        $columns = [];

        foreach ($values as $columnName => $column) {
            $columnValues = [];
            $columnValues[] = $columnName;
            $columnValues[] = $column['type'];

            if (!is_null($column['size'])) {
                $columnValues[] = "({$column['size']})";
            }
            if ($column['unsigned'] === true) {
                $columnValues[] = 'UNSIGNED';
            }
            if ($column['auto_increment'] === true) {
                $columnValues[] = 'AUTO_INCREMENT';
            }
            if ($column['primary'] === true) {
                $columnValues[] = 'PRIMARY KEY';
            } elseif ($column['null'] === false) {
                $columnValues[] = 'NOT NULL';
            }
            $columns[] = implode(" ", $columnValues);
        }

        $columns = implode(", ", $columns);
        $query = str_replace("[columns]", "{$columns}", $query);
        $pdo = self::getConnection();
        $pdo->exec($query);
    }

    public static function insert($tableName, $values)
    {

    }
}