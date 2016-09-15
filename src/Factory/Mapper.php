<?php

namespace Mwyatt\Core\Factory;

class Mapper extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Mapper\\';
    protected $adapter;
    protected $modelFactory;


    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter, \Mwyatt\Core\Factory\Model $modelFactory)
    {
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
    }


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($this->adapter, $this->modelFactory);
    }
}
