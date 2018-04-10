<?php

namespace Nure\Lab1\Controller\Nure;
use Nure\Lab1\Model\Library\Book;
use Nure\Lab1\Model\Library\Journal;
use Nure\Lab1\Model\Library\Newspaper;


class Lab1Controller
{
    public function indexAction(){
        $book = new Book();
        $bookByName = $book->getBookByName('History');
        $beginDate = '01.01.2018';
        $endDate = '31.12.2018';
        $booksByPeriod = $book->getBookByPeriod($beginDate, $endDate);
        $booksByAuthor = $book->getBookByAuthor('Max Glazkov');
        $journal = new Journal();
        $journalByName = $journal->getJournalByName('Fishing');
        $journalByPeriod = $journal->getJournalByPeriod($beginDate, $endDate);
        $newspaper = new Newspaper();
        $newspaperByName = $newspaper->getNewspaperByName('News');
        $newspapersByPeriod = $newspaper->getNewspaperByPeriod($beginDate, $endDate);

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