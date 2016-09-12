<?php

namespace Mwyatt\Core;

abstract class AbstractRepository implements \Mwyatt\Core\RepositoryInterface
{


    protected $mapperFactory;


    public function __construct(\Mwyatt\Core\Factory\Mapper $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }


    protected function getMapper($name)
    {
        return $this->mapperFactory->get($name);
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


    protected function getMapperLazy()
    {
        return $this->mapperFactory->get($this->getRelativeClassName());
    }


    public function findAll()
    {
        $mapper = $this->getMapperLazy();
        $modelIterator = $mapper->findAll();
        return $modelIterator;
    }


    public function findById($id)
    {
        $mapper = $this->getMapperLazy();
        $modelIterator = $mapper->findByIds([$id]);
        return $modelIterator->current();
    }


    public function findByIds(array $ids)
    {
        $mapper = $this->getMapperLazy();
        $modelIterator = $mapper->findByIds($ids);
        return $modelIterator;
    }


    public function insert(array $data)
    {
        $mapper = $this->getMapperLazy();
        return $mapper->insert($data);
    }


    public function updateById($models)
    {
        $mapper = $this->getMapperLazy();
        return $mapper->updateById($models);
    }


    public function deleteById($models)
    {
        $mapper = $this->getMapperLazy();
        return $mapper->deleteById($models);
    }
}
