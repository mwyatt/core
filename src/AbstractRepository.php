<?php

namespace Mwyatt\Core;

abstract class AbstractRepository implements \Mwyatt\Core\RepositoryInterface
{
    protected $mapperFactory;


    public function __construct(\Mwyatt\Core\Factory\Mapper $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }


    protected function getMapper($name = null)
    {
        return $this->mapperFactory->get($name ? $name : $this->getRelativeClassName());
    }


    protected function getModel($name = null)
    {
        $mapper = $this->getMapper($name);
        return $mapper->getModel($name);
    }


    private function getDefaultNamespace()
    {
        $match = 'Repository';
        $parts = explode($match, get_class($this));
        return reset($parts) . "$match\\";
    }


    protected function getRelativeClassName()
    {
        return str_replace($this->getDefaultNamespace(), '', get_class($this));
    }


    public function findAll()
    {
        $mapper = $this->getMapper();
        $modelIterator = $mapper->findAll();
        return $modelIterator;
    }


    public function findById($id)
    {
        $mapper = $this->getMapper();
        $modelIterator = $mapper->findByIds([$id]);
        return $modelIterator->current();
    }


    public function findByIds(array $ids)
    {
        $mapper = $this->getMapper();
        $modelIterator = $mapper->findByIds($ids);
        return $modelIterator;
    }


    public function persist(\Mwyatt\Core\ModelInterface $model)
    {
        $mapper = $this->getMapper();
        return $mapper->persist($model);
    }


    public function deleteById(\Mwyatt\Core\ModelInterface $model)
    {
        $mapper = $this->getMapper();
        return $mapper->deleteById($models);
    }
}
