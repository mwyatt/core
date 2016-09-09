<?php

namespace Mwyatt\Core;

interface MapperInterface
{
    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter, \Mwyatt\Core\Factory\Model $modelFactory);
    public function getTableNameLazy();
    public function getRelativeClassName();
    public function getModelClassLazy();
    public function getModelLazy(array $data);
    public function getModel($name, array $data);
    public function getIterator($models = [], $requestedClassPath = '');
    public function findAll();
    public function findByIds(array $ids);
    public function getInsertGenericSql(array $cols);
    public function getUpdateGenericSql(array $cols);
    public function deleteById(\Mwyatt\Core\AbstractIterator $models);
}
