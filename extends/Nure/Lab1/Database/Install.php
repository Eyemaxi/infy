<?php

namespace Nure\Database;


use Infy\Core\App\Database\InstallInterface;

class Install implements InstallInterface
{

    /**
     * Install the tables
     *
     * @access public
     * @param \Infy\Core\App\Database\Install $installer
     * @return mixed
     */
    public function install(\Infy\Core\App\Database\Install $installer)
    {
        $installer->createTable('author')
            ->addColumn('id', $installer::TYPE_INT, 11, [
            'primary' => true,
            'unsigned' => true,
            'null' => false
            ])->addColumn('author_name', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ]);
    }
}