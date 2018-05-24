<?php

namespace Nure\Lab1\Controller\Nure;


class Lab1Controller
{
    protected $_book;
    protected $_journal;
    protected $_newspaper;

    /**
     * Lab1Controller constructor.
     * @param \Nure\Lab1\Model\Library\Book $book
     * @param \Nure\Lab1\Model\Library\Journal $journal
     * @param \Nure\Lab1\Model\Library\Newspaper $newspaper
     */
    public function __construct(
        \Nure\Lab1\Model\Library\Book $book,
        \Nure\Lab1\Model\Library\Journal $journal,
        \Nure\Lab1\Model\Library\Newspaper $newspaper
    ) {
        $this->_book = $book;
        $this->_journal = $journal;
        $this->_newspaper = $newspaper;
    }

    public function indexAction(){
        $beginDate = '01.01.2018';
        $endDate = '31.12.2018';
        $bookByName = $this->_book->getBookByName('History');
        $booksByPeriod = $this->_book->getBookByPeriod($beginDate, $endDate);
        $booksByAuthor = $this->_book->getBookByAuthor('Max Glazkov');
        $journalByName = $this->_journal->getJournalByName('Fishing');
        $journalByPeriod = $this->_journal->getJournalByPeriod($beginDate, $endDate);
        $newspaperByName = $this->_newspaper->getNewspaperByName('News');
        $newspapersByPeriod = $this->_newspaper->getNewspaperByPeriod($beginDate, $endDate);

        echo '<pre>';
        echo '<h1>Books</h1>';
        var_dump($bookByName);
        var_dump($booksByPeriod);
        var_dump($booksByAuthor);
        echo '<h1>Journal</h1>';
        var_dump($journalByName);
        var_dump($journalByPeriod);
        echo '<h1>Newspaper</h1>';
        var_dump($newspaperByName);
        var_dump($newspapersByPeriod);
        echo '</pre>';
    }
}