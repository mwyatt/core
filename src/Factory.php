<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Factory
{


    protected $defaultNamespace;


    public function setDefaultNamespace($namespace)
    {
        $this->defaultNamespace = $namespace;
    }


    public function get($name)
    {
        $namespace = $this->defaultNamespace . $name;
        if (!class_exists($namespace)) {
            throw new \Exception("Factory cannot create $namespace, it does not exist.");
        }
        return new $namespace;
    }
}
