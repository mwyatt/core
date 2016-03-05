<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
interface MapperInterface
{
    public function __construct(\Mwyatt\Core\DatabaseInterface $database);
    public function findAll();
    public function findColumn($values, $column = 'id');
    // public function insert();
}
