<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20.12.2017
 * Time: 17:24
 */
class Db
{
    public static function getConnection() {
        $xml = simplexml_load_file(ROOT . '/config/config.xml');
        $params = $xml->db_params;

        $dsn = "mysql:host={$params->host};dbname={$params->db};charset={$params->charset}";
        try {
            $pdo = new PDO($dsn, $params->user, $params->pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
        //$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        return $pdo;
    }
}