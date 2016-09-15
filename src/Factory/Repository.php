<?php

namespace Mwyatt\Core\Factory;

class Repository extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Repository\\';
    protected $mapperFactory;


    public function __construct(\Mwyatt\Core\Factory\Mapper $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($this->mapperFactory);
    }
}
