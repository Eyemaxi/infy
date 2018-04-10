<?php

namespace Nure\Lab1\Model\Library;
use Nure\Lab1\Model\LibraryAbstract;

class Newspaper extends LibraryAbstract implements NewspaperInterface
{
    /**
     * Date of publication of the newspaper
     * @access protected
     */
    protected $datePublication;

    public function getNewspaperByName($name)
    {

    }

    public function getNewspaperByPeriod($beginDate, $endDate)
    {

    }
}