<?php

namespace Nure\Lab1\Database;


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
            'auto_increment' => true,
            'null' => false
            ])->addColumn('author_name', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ])->save();

        $installer->createTable('book')
            ->addColumn('id', $installer::TYPE_INT, 11, [
                'primary' => true,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ])->addColumn('book_name', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ])->addColumn('isbn', $installer::TYPE_INT, 10, [
                'null' => false
            ])->addColumn('publishing', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ])->addColumn('year_publishing', $installer::TYPE_INT, 10, [
                'null' => false
            ])->addColumn('number_pages', $installer::TYPE_INT, 10, [
                'null' => false
            ])->addColumn('resource_id', $installer::TYPE_INT, 11, [
                'null' => true
            ])->save();

        $installer->createTable('book_author')
            ->addColumn('id', $installer::TYPE_INT, 11, [
                'primary' => true,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ])->addColumn('book_id', $installer::TYPE_INT, 11, [
                'null' => false
            ])->addColumn('author_id', $installer::TYPE_INT, 11, [
                'null' => true
            ])->save();

        $installer->createTable('journal')
            ->addColumn('id', $installer::TYPE_INT, 11, [
                'primary' => true,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ])->addColumn('journal_name', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ])->addColumn('year_issue', $installer::TYPE_INT, 10, [
                'null' => false
            ])->addColumn('number', $installer::TYPE_INT, 10, [
                'null' => false
            ])->addColumn('resource_id', $installer::TYPE_INT, 11, [
                'null' => true
            ])->save();

        $installer->createTable('newspaper')
            ->addColumn('id', $installer::TYPE_INT, 11, [
                'primary' => true,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ])->addColumn('newspaper_name', $installer::TYPE_VARCHAR, 255, [
                'null' => false
            ])->addColumn('date_publication', $installer::TYPE_DATE, null, [
                'null' => false
            ])->save();

        $installer->createTable('resource')
            ->addColumn('id', $installer::TYPE_INT, 11, [
                'primary' => true,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ])->addColumn('title', $installer::TYPE_TEXT, null, [
                'null' => false
            ])->save();
    }
}