<?php
/**
 * Infy Framework
 *
 * @author    <maksimglaz@gmail.com>
 * @category  Core
 * @package   Infy\Core
 * @copyright Copyright (c) 2018 Infy
 * @license   https://www.infy-team.com/license.txt
 */

namespace Infy\Core\App\Database;

/**
 * Interface InstallInterface
 *
 * @category Core
 * @package Infy\Core\App\Database
 * @author <maksimglaz@gmail.com>
 */
interface InstallInterface
{
    /**
     * Install the tables
     *
     * @access public
     * @param Install $installer
     * @return mixed
     */
    public function install(Install $installer);
}