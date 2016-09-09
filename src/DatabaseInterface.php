<?php

namespace Mwyatt\Core;

interface DatabaseInterface
{
    public function connect(array $credentials);
    public function disconnect();
    public function prepare($sql, $options = []);
    public function execute($parameters = []);
    public function fetch();
    public function fetchAll();
    public function getRowCount();
    public function getLastInsertId($name = null);
    public function beginTransaction();
    public function rollBack();
    public function commit();
}
