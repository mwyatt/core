<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface DatabaseInterface
{


    /**
     * @param array $credentials
     */
    public function setCredentials(array $credentials);


    /**
     * connects to the database
     * @return bool success
     */
    public function connect();
    public function disconnect();
    
    public function prepare($sql, array $options = array());
    public function execute(array $parameters = array());
    
    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null);
    public function fetchAll($fetchStyle = null, $column = 0);
    
    public function select($table, array $bind, $boolOperator = "AND");
    public function insert($table, array $bind);
    public function update($table, array $bind, $where = "");
    public function delete($table, $where = "");
}
