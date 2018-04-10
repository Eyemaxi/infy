<?php

namespace Nure\Lab1\Model\Library;
use Nure\Lab1\Model\LibraryAbstract;

class Journal extends LibraryAbstract implements JournalInterface
{
    /**
     * Year of issue
     * @access protected
     */
    protected $yearIssue;

    /**
     * Number of journal
     * @access protected
     */
    protected $number;

    public function getJournalByName($name)
    {
        return $this->getLibraryItemByName('journal', $name);
    }

    public function getJournalByPeriod($beginDate, $endDate)
    {
        $beginDate = $this->getFormatDate($beginDate,'Y');
        $endDate = $this->getFormatDate($endDate,'Y');
        return $this->getLibraryItemByPeriod('journal', $beginDate, $endDate);
    }

}