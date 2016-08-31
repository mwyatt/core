<?php

namespace Mwyatt\Core;

interface MapperInterface
{
    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter);
    public function getRelativeClassName();
    public function setFetchType($type);
    public function getIterator(array $models);
    public function findAll();
    public function findByIds(array $ids);
    public function lazyPersist(\Mwyatt\Core\ModelInterface $model, array $cols);
}
