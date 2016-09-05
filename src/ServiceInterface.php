<?php

namespace Mwyatt\Core;

interface ServiceInterface
{
    public function __construct(\Mwyatt\Core\MapperFactory $mapperFactory, \Mwyatt\Core\ModelFactory $modelFactory);
    public function createModel(array $data);
    public function getMapper($name);
    public function findById($id);
    public function findByIds(array $ids);
    public function findAll();
    public function getRelativeClassName();
}
