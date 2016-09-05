<?php

namespace Mwyatt\Core;

interface MapperInterface
{
    public function createModel(array $data);
    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter);
    public function beginTransaction();
    public function rollBack();
    public function commit();
    public function getRelativeClassName();
    public function setFetchType($type);
    public function getModel();
    public function getIterator($models = [], $relativeClass = '');
    public function findAll();
    public function findByIds(array $ids);
    public function testArrayKeys(array $keys, array $data);
    public function getInsertGenericSql(array $cols);
    public function getUpdateGenericSql(array $cols);
    public function delete($models);
}
