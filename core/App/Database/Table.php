<?php

namespace Infy\Core\App\Database;


class Table extends Table\Type
{
    protected $entities;

    protected $_queryManager;

    public function __construct(Management\QueryManager $queryManager)
    {
        $this->_queryManager = $queryManager;
    }

    protected function setEntity($entityName, $attributes)
    {
        $this->entities[$entityName] = $attributes;
    }

    private function saveEntities()
    {
        foreach ($this->entities as $entityName => $entity) {
            $this->_queryManager->saveTable($entityName, $entity);
        }
    }

    public function __destruct()
    {
        $this->saveEntities();
    }
}