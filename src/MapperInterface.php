<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
interface MapperInterface
{
    public function __construct(\Mwyatt\Core\DatabaseInterface $database);
    public function findAll($type = \PDO::FETCH_CLASS);
    public function findColumn($values, $column = 'id');
    // public function insert();
    public function delete(array $models, $column = 'id');
}
