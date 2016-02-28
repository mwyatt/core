<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface DatabaseInterface
{
    public function setFetchMode($mode);
    public function connect(array $credentials);
    public function disconnect();
    public function prepare($sql, $options = []);
    public function execute($parameters = []);
    public function fetch();
    public function fetchAll();
    public function getRowCount();
    public function getLastInsertId($name = null);
}
