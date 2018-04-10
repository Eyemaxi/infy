<?php

namespace Nure\Lab1\Model\Library;
use Nure\Lab1\Model\LibraryAbstract;

class Book extends LibraryAbstract implements BookInterface
{
    /**
     * Unique number (ISBN) of the book
     * @access protected
     */
    protected $isbn;

    /**
     * Book publishing
     * @access protected
     */
    protected $publishing;

    /**
     * Year of publication of the book
     * @access protected
     */
    protected $yearPublishing;

    /**
     * Number of pages in a book
     * @access protected
     */
    protected $numberPages;

    public function getBookByName($name)
    {/*
        try {
            $stmt = $db->prepare("INSERT INTO test (label,color) VALUES (?,?)");
            $stmt -> execute(array('perfect','green'));
        }
        catch(PDOException $e){
            echo 'Error : '.$e->getMessage();
            exit();
        }
    */
    }

    public function getBookByPeriod($beginDate, $endDate)
    {

    }

    public function getBookByAuthor($author)
    {

    }
}