<?php

namespace Nure\Lab1\Controller\Nure;
use Nure\Lab1\Model\Library\Book;
use Nure\Lab1\Model\Library\Journal;
use Nure\Lab1\Model\Library\Newspaper;


class Lab1Controller
{
    public function indexAction(){
        $book = new Book();
        $journal = new Journal();
        $newspaper = new Newspaper();
    }
}