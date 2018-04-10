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
    {
        return $this->getLibraryItemByName('book', $name);
    }

    public function getBookByPeriod($beginDate, $endDate)
    {
        $beginDate = $this->getFormatDate($beginDate,'Y');
        $endDate = $this->getFormatDate($endDate,'Y');
        return $this->getLibraryItemByPeriod('book', $beginDate, $endDate);
    }

    public function getBookByAuthor($author)
    {
        $authorIds = $this->getAuthorIdsByName($author);
        $bookIds = $this->getBookIdsByAuthorId($authorIds[0]);
        return $this->getBooksByBookIds($bookIds);
    }
}