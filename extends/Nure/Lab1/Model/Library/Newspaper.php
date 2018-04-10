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
        return $this->getLibraryItemByName('newspaper', $name);
    }

    public function getNewspaperByPeriod($beginDate, $endDate)
    {
        $beginDate = $this->getFormatDate($beginDate,'Y-m-d');
        $endDate = $this->getFormatDate($endDate,'Y-m-d');
        return $this->getLibraryItemByPeriod('newspaper', $beginDate, $endDate);
    }
}