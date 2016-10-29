<?php

namespace Mwyatt\Core;

interface DatabaseInterface
{
    public function connect(
        $host,
        $basename,
        $username,
        $password
    );
    public function disconnect();
    public function prepare($sql, $options = []);
    public function execute($parameters = []);
    public function fetch($mode = \PDO::FETCH_ASSOC, $argument = null);
    public function fetchAll($mode = \PDO::FETCH_ASSOC, $argument = null);
    public function getRowCount();
    public function getLastInsertId($name = null);
    public function beginTransaction();
    public function rollback();
    public function commit();
    public function bindParam($key, $value, $type = null);
    public function getFetchTypeAssoc();
    public function getFetchTypeClass();
    public function getParamInt();
    public function getParamNull();
    public function getParamStr();
    public function getParamBool();
}
