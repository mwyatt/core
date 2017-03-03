<?php

namespace Mwyatt\Core;

interface RepositoryInterface
{
    public function __construct(\Mwyatt\Core\Factory\Mapper $mapperFactory);
    public function findAll();
    public function findById($id);
    public function findByIds(array $ids);
    public function persist(\Mwyatt\Core\ModelInterface $model);
    public function deleteById(\Mwyatt\Core\ModelInterface $model);
}
