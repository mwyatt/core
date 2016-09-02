<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 */
class MapperFactory extends \Mwyatt\Core\Factory
{


    protected $defaultNamespace = '\\Mwyatt\\Core\\Mapper\\';
    protected $adapter;


    protected $modelFactory;


    public function __construct(
        \Mwyatt\Core\DatabaseInterface $adapter,
        \Mwyatt\Core\ModelFactory $modelFactory
    ) {
    
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
    }


    public function get($name)
    {
        $namespace = $this->defaultNamespace . $name;
        return new $namespace($this->adapter);
    }
}
