<?php

namespace Nure\Lab1\Model;
use Infy\Core\App\Database\DataBase;

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
        $this->_pdo = Database::getConnection();
    }

    public function getLibraryItemByName($type, $name)
    {
        try {
            $libraryItems = array();
            $typeName = '';
            switch ($type) {
                case 'book':
                    $typeName = 'book_name';
                    break;
                case 'journal':
                    $typeName = 'journal_name';
                    break;
                case 'newspaper':
                    $typeName = 'newspaper_name';
                    break;
            }
            $stmt = $this->_pdo->prepare("SELECT * FROM {$type} WHERE {$typeName} = ?");
            $stmt -> execute(array($name));
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                array_push($libraryItems, $row);
            }

            return $libraryItems;

        }
        catch(\PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    }

    public function getLibraryItemByPeriod($type, $beginDate, $endDate)
    {
        try {
            $libraryItems = array();
            $typeName = '';
            switch ($type) {
                case 'book':
                    $typeName = 'year_publishing';
                    break;
                case 'journal':
                    $typeName = 'year_issue';
                    break;
                case 'newspaper':
                    $typeName = 'date_publication';
                    break;
            }
            $stmt = $this->_pdo->prepare("SELECT * FROM {$type} WHERE {$typeName} BETWEEN ? AND ?");
            $stmt -> execute(array($beginDate, $endDate));
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                array_push($libraryItems, $row);
            }

            return $libraryItems;
        } catch(\PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    }

    public function getAuthorIdsByName($author)
    {
        try {
            $authorIds = array();
            $stmt = $this->_pdo->prepare("SELECT id FROM author WHERE `author_name` = ?");
            $stmt -> execute(array($author));
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                array_push($authorIds, $row['id']);
            }

            return $authorIds;

        }
        catch(\PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    }

    public function getBookIdsByAuthorId($authorId)
    {
        try {
            $bookIds = array();
            $stmt = $this->_pdo->prepare("SELECT book_id FROM book_author WHERE `author_id` = ?");
            $stmt -> execute(array($authorId));
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                array_push($bookIds, $row['book_id']);
            }
            return $bookIds;

        }
        catch(\PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    }

    public function getBooksByBookIds($bookIds)
    {
        try {
            $books = array();
            foreach ($bookIds as $bookId) {
                $stmt = $this->_pdo->prepare("SELECT * FROM book WHERE `id` = ?");
                $stmt->execute(array($bookId));
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    array_push($books, $row);
                }
            }
            return $books;

        }
        catch(\PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    }

    public function getFormatDate($date, $format)
    {
        $dateTime = new \DateTime($date);
        return $dateTime->format($format);
    }

    //abstract public function getName();

}