<?php

namespace Mwyatt\Core\Factory;

class Iterator extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Iterator\\';
    protected $contents;


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($this->contents);
    }
}
